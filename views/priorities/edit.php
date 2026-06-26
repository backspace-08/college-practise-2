<?php

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM priorities WHERE id = :id');
$stmt->execute(['id' => $id]);
$priority = $stmt->fetch();

if (!$priority) {
    http_response_code(404);
    echo '<p>Приоритет не найден</p>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    if (empty($name)) {
        $error = 'Название обязательно';
    } else {
        $stmt = db()->prepare('UPDATE priorities SET name = :name, sort_order = :sort_order WHERE id = :id');
        $stmt->execute(['name' => $name, 'sort_order' => $sortOrder, 'id' => $id]);
        flash('success', 'Приоритет обновлён');
        redirect('index.php?action=priorities');
    }
}
?>

<form method="post" class="form">
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label for="name">Название</label>
        <input type="text" id="name" name="name" value="<?= escape($_POST['name'] ?? $priority['name']) ?>" required>
    </div>
    <div class="form-group">
        <label for="sort_order">Порядок сортировки</label>
        <input type="number" id="sort_order" name="sort_order" value="<?= escape($_POST['sort_order'] ?? $priority['sort_order']) ?>">
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="index.php?action=priorities" class="btn">Отмена</a>
    </div>
</form>
