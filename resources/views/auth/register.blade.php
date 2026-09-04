<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Everyday</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        /* Styling pesan hint/feedback real-time */
        .password-hint {
            font-size: 0.8rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .password-hint.valid {
            color: #10b981; /* Hijau */
        }
        .password-hint.invalid {
            color: #ef4444; /* Merah */
        }
        .input-wrapper.error input {
            border-color: #ef4444 !important;
        }
        .input-wrapper.success input {
            border-color: #10b981 !important;
        }
    </style>
</head>
<body>
    <div class="split-layout">
        <div class="split-image"></div>
        <div class="split-form">
            <div class="login-container">

                <div class="logo-section">
                    <span class="logo-icon"><i class="fa-brands fa-accusoft"></i></span>
                    <div class="appName">Everyday</div>
                    <p class="tagline">Register to Everyday</p>
                </div>

                @if ($errors->any())
                    <div class="error-badge">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('adminregister.store') }}" id="registerForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <div class="input-wrapper">
                            <input
                                class="form-input"
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="John Doe"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-wrapper">
                            <input
                                class="form-input"
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="johndoe"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-wrapper">
                            <input
                                class="form-input"
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="johndoe@everyday.com"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper" id="passWrapper">
                            <input
                                class="form-input"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <div class="input-wrapper" id="confirmPassWrapper">
                            <input
                                class="form-input"
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                        <!-- Tempat Teks Feedback Real-time -->
                        <div id="passwordMatchHint" class="password-hint" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">Register</button>
                </form>

                <div class="auth-footer">
                    <p>Already organized? <a href="{{ route('login') }}" class="auth-link">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT VALIDASI REAL-TIME -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const confirmWrapper = document.getElementById('confirmPassWrapper');
            const hint = document.getElementById('passwordMatchHint');
            const btnSubmit = document.getElementById('btnSubmit');

            function validatePasswordMatch() {
                const passVal = password.value;
                const confirmVal = confirmPassword.value;

                // Jika confirm password belum diisi apa-apa, sembunyikan pesan
                if (confirmVal === '') {
                    hint.style.display = 'none';
                    confirmWrapper.classList.remove('error', 'success');
                    btnSubmit.disabled = false;
                    return;
                }

                hint.style.display = 'flex';

                if (passVal === confirmVal) {
                    // Password Cocok
                    hint.className = 'password-hint valid';
                    hint.innerHTML = '<i class="fa-solid fa-circle-check"></i> Passwords match';
                    confirmWrapper.classList.remove('error');
                    confirmWrapper.classList.add('success');
                    btnSubmit.disabled = false;
                } else {
                    // Password Tidak Cocok
                    hint.className = 'password-hint invalid';
                    hint.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Passwords do not match';
                    confirmWrapper.classList.remove('success');
                    confirmWrapper.classList.add('error');
                    btnSubmit.disabled = true; // Opsional: matikan tombol submit jika tidak cocok
                }
            }

            // Jalankan validasi setiap kali user mengetik di password / confirm password
            password.addEventListener('input', validatePasswordMatch);
            confirmPassword.addEventListener('input', validatePasswordMatch);
        });
    </script>
</body>
</html>