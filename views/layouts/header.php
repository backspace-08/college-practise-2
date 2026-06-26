<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($title ?? 'Task Manager') ?> — Task Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <a href="index.php?action=dashboard">Task Manager</a>
        </div>
        <?php if (isAuthenticated()): ?>
        <ul class="nav-links">
            <li><a href="index.php?action=dashboard">Главная</a></li>
            <li><a href="index.php?action=projects">Проекты</a></li>
            <li><a href="index.php?action=tasks">Задачи</a></li>
            <li><a href="index.php?action=categories">Категории</a></li>
            <li class="dropdown">
                <a href="#">Отчёты ▾</a>
                <ul class="dropdown-menu">
                    <li><a href="index.php?action=report_by_status">По статусам</a></li>
                    <li><a href="index.php?action=report_by_priority">По приоритетам</a></li>
                    <li><a href="index.php?action=report_by_category">По категориям</a></li>
                    <li><a href="index.php?action=report_by_user">По пользователю</a></li>
                    <li><a href="index.php?action=report_overdue">Просроченные</a></li>
                    <li><a href="index.php?action=report_completed_weekly">Завершённые за неделю</a></li>
                </ul>
            </li>
            <?php if (isAdmin()): ?>
            <li><a href="index.php?action=users">Пользователи</a></li>
            <?php endif; ?>
            <li class="nav-right">
                <span class="user-info"><?= escape($_SESSION['username'] ?? '') ?></span>
                <a href="index.php?action=logout" class="btn-logout">Выйти</a>
            </li>
        </ul>
        <?php endif; ?>
    </div>
</nav>

<main class="container">
    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success"><?= escape(flash('success')) ?></div>
    <?php elseif (hasFlash('error')): ?>
        <div class="alert alert-error"><?= escape(flash('error')) ?></div>
    <?php endif; ?>

    <h1><?= escape($title ?? '') ?></h1>
