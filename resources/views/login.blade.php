<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - INTELLIMEDS</title>
    <style>
        body {
            background-color: #0f3e59;
            font-family: Arial, sans-serif;
            color: #ffffff;
            text-align: center;
            padding-top: 80px;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
        }

        .login-container img {
            width: 80px;
            margin-bottom: 20px;
        }

        .login-container h1 {
            font-size: 20px;
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 30px;
            border: none;
            font-size: 14px;
        }

        .login-button {
            margin-top: 20px;
            display: inline-block;
            background: none;
            border: none;
            cursor: pointer;
        }

        .login-button img {
            width: 40px;
        }

        .error-message {
            color: #ffb3b3;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/box-icon.png') }}" alt="Logo">

        <h1>Intelligent Medication Inventory<br>Management System<br><strong>(INTELLIMEDS)</strong></h1>

        <!-- Show login error if exists -->
        @if(session('error'))
            <p class="error-message">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('customLogin') }}">
            @csrf
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button class="login-button" type="submit">
                <img src="{{ asset('images/login-icon.png') }}" alt="Login Icon">
            </button>
        </form>
    </div>
</body>
</html>
