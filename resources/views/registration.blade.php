<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ session()->get('page_title') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .noto-serif-bengali-700 {
            font-family: "Noto Serif Bengali", serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
            font-variation-settings: "wdth" 100;
        }

        .noto-serif-bengali-500 {
            font-family: "Noto Serif Bengali", serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
            font-variation-settings: "wdth" 100;
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center noto-serif-bengali-700" style="font-size: 28px;">{{ $invitationDetails->email_header }}</h1>
        <h3 class="mb-4 text-center noto-serif-bengali-700" style="font-size: 20px;">{{ session()->get('page_title') }}</h3>

        <form action="{{ route('registration.submit', $code) }}" method="POST">
            @csrf

            <div id="trainee-wrapper">
                <div class="trainee-group border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3 noto-serif-bengali-500">প্রশিক্ষণার্থী তথ্য</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                        </div>
                    </div>
                    <div class="row" id="trainee-form">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="trainee_name[]" class="noto-serif-bengali-500">প্রশিক্ষণার্থীর নাম</label>
                                <input type="text" class="form-control" name="trainee_name[]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="trainee_designation[]" class="noto-serif-bengali-500">প্রশিক্ষণার্থী পদবী</label>
                                <input type="text" class="form-control" name="trainee_designation[]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="office[]" class="noto-serif-bengali-500">অফিস</label>
                                <select class="form-control" name="office[]" required>
                                    <option value=""></option>
                                    @foreach ($listedOffices as $groupName => $offices)
                                        <optgroup label="{{ $groupName }}">
                                            @foreach ($offices as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="trainee_nid[]" class="noto-serif-bengali-500">প্রশিক্ষণার্থীর NID</label>
                                <input type="text" class="form-control" name="trainee_nid[]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="trainee_email[]" class="noto-serif-bengali-500">প্রশিক্ষণার্থীর ইমেইল</label>
                                <input type="email" class="form-control" name="trainee_email[]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="trainee_phone[]" class="noto-serif-bengali-500">প্রশিক্ষণার্থীর ফোন</label>
                                <input type="text" class="form-control" name="trainee_phone[]" required>
                            </div>
                        </div>
                    </div>                                   
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 m-auto text-center">
                    <button type="button" class="btn btn-secondary noto-serif-bengali-500" id="add-more">যোগ করুন</button>
                    <button type="submit" class="btn btn-primary noto-serif-bengali-500">জমা দিন</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#add-more').on('click', function () {
                let newGroup = $('.trainee-group').first().clone();
                newGroup.find('input').val(''); // clear inputs
                newGroup.find('.remove-btn').removeClass('d-none'); // show remove button
                $('#trainee-wrapper').append(newGroup);
            });

            $(document).on('click', '.remove-btn', function () {
                $(this).closest('.trainee-group').remove();
            });
        });
    </script>

    @if (session('message'))
    <script>
        // Show SweetAlert with the message
        Swal.fire({
            icon: "{{ session('success') ? 'success' : 'error' }}",
            title: "{{ session('success') ? 'Success!' : 'Error!' }}",
            text: "{{ session('message') }}",
        });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
