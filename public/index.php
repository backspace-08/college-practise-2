<?php

session_start();

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/helpers.php';

$action = $_GET['action'] ?? 'dashboard';
$action = str_replace(['../', '..', '/'], '', $action);

// ---- Public actions (no auth required) ----

if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if (login($username, $password)) {
            redirect('index.php?action=dashboard');
        }
        $error = 'Неверное имя пользователя или пароль';
    }
    renderLayout('header', ['title' => 'Вход']);
    renderView('auth/login', ['error' => $error ?? null]);
    renderLayout('footer');
    exit;
}

if ($action === 'logout') {
    logout();
    redirect('index.php?action=login');
}

// ---- Authenticated area ----

requireAuth();

$allowedActions = [
    'dashboard',
    'projects', 'project_create', 'project_edit', 'project_delete',
    'tasks', 'task_create', 'task_edit', 'task_delete',
    'categories', 'category_create', 'category_edit', 'category_delete',
    'priorities', 'priority_create', 'priority_edit', 'priority_delete',
    'statuses', 'status_create', 'status_edit', 'status_delete',
    'users',
    'report_by_status', 'report_by_priority', 'report_by_category',
    'report_by_user', 'report_overdue', 'report_completed_weekly',
];

if (!in_array($action, $allowedActions, true)) {
    $action = 'dashboard';
}

$adminOnlyActions = ['users', 'category_create', 'category_edit', 'category_delete',
    'priority_create', 'priority_edit', 'priority_delete',
    'status_create', 'status_edit', 'status_delete'];

if (in_array($action, $adminOnlyActions, true)) {
    requireAdmin();
}

$viewMap = [
    'dashboard' => 'dashboard',
    'projects' => 'projects/index',
    'project_create' => 'projects/create',
    'project_edit' => 'projects/edit',
    'project_delete' => 'projects/delete',
    'tasks' => 'tasks/index',
    'task_create' => 'tasks/create',
    'task_edit' => 'tasks/edit',
    'task_delete' => 'tasks/delete',
    'categories' => 'categories/index',
    'category_create' => 'categories/create',
    'category_edit' => 'categories/edit',
    'category_delete' => 'categories/delete',
    'priorities' => 'priorities/index',
    'priority_create' => 'priorities/create',
    'priority_edit' => 'priorities/edit',
    'priority_delete' => 'priorities/delete',
    'statuses' => 'statuses/index',
    'status_create' => 'statuses/create',
    'status_edit' => 'statuses/edit',
    'status_delete' => 'statuses/delete',
    'users' => 'users/index',
    'report_by_status' => 'reports/by_status',
    'report_by_priority' => 'reports/by_priority',
    'report_by_category' => 'reports/by_category',
    'report_by_user' => 'reports/by_user',
    'report_overdue' => 'reports/overdue',
    'report_completed_weekly' => 'reports/completed_weekly',
];

$view = $viewMap[$action] ?? 'dashboard';

// ---- Actions that always redirect (no output, no layout) ----

$redirectActions = ['project_delete', 'task_delete', 'category_delete', 'priority_delete', 'status_delete'];

if (in_array($action, $redirectActions, true)) {
    renderView($view);
    exit;
}

// ---- Form submissions (POST) — handle before layout ----

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formActions = ['project_create', 'project_edit', 'task_create', 'task_edit',
        'category_create', 'category_edit', 'priority_create', 'priority_edit',
        'status_create', 'status_edit'];

    if (in_array($action, $formActions, true)) {
        renderView($view);
        exit;
    }
}

// ---- Page rendering (GET) — with layout ----

renderLayout('header', ['title' => getPageTitle($action)]);
renderView($view, ['action' => $action]);
renderLayout('footer');

function getPageTitle(string $action): string
{
    $titles = [
        'dashboard' => 'Панель управления',
        'projects' => 'Проекты',
        'project_create' => 'Создание проекта',
        'project_edit' => 'Редактирование проекта',
        'tasks' => 'Задачи',
        'task_create' => 'Создание задачи',
        'task_edit' => 'Редактирование задачи',
        'categories' => 'Категории',
        'category_create' => 'Создание категории',
        'category_edit' => 'Редактирование категории',
        'priorities' => 'Приоритеты',
        'priority_create' => 'Создание приоритета',
        'priority_edit' => 'Редактирование приоритета',
        'statuses' => 'Статусы',
        'status_create' => 'Создание статуса',
        'status_edit' => 'Редактирование статуса',
        'users' => 'Пользователи',
        'report_by_status' => 'Задачи по статусам',
        'report_by_priority' => 'Задачи по приоритетам',
        'report_by_category' => 'Задачи по категориям',
        'report_by_user' => 'Задачи пользователя',
        'report_overdue' => 'Просроченные задачи',
        'report_completed_weekly' => 'Завершённые за неделю',
    ];
    return $titles[$action] ?? 'Task Manager';
}
