<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Everyday</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="split-layout">
        <div class="split-image"></div>
        <div class="split-form">
            <div class="login-container">

                <div class="logo-section">
                    <span class="logo-icon"><i class="fa-brands fa-accusoft"></i></span>
                    <div class="appName">Everyday</div>
                    <p class="tagline">Sign in to your workspace</p>
                </div>

                <!-- ALERT SUCCESS (Ditaruh di sini biar rapi di atas form) -->
                 
                @if (session('success'))
                    <div class="success-badge" style="background-color: #d1fae5; color: #065f46; padding: 10px 14px; border-radius: 8px; margin-bottom: 15px; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- ALERT ERROR -->
                @if ($errors->any())
                    <div class="error-badge">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="login">Email or Username</label>
                        <div class="input-wrapper">
                            <input
                                class="form-input"
                                type="text"
                                id="login"
                                name="login"
                                value="{{ old('login') }}"
                                placeholder="johndoe@everyday.com or johndoe"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
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

                    <button type="submit" class="btn-submit">Sign In</button>
                </form>
                
                <div class="auth-footer">
                    <p>Want to makes life organized? <a href="{{ route('adminregister') }}" class="auth-link">Join Us</a></p>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>