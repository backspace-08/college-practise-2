<?php

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM projects WHERE id = :id');
$stmt->execute(['id' => $id]);
$project = $stmt->fetch();

if (!$project) {
    http_response_code(404);
    echo '<p>Проект не найден</p>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($name)) {
        $error = 'Название проекта обязательно';
    } else {
        $stmt = db()->prepare('UPDATE projects SET name = :name, description = :description, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
        flash('success', 'Проект обновлён');
        redirect('index.php?action=projects');
    }
}
?>

<form method="post" class="form">
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label for="name">Название</label>
        <input type="text" id="name" name="name" value="<?= escape($_POST['name'] ?? $project['name']) ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Описание</label>
        <textarea id="description" name="description" rows="5"><?= escape($_POST['description'] ?? $project['description']) ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="index.php?action=projects" class="btn">Отмена</a>
    </div>
</form>
