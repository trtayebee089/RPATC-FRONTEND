<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration Success</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful!',
            text: '{{ session('message') }}',
            timer: 5000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "https://patc.rajshahidiv.gov.bd";
        });
    </script>

</body>
</html>
