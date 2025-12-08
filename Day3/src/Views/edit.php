<?php
$layoutFile = __DIR__ . '/layout.php';
ob_start();
?>

<h1>Chỉnh sửa Todo</h1>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="/update">
    <input type="hidden" name="id" value="<?= $todo['id'] ?>">

    <div class="form-group">
        <label for="title">Tiêu đề *</label>
        <input type="text" id="title" name="title" required value="<?= htmlspecialchars($todo['title']) ?>">
    </div>

    <div class="form-group">
        <label for="description">Mô tả</label>
        <textarea id="description" name="description"><?= htmlspecialchars($todo['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục</label>
        <select id="category_id" name="category_id">
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" 
                        <?= ($todo['category_id'] == $category['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
            <option value="pending" <?= $todo['status'] === 'pending' ? 'selected' : '' ?>>Đang làm</option>
            <option value="completed" <?= $todo['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
        </select>
    </div>

    <p>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="/" class="btn">Hủy</a>
    </p>
</form>

<?php
$content = ob_get_clean();
require $layoutFile;
?>

