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
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <label class="form-label" for="login">Email or Username</label>
        <details style="position: relative; display: inline-block;"><summary style="cursor: pointer; color: #6b7280; list-style: none; display: flex; align-items: center; user-select: none;" title="Info Default User">
        <!-- SVG Icon Info -->
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
    </summary>
        <div style="position: absolute; right: 0; top: 24px; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; padding: 10px; width: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 50; font-size: 0.75rem; color: #374151;">
            <strong>Default User: </strong><br><code>everyday/database/seeders/AdminSeeder.php</code>
        </div>
    </details>
    </div>

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