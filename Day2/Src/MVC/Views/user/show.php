<h1><?= $viewData['title'] ?? '' ?></h1>

<div>
    <p><strong>ID:</strong> <?= $viewData['user']['id'] ?? '' ?></p>
    <p><strong>Tên:</strong> <?= $viewData['user']['name'] ?? '' ?></p>
    <p><strong>Email:</strong> <?= $viewData['user']['email'] ?? '' ?></p>
    <p><strong>Tuổi:</strong> <?= $viewData['user']['age'] ?? '' ?></p>
    <p><strong>Ngày tạo:</strong> <?= $viewData['user']['created_at'] ?? '' ?></p>
</div>

<a href="/users" class="btn">Quay Lại Danh Sách</a>

