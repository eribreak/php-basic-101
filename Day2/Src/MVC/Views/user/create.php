<h1><?= $viewData['title'] ?? '' ?></h1>

<?php if (isset($viewData['error'])): ?>
    <div class="error"><?= $viewData['error'] ?></div>
<?php endif; ?>

<form method="POST" action="/users/store">
    <div class="form-group">
        <label for="name">Tên:</label>
        <input type="text" id="name" name="name" value="<?= $viewData['name'] ?? '' ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $viewData['email'] ?? '' ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Tạo User</button>
    <a href="/users" class="btn">Hủy</a>
</form>

