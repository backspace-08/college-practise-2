<?php

require_once __DIR__ . '/db.php';

function login(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = (int) $user['role_id'];

        $roleStmt = db()->prepare('SELECT name FROM roles WHERE id = :id');
        $roleStmt->execute(['id' => $user['role_id']]);
        $role = $roleStmt->fetch();
        $_SESSION['role_name'] = $role['name'] ?? '';

        return true;
    }

    return false;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}

function isAuthenticated(): bool
{
    return !empty($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return ($_SESSION['role_name'] ?? '') === 'admin';
}

function requireAuth(): void
{
    if (!isAuthenticated()) {
        header('Location: index.php?action=login');
        exit;
    }
}

function requireAdmin(): void
{
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        renderView('errors/403');
        exit;
    }
}

function currentUserId(): int
{
    return $_SESSION['user_id'] ?? 0;
}
