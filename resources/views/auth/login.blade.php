<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your account to continue</p>
        </div>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class='bx bx-envelope'></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class='bx bx-key'></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
            </div>

            <button type="submit" class="login-button">
                Sign In
            </button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
        </div>
    </div>
</body>

</html>
