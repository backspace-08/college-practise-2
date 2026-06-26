<?php

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
$cat = $stmt->fetch();

if (!$cat) {
    http_response_code(404);
    echo '<p>Категория не найдена</p>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    if (empty($name)) {
        $error = 'Название обязательно';
    } else {
        $stmt = db()->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
        flash('success', 'Категория обновлена');
        redirect('index.php?action=categories');
    }
}
?>

<form method="post" class="form">
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label for="name">Название</label>
        <input type="text" id="name" name="name" value="<?= escape($_POST['name'] ?? $cat['name']) ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Описание</label>
        <textarea id="description" name="description" rows="3"><?= escape($_POST['description'] ?? $cat['description']) ?></textarea>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="index.php?action=categories" class="btn">Отмена</a>
    </div>
</form>
