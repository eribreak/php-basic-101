<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList - Laravel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        h1 {
            margin-bottom: 20px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            text-decoration: none;
            border: 1px solid #ccc;
            background: #f0f0f0;
            color: #333;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .btn-success {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .filter {
            margin-bottom: 15px;
        }
        .filter a {
            padding: 5px 10px;
            text-decoration: none;
            background: #f0f0f0;
            color: #333;
            margin-right: 5px;
        }
        .filter a.active {
            background: #007bff;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
        }
        textarea {
            min-height: 80px;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>

