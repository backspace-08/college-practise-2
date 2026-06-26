<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($name)) {
        $error = 'Название проекта обязательно';
    } else {
        $stmt = db()->prepare('INSERT INTO projects (name, description) VALUES (:name, :description)');
        $stmt->execute(['name' => $name, 'description' => $description]);
        flash('success', 'Проект создан');
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
        <input type="text" id="name" name="name" value="<?= escape(old('name')) ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Описание</label>
        <textarea id="description" name="description" rows="5"><?= escape(old('description')) ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Создать</button>
        <a href="index.php?action=projects" class="btn">Отмена</a>
    </div>
</form>
