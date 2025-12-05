<h1><?= $viewData['title'] ?? '' ?></h1>
<p><?= $viewData['message'] ?? '' ?></p>

<h2>Tính năng:</h2>
<ul>
    <?php foreach (($viewData['features'] ?? []) as $feature): ?>
        <li><?= $feature ?></li>
    <?php endforeach; ?>
</ul>

<h2>Hướng dẫn sử dụng:</h2>
<p>Để xem các ví dụ OOP, chạy file <code>oop-demo.php</code></p>
<p>Để test MVC framework, truy cập các route đã được định nghĩa trong <code>index.php</code></p>

