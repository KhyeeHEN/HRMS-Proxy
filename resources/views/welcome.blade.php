<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>KTHRM- Kridentia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Styles -->
    <style>
        body,
        html {
            font-family: 'Figtree', sans-serif;
            background-color: white;
            color: black;
            height: 100%;
            overflow: hidden;
            /* Disable scrolling */
        }

        .bg-image-vertical {
            position: relative;
            overflow: hidden;
            background-repeat: no-repeat;
            background-position: right center;
            background-size: auto 100%;
        }

        @media (min-width: 1025px) {
            .h-custom-2 {
                height: 100%;
            }
        }

        .form-control-lg {
            background-color: white;
            border: 1px solid #cccccc;
            color: black;
        }

        .btn-info {
            background-color: #00aeef;
            border-color: #00aeef;
        }

        .link-info {
            color: #00aeef;
        }

        .text-muted {
            color: #ccc !important;
        }

        .logo {
            height: auto;
            /* Maintain aspect ratio */
            width: 50%;
            /* Adjust as needed */
        }

        .login-form-container {
            margin-top: 0;
            /* Adjust margin as needed */
        }

        #floating-success {
            position: fixed;
            top: 20px;
            /* jarak dari atas page */
            left: 50%;
            /* tengah page secara horizontal */
            transform: translateX(-50%);
            /* align center */
            z-index: 9999;
            /* pastikan ia di atas semua elemen lain */
            width: auto;
            max-width: 90%;
            padding: 15px 30px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-size: 16px;
        }
    </style>
</head>

<body>
    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                @if(Cookie::has('logout_message'))
                    <div class="alert alert-success shadow position-fixed text-center px-4 py-3"
                        style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 600px; width: 90%; border-radius: 10px;">
                        {{ Cookie::get('logout_message') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger shadow position-fixed text-center px-4 py-3"
                        style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 600px; width: 90%; border-radius: 10px;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-success shadow position-fixed text-center px-4 py-3"
                        style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 600px; width: 90%; border-radius: 10px;">
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger shadow position-fixed text-center px-4 py-3"
                        style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 600px; width: 90%; border-radius: 10px;">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="col-sm-6">
                    <div class="px-5 ms-xl-4 mt-5">
                        <img src="{{ asset('kridentia.jpg') }}" alt="Logo" class="logo">
                    </div>
                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 login-form-container">
                        <form style="width: 23rem;" method="POST" action="{{ route('login.post') }}" id="loginForm">
                            @csrf
                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>

                            <!-- Email/Username Field -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="login">Email or Username</label>
                                <input type="text" id="login" class="form-control form-control-lg" name="login"
                                    required />
                                <span class="text-danger" id="loginError"></span> <!-- Field error message -->
                            </div>

                            <!-- Password Field -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" class="form-control form-control-lg"
                                    name="password" required />
                                <span class="text-danger" id="passwordError"></span> <!-- Field error message -->
                            </div>

                            <!-- Login Button -->
                            <div class="pt-1 mb-4">
                                <button class="btn btn-info btn-lg btn-block" type="submit">LOGIN</button>
                            </div>

                            <p class="mb-5 pb-lg-2"><a class="text-muted" href="{{ route('forgot') }}">Forgot
                                    password?</a></p>
                        </form>
                    </div>
                </div>

                <!-- Right Side Image -->
                <div class="col-sm-6 px-0 d-none d-sm-block bg-image-vertical">
                    <img src="{{ asset('kridentia-building.jpg') }}" alt="Login image" class="w-100 vh-100"
                        style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript for Field Validation -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#login, #password').on('input', function () {
                $.ajax({
                    url: "{{ route('login.validate') }}",
                    type: "POST",
                    data: {
                        login: $('#login').val(),
                        password: $('#password').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#loginError, #passwordError').text(''); // Clear errors
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        $('#loginError').text(errors.login ? errors.login[0] : '');
                        $('#passwordError').text(errors.password ? errors.password[0] : '');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alertBox = document.getElementById("logout-alert");
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.display = 'none';

                    // Remove ?logout=1 from URL without reload
                    const url = new URL(window.location);
                    url.searchParams.delete('logout');
                    window.history.replaceState({}, document.title, url.pathname);
                }, 3000); // hide after 3 seconds
            }
        });
    </script>
</body>

</html>