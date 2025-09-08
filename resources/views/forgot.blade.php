<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>KTHRM - Kridentia</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

    <style>
        body,
        html {
            font-family: "Figtree", sans-serif;
            background-color: white;
            color: black;
            height: 100%;
            overflow: hidden;
        }

        .bg-image-vertical {
            background-repeat: no-repeat;
            background-position: right center;
            background-size: auto 100%;
        }

        @media (min-width: 1025px) {
            .h-custom-2 {
                height: 100%;
            }
        }

        .form-control-sm {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        .btn-info {
            background-color: #00aeef;
            border-color: #00aeef;
        }

        .logo {
            width: 50%;
        }

        .login-form-container {
            margin-top: 0;
        }

        .btn-check {
            margin-left: 10px;
            height: 100%;
        }
    </style>
</head>

<body>
    @if ($errors->any())
        <div class="alert alert-danger shadow position-fixed text-center px-4 py-3"
            style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 600px; width: 90%; border-radius: 10px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @elseif(session('status'))
        <div class="alert alert-success shadow position-fixed text-center px-4 py-3"
            style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 600px; width: 90%; border-radius: 10px;">
            {{ session('status') }}
        </div>
    @endif
    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="px-5 ms-xl-4 mt-5">
                        <img src="{{ asset('kridentia.jpg') }}" alt="Logo" class="logo" />
                    </div>

                    <div class="px-5 ms-xl-4 mt-5 login-form-container">
                        <!-- Verify Form -->
                        <form style="width: 23rem;" method="POST" action="{{ route('forgot.check') }}" id="verifyForm">
                            @csrf

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Forgot Password</h3>

                            <div class="form-row mb-2">
                                <div class="col-12 mb-2">
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        placeholder="Full Name" value="{{ old('name') }}" required id="nameInput" />
                                </div>
                                <div class="col-8">
                                    <input type="email" name="email" class="form-control form-control-sm"
                                        placeholder="Email" value="{{ old('email') }}" required id="emailInput" />
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-info btn-sm w-100" type="submit" id="verifyBtn">
                                        Verify
                                    </button>
                                </div>
                                <div class="col-12 mb-2">
                                    <small style="color: #a6a6a6;">*Please verify your account before proceeding to
                                        reset password.</small>
                                </div>
                            </div>


                        </form>

                        <!-- Reset Password Form (always visible but disabled initially) -->
                        <form style="width: 23rem; margin-top: 2rem;" method="POST" action="{{ route('forgot.reset') }}"
                            id="resetForm">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ session('user') ? session('user')->id : '' }}"
                                id="userIdInput" />

                            <div class="form-group mb-2">
                                <input type="password" name="password" class="form-control form-control-lg"
                                    placeholder="New Password" required id="passwordInput" disabled />
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                    placeholder="Confirm New Password" required id="passwordConfirmInput" disabled />
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-info btn-lg btn-block" type="submit" id="resetBtn" disabled>
                                    Reset Password
                                </button>
                            </div>
                        </form>

                        <p class="mb-5 pb-lg-2 mt-5"><a class="text-muted" href="{{ route("welcome") }}">Back to
                                Login</a></p>
                    </div>
                </div>

                <div class="col-sm-6 px-0 d-none d-sm-block bg-image-vertical">
                    <img src="{{ asset('kridentia-building.jpg') }}" alt="Login image" class="w-100 vh-100"
                        style="object-fit: cover; object-position: left" />
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const userExists = @json(session('user') ? true : false);

            const passwordInput = document.getElementById("passwordInput");
            const passwordConfirmInput = document.getElementById("passwordConfirmInput");
            const resetBtn = document.getElementById("resetBtn");

            // Create error message elements dynamically below inputs
            function createErrorElement(input) {
                let errorEl = document.createElement('small');
                errorEl.style.color = 'red';
                errorEl.style.display = 'block';
                errorEl.style.marginTop = '0.25rem';
                errorEl.classList.add('error-message');
                input.parentNode.appendChild(errorEl);
                return errorEl;
            }

            let passwordError = createErrorElement(passwordInput);
            let confirmError = createErrorElement(passwordConfirmInput);

            if (userExists) {
                passwordInput.disabled = false;
                passwordConfirmInput.disabled = false;
                resetBtn.disabled = true; // disable initially
            }

            function validatePassword() {
                if (passwordInput.value.length === 0) {
                    passwordError.textContent = 'New password is required.';
                    return false;
                } else if (passwordInput.value.length < 8) {
                    passwordError.textContent = 'Password must be at least 8 characters.';
                    return false;
                } else {
                    passwordError.textContent = '';
                    return true;
                }
            }

            function validateConfirmPassword() {
                if (passwordConfirmInput.value.length === 0) {
                    confirmError.textContent = 'Please confirm your new password.';
                    return false;
                } else if (passwordConfirmInput.value !== passwordInput.value) {
                    confirmError.textContent = 'Passwords do not match.';
                    return false;
                } else {
                    confirmError.textContent = '';
                    return true;
                }
            }

            function checkFormValidity() {
                const validPassword = validatePassword();
                const validConfirm = validateConfirmPassword();

                resetBtn.disabled = !(validPassword && validConfirm);
            }

            passwordInput.addEventListener('input', () => {
                validatePassword();
                validateConfirmPassword(); // also validate confirm on password change
                checkFormValidity();
            });

            passwordConfirmInput.addEventListener('input', () => {
                validateConfirmPassword();
                checkFormValidity();
            });
        });
    </script>
</body>

</html>