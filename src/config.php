<?php

$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    $envPath = __DIR__ . '/../.env.example';
}

$env = parse_ini_file($envPath);

define('DB_HOST', $env['DB_HOST'] ?? 'pgsql');
define('DB_PORT', $env['DB_PORT'] ?? '5432');
define('DB_NAME', $env['DB_DATABASE'] ?? 'college_practise_2');
define('DB_USER', $env['DB_USERNAME'] ?? 'sail');
define('DB_PASS', $env['DB_PASSWORD'] ?? 'password');

define('APP_NAME', $env['APP_NAME'] ?? 'Task Manager');
define('APP_DEBUG', ($env['APP_DEBUG'] ?? 'true') === 'true');
