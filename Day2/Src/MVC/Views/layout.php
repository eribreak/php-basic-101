<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewData['title'] ?? 'Mini MVC' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: black;
            background: white;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: black;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav h1 {
            font-size: 1.5rem;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .content {
            background: white;
            padding: 2rem;
            border-radius: 5px;
        }
        footer {
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            color: black;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: blue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid black;
        }
        table th {
            background: white;
            font-weight: bold;
        }
        form {
            max-width: 500px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1>Mini MVC Framework</h1>
                <ul>
                    <li><a href="/">Trang Chủ</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/users">Users</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <?= $content ?? '' ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; PHP OOP & MVC</p>
        </div>
    </footer>
</body>
</html>

