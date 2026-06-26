<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    if (empty($name)) {
        $error = 'Название обязательно';
    } else {
        $stmt = db()->prepare('INSERT INTO task_statuses (name, sort_order) VALUES (:name, :sort_order)');
        $stmt->execute(['name' => $name, 'sort_order' => $sortOrder]);
        flash('success', 'Статус создан');
        redirect('index.php?action=statuses');
    }
}
?>

<form method="post" class="form">
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label for="name">Название</label>
        <input type="text" id="name" name="name" value="<?= escape(old('name')) ?>" required>
    </div>
    <div class="form-group">
        <label for="sort_order">Порядок сортировки</label>
        <input type="number" id="sort_order" name="sort_order" value="<?= escape(old('sort_order', '0')) ?>">
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Создать</button>
        <a href="index.php?action=statuses" class="btn">Отмена</a>
    </div>
</form>
