<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            if($invitationDetails){
                $gradingList = DB::table('grading_policies')->where('status', 'active')->get();
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
    
                return view('registration', compact('code', 'invitationDetails', 'listedOffices', 'gradingList'));
            } else {
                throw new Exception('Invitation Letter Not Found With Code: ' . $code);
            }

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
                'trainee_grade.*' => 'required',
                'office.*' => 'required|exists:organizations,id',
                'location.*' => 'required|string',
                'traineeListFile' => 'required|file|mimes:pdf|max:10240',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successfully completed.'
            ]);

            $invitationLetter = DB::table('invitations')->where('code', $request->code)->first();

            if(!$invitationLetter){
                throw new \Exception('Invitation Letter Not Found For ' . $request->code);
            }
    
            DB::beginTransaction();

            foreach ($request->trainee_name as $key => $traineeName) {
                if (!isset($request->trainee_nid[$key]) || is_null($request->trainee_nid[$key])) {
                    throw new \Exception('Trainee NID is missing for key ' . $key);
                }
                
                DB::table('invitation_registrations')->insert([
                    'code' => $request->code,
                    'traineeName' => $traineeName,
                    'traineeNid' => $request->trainee_nid[$key],
                    'traineeEmail' => $request->trainee_email[$key],
                    'traineePhone' => $request->trainee_phone[$key],
                    'traineeDesignation' => $request->trainee_designation[$key],
                    'gradeId' => $request->trainee_grade[$key],
                    'organizationId' => $request->office[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if ($request->hasFile('traineeListFile')) {
                $file = $request->file('traineeListFile');
                $fileName = $file->getClientOriginalName();
                $originalFilePath = $file->storeAs('uploads', $fileName, 'local');
                $originalFilePath = storage_path('app/' . $originalFilePath);
                
                $compressedFilePath = storage_path('app/uploads/compressed_' . $fileName);
            
                // Compress the PDF using Ghostscript
                $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$compressedFilePath $originalFilePath";
                exec($command);
                
                if (file_exists($originalFilePath)) {
                    unlink($originalFilePath);
                }

                DB::table('uploaded_documents')->insert([
                    'document_type' => 'Trainee List',
                    'file_path' => $compressedFilePath,
                    'file_name' => $file->getClientOriginalName(),
                    'submitted_by' => Auth::check() ? Auth::id() : null,
                    'related_model_type' => 'App\Models\Invitation',
                    'related_model_id' => $invitationLetter->id,
                    'upload_context' => 'submission',
                    'uploaded_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Registration successfully completed.'
            ]);
    
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('An error occurred during the trainee registration process: ', ['error' => $ex->getMessage()]);
            return redirect()->back()->with([
                'success' => false,
                'message' => 'An error occurred during the trainee registration process: ' . $ex->getMessage()
            ]);
        }
    }

    public function checkNid(Request $request)
    {
        $exists = DB::table('invitation_registrations')->where('traineeNid', $request->nid)->exists();

        return response()->json(['exists' => $exists]);
    }

}
