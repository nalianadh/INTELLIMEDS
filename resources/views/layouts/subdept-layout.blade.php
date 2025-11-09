<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sub Department')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { background: #e3f0fc; font-family: 'Segoe UI', Arial, sans-serif; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background: #226699;
            color: #fff;
            padding: 24px 0;
            z-index: 100;
        }
        .sidebar h2 { text-align: center; font-weight: 700; margin-bottom: 32px; }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px 32px;
            text-decoration: none;
            font-weight: 600;
            border-radius: 6px;
            margin-bottom: 8px;
            transition: background 0.2s;
        }
        .sidebar a.active, .sidebar a:hover {
            background: #1a179a;
            color: #fff;
        }
        .main {
            margin-left: 240px;
            padding: 32px;
        }
        .header { margin-bottom: 32px; }
        .header h2 { font-weight: 700; color: #20425c; }
        .header p { color: #228be6; font-size: 1rem; }
    </style>
</head>
<body>
    @include('components.sidebar-subdept')
    <div class="main">
        <div class="header">
            <h2>@yield('page_title', 'Welcome To INTELLIMEDS')</h2>
            <p>@yield('breadcrumb', 'Home / Sub Department')</p>
        </div>
        @yield('content')
    </div>
</body>
</html>
