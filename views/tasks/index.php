<?php

$filterProject = $_GET['project_id'] ?? '';
$filterStatus = $_GET['status_id'] ?? '';
$filterUser = $_GET['user_id'] ?? '';

$sql = "SELECT t.*, u.username, p.name AS project_name, ts.name AS status_name, pr.name AS priority_name, c.name AS category_name
        FROM tasks t
        JOIN users u ON u.id = t.user_id
        JOIN projects p ON p.id = t.project_id
        JOIN task_statuses ts ON ts.id = t.status_id
        JOIN priorities pr ON pr.id = t.priority_id
        LEFT JOIN categories c ON c.id = t.category_id
        WHERE 1=1";

$params = [];

if ($filterProject !== '') {
    $sql .= ' AND t.project_id = :project_id';
    $params['project_id'] = (int) $filterProject;
}
if ($filterStatus !== '') {
    $sql .= ' AND t.status_id = :status_id';
    $params['status_id'] = (int) $filterStatus;
}
if ($filterUser !== '') {
    $sql .= ' AND t.user_id = :user_id';
    $params['user_id'] = (int) $filterUser;
}

$sql .= ' ORDER BY t.created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

$projects = db()->query('SELECT * FROM projects ORDER BY name')->fetchAll();
$statuses = db()->query('SELECT * FROM task_statuses ORDER BY sort_order')->fetchAll();
$users = db()->query('SELECT * FROM users ORDER BY username')->fetchAll();
?>

<?php if (isAdmin()): ?>
<div class="toolbar">
    <a href="index.php?action=task_create" class="btn btn-primary">+ Создать задачу</a>
</div>
<?php endif; ?>

<form method="get" class="filter-form">
    <input type="hidden" name="action" value="tasks">
    <div class="filter-row">
        <select name="project_id">
            <option value="">Все проекты</option>
            <?php foreach ($projects as $p): ?>
                <option value="<?= $p['id'] ?>" <?= selected($filterProject, (string)$p['id']) ?>><?= escape($p['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="status_id">
            <option value="">Все статусы</option>
            <?php foreach ($statuses as $s): ?>
                <option value="<?= $s['id'] ?>" <?= selected($filterStatus, (string)$s['id']) ?>><?= escape($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="user_id">
            <option value="">Все пользователи</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>" <?= selected($filterUser, (string)$u['id']) ?>><?= escape($u['username']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-sm">Фильтр</button>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Проект</th>
            <th>Исполнитель</th>
            <th>Приоритет</th>
            <th>Статус</th>
            <th>Категория</th>
            <th>Срок</th>
            <?php if (isAdmin()): ?>
            <th>Действия</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
        <tr class="<?= $task['completed_at'] ? 'row-completed' : ($task['due_date'] && $task['due_date'] < date('Y-m-d') ? 'row-overdue' : '') ?>">
            <td><?= $task['id'] ?></td>
            <td><?= escape($task['title']) ?></td>
            <td><?= escape($task['project_name']) ?></td>
            <td><?= escape($task['username']) ?></td>
            <td><span class="badge priority-<?= $task['priority_id'] ?>"><?= escape($task['priority_name']) ?></span></td>
            <td><span class="badge"><?= escape($task['status_name']) ?></span></td>
            <td><?= escape($task['category_name'] ?? '-') ?></td>
            <td><?= $task['due_date'] ? escape($task['due_date']) : '-' ?></td>
            <?php if (isAdmin()): ?>
            <td class="actions">
                <a href="index.php?action=task_edit&id=<?= $task['id'] ?>" class="btn-sm">✏️</a>
                <a href="index.php?action=task_delete&id=<?= $task['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Удалить задачу?')">🗑️</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
