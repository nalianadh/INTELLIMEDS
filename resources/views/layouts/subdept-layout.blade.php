<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sub Department') - INTELLIMEDS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0f3e59;
            --primary-hover: #1a5270;
            --secondary-color: #228be6;
            --sidebar-bg: #1e3a52;
            --sidebar-hover: #2c5270;
            --sidebar-active: #0a2a3f;
            --bg-light: #f5f8fa;
            --bg-white: #ffffff;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --border-color: #e1e8ed;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
        }

        body {
            background: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #152d42 100%);
            color: #fff;
            padding: 0;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        
        .sidebar-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            margin-bottom: 10px;
            opacity: 0.95;
        }


        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 24px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .sidebar-header .subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 16px 12px;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 16px;
            margin-bottom: 4px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.9);
            padding: 12px 16px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-nav a i {
            width: 20px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-nav a.active {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--secondary-color);
            border-radius: 0 4px 4px 0;
        }

        /* Main Content Area */
        .main {
            margin-left: 260px;
            padding: 32px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Header Styles */
        .page-header {
            background: var(--bg-white);
            padding: 24px 28px;
            border-radius: 12px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary-color);
        }

        .page-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .page-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h2 i {
            font-size: 22px;
            color: var(--secondary-color);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            flex-wrap: wrap;
        }

        .breadcrumb i {
            font-size: 12px;
        }

        .breadcrumb a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .breadcrumb span {
            color: var(--text-dark);
            font-weight: 500;
        }

        /* Alert Styles */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            border: 1px solid transparent;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert i {
            font-size: 18px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        /* Form Styles */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group label i {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 11px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background: var(--bg-white);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(34, 139, 230, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Button Styles */
        .btn {
            padding: 11px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
            justify-content: center;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Card Styles */
        .card {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--bg-light);
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .page-header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-1 { margin-top: 8px; }
        .mt-2 { margin-top: 16px; }
        .mt-3 { margin-top: 24px; }
        .mb-1 { margin-bottom: 8px; }
        .mb-2 { margin-bottom: 16px; }
        .mb-3 { margin-bottom: 24px; }
        .p-0 { padding: 0; }
        .flex { display: flex; }
        .flex-center { display: flex; align-items: center; justify-content: center; }
        .flex-between { display: flex; align-items: center; justify-content: space-between; }
        .gap-1 { gap: 8px; }
        .gap-2 { gap: 16px; }
        .gap-3 { gap: 24px; }
    </style>
    @stack('styles')
</head>
<body>
    @include('components.sidebar-subdept')
    
    <div class="main">
        <div class="page-header">
            <div class="page-header-top">
                <h2>
                    @if(View::hasSection('page_icon'))
                        <i class="@yield('page_icon')"></i>
                    @endif
                    @yield('page_title', 'Welcome To INTELLIMEDS')
                </h2>
            </div>
            <div class="breadcrumb">
                <i class="fas fa-home"></i>
                @php
                    $breadcrumbParts = explode(' / ', trim(View::yieldContent('breadcrumb', 'Home')));
                @endphp
                @foreach($breadcrumbParts as $index => $part)
                    @if($index < count($breadcrumbParts) - 1)
                        <a href="#">{{ trim($part) }}</a>
                        <i class="fas fa-chevron-right"></i>
                    @else
                        <span>{{ trim($part) }}</span>
                    @endif
                @endforeach
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>