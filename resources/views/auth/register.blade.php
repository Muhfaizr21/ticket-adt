<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Create Account</h1>
            <p>Sign up to get started with our platform</p>
        </div>

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <div class="input-with-icon">
                    <i class='bx bx-user'></i>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
            </div>

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
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="password-strength">
                    Use 8 or more characters with a mix of letters, numbers & symbols
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-with-icon">
                    <i class='bx bx-check-shield'></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                </div>
            </div>

            <button type="submit" class="register-button">
                Create Account
            </button>
        </form>

        <div class="terms">
            By creating an account, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
        </div>

        <div class="register-footer">
            <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        </div>
    </div>
</body>
</html>