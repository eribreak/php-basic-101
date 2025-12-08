<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
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
            background: lightgreen; 
            color: green;
        }
        .alert-error {
            background: lightcoral;
            color: red;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            text-decoration: none;
            border: 1px solid #ccc;
            background: lightgray;
            color: black;
        }
        .btn-primary {
            background: blue;
            color: white;
        }
        .btn-success {
            background: green;
            color: white;
        }
        .btn-danger {
            background: red;
            color: white;
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
            background: lightgray;
            color: black;
            margin-right: 5px;
        }
        .filter a.active {
            background: blue;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px;
            border: 1px solid lightgray;
            text-align: left;
        }
        th {
            background: lightgray;
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
            border: 1px solid lightgray;
        }
        textarea {
            min-height: 80px;
        }
    </style>
</head>
<body>
    <?= $content ?? '' ?>
</body>
</html>

