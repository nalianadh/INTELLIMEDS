<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - INTELLIMEDS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a2e44 0%, #1a5f7a 50%, #0f3e59 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(26, 95, 122, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            animation: pulse 8s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(15, 62, 89, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
            animation: pulse 6s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .login-container {
            max-width: 440px;
            width: 90%;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3),
                        0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .logo-wrapper img {
            width: 90px;
            height: 90px;
            filter: drop-shadow(0 4px 12px rgba(255, 255, 255, 0.2));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .login-container h1 {
            font-size: 18px;
            margin-bottom: 40px;
            line-height: 1.6;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        .login-container h1 strong {
            font-weight: 700;
            font-size: 22px;
            display: block;
            margin-top: 8px;
            background: linear-gradient(135deg, #ffffff 0%, #a8dadc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-message {
            background: rgba(255, 82, 82, 0.15);
            color: #ffcccb;
            padding: 12px 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            font-size: 14px;
            border: 1px solid rgba(255, 82, 82, 0.3);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 16px 24px;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            font-size: 15px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transition: all 0.3s ease;
            outline: none;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1);
        }

        .login-button {
            margin-top: 30px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2a9d8f 0%, #1a5f7a 100%);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(42, 157, 143, 0.4);
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3dbaa2 0%, #2a7f8f 100%);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .login-button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 30px rgba(42, 157, 143, 0.6);
        }

        .login-button:hover::before {
            opacity: 1;
        }

        .login-button:active {
            transform: translateY(-1px) scale(1.02);
        }

        .login-button img {
            width: 32px;
            height: 32px;
            position: relative;
            z-index: 1;
            filter: brightness(0) invert(1);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
            }

            .login-container h1 {
                font-size: 16px;
            }

            .login-container h1 strong {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-wrapper">
            <img src="{{ asset('images/box-icon.png') }}" alt="Logo">
        </div>

        <h1>Intelligent Medication Inventory<br>Management System<br><strong>(INTELLIMEDS)</strong></h1>

        <!-- Show login error if exists -->
        @if(session('error'))
            <p class="error-message">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('customLogin') }}">
            @csrf
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button class="login-button" type="submit">
                <img src="{{ asset('images/login-icon.png') }}" alt="Login Icon">
            </button>
        </form>
    </div>
</body>
</html>