<?php

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM tasks WHERE id = :id');
$stmt->execute(['id' => $id]);
$task = $stmt->fetch();

if (!$task) {
    http_response_code(404);
    echo '<p>Задача не найдена</p>';
    return;
}

$projects = db()->query('SELECT * FROM projects ORDER BY name')->fetchAll();
$users = db()->query('SELECT * FROM users ORDER BY username')->fetchAll();
$statuses = db()->query('SELECT * FROM task_statuses ORDER BY sort_order')->fetchAll();
$priorities = db()->query('SELECT * FROM priorities ORDER BY sort_order')->fetchAll();
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $dueDate = $_POST['due_date'] ?: null;
    $completedAt = $_POST['completed_at'] ? ($_POST['completed_at'] . ' ' . date('H:i:s')) : null;
    $projectId = (int) ($_POST['project_id'] ?? 0);
    $userId = (int) ($_POST['user_id'] ?? 0);
    $statusId = (int) ($_POST['status_id'] ?? 0);
    $priorityId = (int) ($_POST['priority_id'] ?? 0);
    $categoryId = $_POST['category_id'] ? (int) $_POST['category_id'] : null;

    if (empty($title) || !$projectId || !$userId || !$statusId || !$priorityId) {
        $error = 'Заполните обязательные поля';
    } else {
        $stmt = db()->prepare('UPDATE tasks SET title = :title, description = :description, due_date = :due_date, completed_at = :completed_at, user_id = :user_id, project_id = :project_id, status_id = :status_id, priority_id = :priority_id, category_id = :category_id, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'completed_at' => $completedAt,
            'user_id' => $userId,
            'project_id' => $projectId,
            'status_id' => $statusId,
            'priority_id' => $priorityId,
            'category_id' => $categoryId,
            'id' => $id,
        ]);
        flash('success', 'Задача обновлена');
        redirect('index.php?action=tasks');
    }
}
?>

<form method="post" class="form">
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label for="title">Название *</label>
        <input type="text" id="title" name="title" value="<?= escape($_POST['title'] ?? $task['title']) ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Описание</label>
        <textarea id="description" name="description" rows="4"><?= escape($_POST['description'] ?? $task['description']) ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="project_id">Проект *</label>
            <select id="project_id" name="project_id" required>
                <option value="">Выберите проект</option>
                <?php foreach ($projects as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= selected($_POST['project_id'] ?? $task['project_id'], $p['id']) ?>><?= escape($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="user_id">Исполнитель *</label>
            <select id="user_id" name="user_id" required>
                <option value="">Выберите пользователя</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= selected($_POST['user_id'] ?? $task['user_id'], $u['id']) ?>><?= escape($u['username']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="status_id">Статус *</label>
            <select id="status_id" name="status_id" required>
                <option value="">Выберите статус</option>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= selected($_POST['status_id'] ?? $task['status_id'], $s['id']) ?>><?= escape($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="priority_id">Приоритет *</label>
            <select id="priority_id" name="priority_id" required>
                <option value="">Выберите приоритет</option>
                <?php foreach ($priorities as $pr): ?>
                    <option value="<?= $pr['id'] ?>" <?= selected($_POST['priority_id'] ?? $task['priority_id'], $pr['id']) ?>><?= escape($pr['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="category_id">Категория</label>
            <select id="category_id" name="category_id">
                <option value="">Без категории</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= selected($_POST['category_id'] ?? $task['category_id'], $c['id']) ?>><?= escape($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="due_date">Срок выполнения</label>
            <input type="date" id="due_date" name="due_date" value="<?= escape($_POST['due_date'] ?? $task['due_date']) ?>">
        </div>
        <div class="form-group">
            <label for="completed_at">Дата завершения</label>
            <input type="date" id="completed_at" name="completed_at" value="<?= escape($_POST['completed_at'] ?? ($task['completed_at'] ? date('Y-m-d', strtotime($task['completed_at'])) : '')) ?>">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="index.php?action=tasks" class="btn">Отмена</a>
    </div>
</form>
