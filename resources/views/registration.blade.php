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
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
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
    <div class="container-fluid mt-5">
        {{-- Centered Header --}}
        @if($invitationDetails->email_header)
            <div class="position-relative mb-4 text-center">
                <div class="position-absolute text-center noto-serif-bengali-700" style="top:50%;right: 0;max-width: 250px; border: 2px solid #80CBC4;color:#2DAA9E;padding: 7px 15px; font-size: 14px;">
                    “দুর্নীতিকে না বলি,<br>
                    কার্যকর জনপ্রশাসন গড়ি।”
                </div>

                {{-- Centered Header --}}
                <img src="https://upload.wikimedia.org/wikipedia/bn/6/61/%E0%A6%AC%E0%A6%BE%E0%A6%82%E0%A6%B2%E0%A6%BE%E0%A6%A6%E0%A7%87%E0%A6%B6_%E0%A6%B2%E0%A7%8B%E0%A6%95-%E0%A6%AA%E0%A7%8D%E0%A6%B0%E0%A6%B6%E0%A6%BE%E0%A6%B8%E0%A6%A8_%E0%A6%AA%E0%A7%8D%E0%A6%B0%E0%A6%B6%E0%A6%BF%E0%A6%95%E0%A7%8D%E0%A6%B7%E0%A6%A3_%E0%A6%95%E0%A7%87%E0%A6%A8%E0%A7%8D%E0%A6%A6%E0%A7%8D%E0%A6%B0.jpg" alt="{{ $invitationDetails->email_header }}" width="80" style="margin-bottom: 15px;">
                <h2 class="fw-bold noto-serif-bengali-700" style="font-size: 24px;color: #006A71;">
                    @php
                        $splitText = explode(',', $invitationDetails->email_header);
                    @endphp
                    {{ $splitText[0] }},<br>
                    {{ $splitText[1] }}
                </h2>
            </div>
        @endif
        <h3 class="mb-4 text-center noto-serif-bengali-700" style="font-size: 18px;color: #006A71;">প্রশিক্ষণের জন্য কর্মচারীদের তালিকা</h3>

        <form id="training-form" enctype="multipart/form-data" >
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="file_upload" class="noto-serif-bengali-500">
                            যে কর্মচারীদের তালিকা প্রশিক্ষণ দিতে চান তা আপলোড করুন (PDF)
                        </label>
                        <input type="file" name="traineeListFile" id="" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="fileInfo" style="width: 100%;padding: 10px;border: 1px solid #000;border-radius: 4px;background: #F6F4F0;display: none;">
                        <table>
                            <tbody><tr>
                                <td width="28%">File Name</td>
                                <td width="5%">:</td>
                                <td><span id="fileName">DEMO REGISTER TEMPLATE.pdf</span></td>
                            </tr>
                            <tr>
                                <td>File Size</td>
                                <td>:</td>
                                <td><span id="fileSize">5637.96 KB</span></td>
                            </tr>
                            <tr>
                                <td>Foramt</td>
                                <td>:</td>
                                <td><span id="fileFormat">application/pdf</span></td>
                            </tr>
                        </tbody></table>
                    </div>
                </div>
            </div>

            <div id="trainee-wrapper">
                <div class="table-responsive mb-4">
                    <table class="table table-bordered" id="trainee-wrapper">
                        <thead class="thead-light">
                            <tr class="text-center noto-serif-bengali-500">
                                <th>অফিস</th>
                                <th>নাম</th>
                                <th>পদবী</th>
                                <th>এন.আই.ডি নম্বর</th>
                                <th>ইমেইল</th>
                                <th>ফোন</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="trainee-row">
                                <td width="15%">
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
                                </td>
                                <td width="15%"><input type="text" class="form-control" name="trainee_name[]" required></td>
                                <td width="25%">
                                    <div style="display: flex; algin-items: center;">
                                        <input type="text" class="form-control" name="trainee_designation[]" required>
                                        <select class="form-control" name="trainee_grade[]" required style="margin-left: 5px;">
                                            <option value=""></option>
                                            @foreach ($gradingList as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td width="12%" class="text-center"><input type="text" class="form-control text-center" name="trainee_nid[]" required></td>
                                <td width="12%"><input type="email" class="form-control" name="trainee_email[]" required></td>
                                <td width="12%"><input type="text" class="form-control" name="trainee_phone[]" required></td>
                                <td width="2%" class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-btn"><i class='bx bx-trash' ></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 m-auto text-center">
                    <button type="button" class="btn btn-secondary noto-serif-bengali-500" id="add-more">যোগ করুন</button>
                    <button type="submit" class="btn btn-primary noto-serif-bengali-500" id="submit-button">জমা দিন</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('input[name="traineeListFile"]').on('change', function () {
                const file = this.files[0];

                if (!file) return;

                const allowedTypes = ['application/pdf'];
                const maxSizeMB = 10;

                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ভুল ফাইল',
                        text: 'শুধুমাত্র PDF ফাইল আপলোড করা যাবে।',
                        confirmButtonText: 'ঠিক আছে'
                    });
                    $(this).val(''); // Clear the input
                    $('#fileInfo').hide();
                    return;
                }

                // Validate file size
                if (file.size > maxSizeMB * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ফাইলটি বড়',
                        text: `ফাইল সাইজ ${maxSizeMB}MB এর বেশি হওয়া যাবে না।`,
                        confirmButtonText: 'ঠিক আছে'
                    });
                    $(this).val('');
                    $('#fileInfo').hide();
                    return;
                }

                // Show file info
                $('#fileName').text(file.name);
                $('#fileSize').text((file.size / 1024).toFixed(2) + ' KB');
                $('#fileFormat').text(file.type);
                $('#fileInfo').show();
            });

            $('#add-more').on('click', function () {
                let newRow = $('#trainee-wrapper tbody .trainee-row').first().clone();
                newRow.find('input').val('');
                newRow.find('select').val('');
                $('#trainee-wrapper tbody').append(newRow);
            });

            $(document).on('click', '.remove-btn', function () {
                if ($('#trainee-wrapper tbody .trainee-row').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'কমপক্ষে একজন প্রশিক্ষণার্থী থাকতে হবে!',
                    });
                }
            });

            $(document).on('blur', 'input[name="trainee_nid[]"]', function () {
                let input = $(this);
                let nid = input.val();
                let errorElement = input.next('.nid-error');

                // Remove existing error message
                errorElement.remove();

                if (nid !== '') {
                    $.ajax({
                        url: "{{ route('nid.check') }}",
                        method: "POST",
                        data: {
                            nid: nid,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            input.next('.nid-error').remove(); // Remove existing error message if any
                            input.removeClass('border-danger'); // Reset if previously added
                            if (response.exists) {
                                input.addClass('border-danger');
                                input.after('<small class="text-danger nid-error noto-serif-bengali-500 text-center" style="text-align: center;">এই NID ইতোমধ্যে বিদ্যমান।</small>');
                            }
                        }
                    });
                }
            });

            document.getElementById('submit-button').addEventListener('click', async function (e) {
                e.preventDefault();

                const form = document.getElementById('training-form');
                const formData = new FormData(form);
                const code = "{{ $code }}";
                
                // Show loading state
                const button = document.getElementById('submit-button');
                button.disabled = true;
                button.innerText = 'Uploading...';

                try {
                    const csrfResponse = await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
                        credentials: 'include'
                    });

                    if (!csrfResponse.ok) {
                        throw new Error('CSRF cookie request failed');
                    }

                    const response = await fetch('http://127.0.0.1:8000/api/registration/store', {
                        method: 'POST',
                        credentials: 'include', // Send cookies with the request
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token to the header
                        },
                        body: formData,
                    });

                    const res = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'সফল!',
                            text: res.message || 'ডেটা সফলভাবে জমা হয়েছে।',
                        });
                        form.reset(); // Reset form after success
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ব্যর্থ!',
                            text: res.message || 'কিছু সমস্যা হয়েছে।',
                        });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'ত্রুটি!',
                        text: 'নেটওয়ার্ক সমস্যা হয়েছে বা সার্ভার সাড়া দিচ্ছে না।',
                    });
                } finally {
                    button.disabled = false;
                    button.innerText = 'জমা দিন';
                }
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
