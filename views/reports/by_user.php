<p class="report-description">Задачи, назначенные на выбранного пользователя.</p>

<?php
$users = db()->query('SELECT * FROM users ORDER BY username')->fetchAll();

$selectedUserId = (int) ($_GET['user_id'] ?? currentUserId());

$stmt = db()->prepare('SELECT t.title, p.name AS project, ts.name AS status, t.due_date, t.completed_at FROM tasks t JOIN projects p ON p.id = t.project_id JOIN task_statuses ts ON ts.id = t.status_id WHERE t.user_id = :user_id ORDER BY t.created_at DESC');
$stmt->execute(['user_id' => $selectedUserId]);
$rows = $stmt->fetchAll();
?>

<form method="get" class="filter-form">
    <input type="hidden" name="action" value="report_by_user">
    <div class="filter-row">
        <select name="user_id">
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>" <?= selected($selectedUserId, $u['id']) ?>><?= escape($u['username']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-sm">Показать</button>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Задача</th>
            <th>Проект</th>
            <th>Статус</th>
            <th>Срок</th>
            <th>Завершена</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= escape($row['title']) ?></td>
            <td><?= escape($row['project']) ?></td>
            <td><?= escape($row['status']) ?></td>
            <td><?= $row['due_date'] ? escape($row['due_date']) : '-' ?></td>
            <td><?= $row['completed_at'] ? escape($row['completed_at']) : 'Нет' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
