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
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
            /*background-image: url('/images/box-icon.png');*/
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
        }

        .sidebar {
            position: fixed;
            width: 200px;
            height: 100vh;
            background-color: #0f3e59;
            color: white;
            padding: 20px 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: #14506e;
        }

        .main {
            margin-left: 200px;
            padding: 30px;
        }

        .header {
            max-width: unset;
            margin: 0 0 28px 0;
            padding: 0;
            background: none;
            border-radius: 0;
            box-shadow: none;
        }

        .header h2 {
            margin: 0 0 8px 0;
            font-size: 2em;
            color: inherit;
            font-weight: bold;
            letter-spacing: normal;
        }

        .header p {
            margin: 0;
            color: inherit;
            font-size: 1em;
            font-weight: normal;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .summary-alerts {
            display: flex;
            gap: 20px;
        }

        .summary,
        .alerts {
            background-color: white;
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .summary h3,
        .alerts h3 {
            margin-top: 0;
        }

        .chart-placeholder {
            height: 200px;
            background: #f0f0f0;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #eaeaea;
        }

        .logout-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
        }

        .logout-btn a {
            color: #fff;
            text-decoration: underline;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
        }
        .form-row {
            display: flex;
            gap: 20px;
        }
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        form {
            background: rgba(180,200,215,0.7);
            padding: 32px 24px 24px 24px;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            max-width: 900px;
            margin: 0 auto 32px auto;
            position: relative;
        }
        label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #0f3e59;
        }
        input, textarea {
            padding: 10px 12px;
            border: 1px solid #bfc9d1;
            border-radius: 6px;
            font-size: 15px;
            background: #f8fafc;
            transition: border-color 0.2s;
        }
        input:focus, textarea:focus {
            border-color: #0f3e59;
            outline: none;
            background: #fff;
        }
        button[type="submit"] {
            width: 120px;
            background: #1a0099;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 0;
            font-size: 16px;
            font-weight: 700;
            margin-top: 18px;
            cursor: pointer;
            transition: background 0.2s;
            position: static;
            top: unset;
            right: unset;
            display: block;
            margin-left: auto;
        }
        button[type="submit"]:hover {
            background: #14506e;
        }
        .search-bar-form {
            display: flex;
            align-items: stretch;
            gap: 0;
            background: #cbd9e5;
            padding: 12px;
            border-radius: 16px;
            max-width: 900px;
            margin-bottom: 24px;
        }

        .search-bar-input {
            flex: 1;
            border-radius: 8px 0 0 8px;
            border: 1px solid #c3e6cb;
            padding: 10px 16px;
            font-size: 1.1rem;
            height: auto;
            background: white;
        }

        .search-bar-button {
            padding: 0 16px;
            background: #00c389;
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }

        .status-badge.pending {
            background-color: #f0ad4e; /* Yellow/Orange */
            color: #000; /* text-dark */
        }

        .status-badge.approved {
            background-color: #28a745; /* Green */
        }

        .status-badge.rejected {
            background-color: #dc3545; /* Red */
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
