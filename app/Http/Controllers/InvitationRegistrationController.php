<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InvitationRegistrationController extends Controller
{
    public function registration(Request $request){
        try{
            session([
                'page_title' => 'রেজিস্ট্রেশন ফরম',
            ]);

            $validatedUrl = Validator::make(
                ['code' => $request->code],
                ['code' => 'required'],
                ['code.required' => 'Course Code Is Required for the Registration Process.']
            );

            if ($validatedUrl->fails()) {
                throw new Exception('Invalid or missing course code.');
            }
            $code = $request->code;

            $invitationDetails = DB::table('invitations')->where('code', $code)->first();
            $listedOffices = DB::table('organizations')
                ->join('organization_groups', 'organizations.group_id', '=', 'organization_groups.id')
                ->where('organizations.status', 'active')
                ->select(
                    'organizations.id',
                    'organizations.name',
                    'organizations.group_id',
                    'organization_groups.name as group_name'
                )
                ->orderBy('organization_groups.name')
                ->orderBy('organizations.name')
                ->get()
                ->groupBy('group_name');

            return view('registration', compact('code', 'invitationDetails', 'listedOffices'));
        } catch (Exception $ex){
            Log::error('An Error Occurred In Registration View', [
                'message' => $ex->getMessage()
            ]);
            return redirect()->to('https://patc.rajshahidiv.gov.bd');
        }
    }

    public function store(Request $request) {
        try {
            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'trainee_name.*' => 'required|string',
                'trainee_nid.*' => 'required|string|unique:invitation_registrations,traineeNid', // Check uniqueness
                'trainee_email.*' => 'required|email',
                'trainee_phone.*' => 'required|string',
                'trainee_designation.*' => 'required|string',
                'office.*' => 'required|exists:organizations,id',
                'location.*' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Process each trainee's data
            foreach ($request->trainee_name as $key => $traineeName) {
                if (!isset($request->trainee_nid[$key]) || is_null($request->trainee_nid[$key])) {
                    throw new \Exception('Trainee NID is missing for key ' . $key);
                }
    
                // Insert registration into the database
                DB::table('invitation_registrations')->insert([
                    'code' => $request->code,
                    'traineeName' => $traineeName,
                    'traineeNid' => $request->trainee_nid[$key],
                    'traineeEmail' => $request->trainee_email[$key],
                    'traineePhone' => $request->trainee_phone[$key],
                    'traineeDesignation' => $request->trainee_designation[$key],
                    'organizationId' => $request->office[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
    
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Registration successfully completed.'
            ]);
    
        } catch (\Exception $ex) {
            Log::error('An error occurred during the trainee registration process: ', ['error' => $ex->getMessage()]);
            return redirect()->back()->with([
                'success' => false,
                'message' => 'An error occurred during the trainee registration process: ' . $ex->getMessage()
            ]);
        }
    }

}
