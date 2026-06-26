<?php

function renderView(string $view, array $data = []): void
{
    extract($data);

    $path = __DIR__ . '/../views/' . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';

    if (!file_exists($path)) {
        if (APP_DEBUG) {
            echo "View not found: " . htmlspecialchars($path);
        } else {
            http_response_code(500);
            echo 'Internal Server Error';
        }
        return;
    }

    require $path;
}

function renderLayout(string $layout, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../views/layouts/' . $layout . '.php';
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $default;
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }

    $val = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $val;
}

function hasFlash(string $key): bool
{
    return !empty($_SESSION['flash'][$key]);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function selected(?string $a, string $b): string
{
    return $a === $b ? 'selected' : '';
}

function checked(bool $condition): string
{
    return $condition ? 'checked' : '';
}
