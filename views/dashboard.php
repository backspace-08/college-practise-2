<?php

$stmtProjects = db()->query('SELECT COUNT(*) AS count FROM projects');
$projectCount = $stmtProjects->fetch()['count'];

$stmtTasks = db()->query('SELECT COUNT(*) AS count FROM tasks');
$taskCount = $stmtTasks->fetch()['count'];

$stmtCompleted = db()->query("SELECT COUNT(*) AS count FROM tasks WHERE completed_at IS NOT NULL");
$completedCount = $stmtCompleted->fetch()['count'];

$stmtOverdue = db()->query("SELECT COUNT(*) AS count FROM tasks WHERE due_date < CURRENT_DATE AND completed_at IS NULL");
$overdueCount = $stmtOverdue->fetch()['count'];

$stmtRecent = db()->query("SELECT t.title, t.created_at, p.name AS project, ts.name AS status FROM tasks t JOIN projects p ON p.id = t.project_id JOIN task_statuses ts ON ts.id = t.status_id ORDER BY t.created_at DESC LIMIT 5");
$recentTasks = $stmtRecent->fetchAll();
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $projectCount ?></div>
        <div class="stat-label">Проектов</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $taskCount ?></div>
        <div class="stat-label">Всего задач</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $completedCount ?></div>
        <div class="stat-label">Выполнено</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $overdueCount ?></div>
        <div class="stat-label">Просрочено</div>
    </div>
</div>

<div class="quick-actions">
    <h2>Быстрые действия</h2>
    <div class="actions-grid">
        <?php if (isAdmin()): ?>
        <a href="index.php?action=project_create" class="action-card">+ Новый проект</a>
        <a href="index.php?action=task_create" class="action-card">+ Новая задача</a>
        <?php endif; ?>
        <a href="index.php?action=projects" class="action-card">Список проектов</a>
        <a href="index.php?action=tasks" class="action-card">Список задач</a>
        <a href="index.php?action=report_overdue" class="action-card action-warn">Просроченные задачи</a>
    </div>
</div>

<div class="section">
    <h2>Последние задачи</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Проект</th>
                <th>Статус</th>
                <th>Создана</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentTasks as $task): ?>
            <tr>
                <td><?= escape($task['title']) ?></td>
                <td><?= escape($task['project']) ?></td>
                <td><span class="badge"><?= escape($task['status']) ?></span></td>
                <td><?= escape($task['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
