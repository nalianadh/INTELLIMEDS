<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'INTELLIMEDS')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            line-height: 1.6;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            width: 240px;
            height: 100vh;
            background: linear-gradient(180deg, #0f3e59 0%, #1a5270 100%);
            color: white;
            padding: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .sidebar h2 {
            text-align: center;
            margin: 0;
            padding: 24px 20px;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar a i {
            margin-right: 12px;
            width: 18px;
            font-size: 16px;
        }

        .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #00c389;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
            padding-left: 28px;
        }

        .logout-btn {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .logout-btn a {
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .logout-btn a:hover {
            color: #fff;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: 240px;
            padding: 32px 40px;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            margin-bottom: 32px;
        }

        .header h2 {
            margin: 0 0 4px 0;
            font-size: 28px;
            color: #0f3e59;
            font-weight: 600;
        }

        .header p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* ===== ALERTS ===== */
        .alert {
            margin-bottom: 24px;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
        }

        .alert-success::before {
            content: "✓";
            font-weight: bold;
            font-size: 18px;
        }

        /* ===== FORMS ===== */
        form {
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            max-width: 800px;
            border: 1px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-row .form-group {
            margin-bottom: 0;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #212529;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #0f3e59;
            outline: none;
            box-shadow: 0 0 0 3px rgba(15, 62, 89, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input::placeholder,
        textarea::placeholder {
            color: #adb5bd;
        }

        button[type="submit"] {
            background: #0f3e59;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 11px 28px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button[type="submit"]:hover {
            background: #1a5270;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(15, 62, 89, 0.2);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* ===== SEARCH BAR ===== */
        .search-bar-form {
            display: flex;
            align-items: stretch;
            gap: 0;
            background: #ffffff;
            padding: 8px;
            border-radius: 8px;
            max-width: 600px;
            margin-bottom: 24px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .search-bar-input {
            flex: 1;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            background: transparent;
        }

        .search-bar-input:focus {
            outline: none;
            box-shadow: none;
        }

        .search-bar-button {
            padding: 0 20px;
            background: #0f3e59;
            color: white;
            border: none;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .search-bar-button:hover {
            background: #1a5270;
        }

        /* ===== CARDS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .card {
            background-color: #ffffff;
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        /* ===== TABLES ===== */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 16px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        table th,
        table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 13px;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-badge.approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-badge.rejected {
            background-color: #f8d7da;
            color: #842029;
        }

        /* ===== SUMMARY/ALERTS SECTIONS ===== */
        .summary-alerts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .summary,
        .alerts {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
        }

        .summary h3,
        .alerts h3 {
            margin: 0 0 16px 0;
            font-size: 18px;
            font-weight: 600;
            color: #0f3e59;
        }

        .chart-placeholder {
            height: 200px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .sidebar,
            .header .action-bar, /* hides top buttons */
            .no-print {
                display: none !important;
            }

            .main,
            .content {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .transfer-slip {
                box-shadow: none !important;
                border: none !important;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Content --}}
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>