<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .login-bg {
            background: url('/img/background.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 60px;
        }
        .left-panel {
            background: rgba(0,0,0,0.45);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            border-radius: 0 24px 24px 0;
            min-width: 350px;
            max-width: 450px;
            width: 35vw;
        }
        .left-panel img {
            width: 160px;
            height: 160px;
            object-fit: contain;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            padding: 18px;
            margin-bottom: 32px;
        }
        .system-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
        }
        .system-desc {
            font-size: 1.1rem;
            font-weight: 400;
            text-align: center;
            margin-bottom: 0;
        }
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-width: 350px;
            max-width: 600px;
            width: 40vw;
            margin-left: auto;
            margin-right: 48px;
        }
        .login-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.12);
            padding: 40px 32px;
            width: 100%;
            max-width: 400px;
        }
        @media (max-width: 900px) {
            .login-bg {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0;
            }
            .left-panel, .right-panel {
                max-width: 90vw;
                min-width: unset;
                width: 100vw;
                border-radius: 24px;
            }
            .left-panel {
                margin-bottom: 32px;
                border-radius: 24px;
            }
            .right-panel {
                justify-content: center;
                margin-left: 0;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-bg">
        <div class="left-panel">
            <img src="/img/cantil-e-logo.png" alt="Barangay Cantil-E Logo">
            <div class="system-title">Barangay Cantil-E<br>Management System</div>
            <div class="system-desc">Management System</div>
        </div>
        <div class="right-panel">
            <div class="login-card">
                <h4 class="mb-3 text-center">Barangay Cantil-E Management System</h4>
                <p class="mb-4 text-center text-muted">Sign in to your account</p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember"></label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" style="background:#7b3f2c; border:none;">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 