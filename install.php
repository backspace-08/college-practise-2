<?php
/**
 * Установщик базы данных.
 * Запуск: php install.php
 * Создаёт таблицы, наполняет тестовыми данными.
 */

require_once __DIR__ . '/src/config.php';

$dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";";

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage() . "\n";
    echo "Убедитесь, что PostgreSQL запущен и .env настроен.\n";
    exit(1);
}

echo "Подключение к БД успешно.\n";

// Выполняем schema.sql
$schema = file_get_contents(__DIR__ . '/sql/001_schema.sql');
if ($schema === false) {
    echo "Ошибка чтения sql/001_schema.sql\n";
    exit(1);
}

try {
    $pdo->exec("DROP TABLE IF EXISTS comments, tasks, projects, categories, priorities, task_statuses, users, roles CASCADE");
    echo "Старые таблицы удалены.\n";
} catch (PDOException $e) {
    // игнорируем, если таблиц нет
}

try {
    $pdo->exec($schema);
    echo "Таблицы созданы.\n";
} catch (PDOException $e) {
    echo "Ошибка создания таблиц: " . $e->getMessage() . "\n";
    exit(1);
}

// Заполняем роли
$pdo->exec("INSERT INTO roles (name, description) VALUES
    ('admin', 'Полный доступ: создание, редактирование, удаление'),
    ('viewer', 'Только просмотр данных')");
echo "Роли созданы.\n";

// Создаём пользователей с правильными хешами паролей
$adminHash = password_hash('password', PASSWORD_BCRYPT);
$viewerHash = password_hash('password', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
$stmt->execute(['admin', 'admin@example.com', $adminHash, 1]);
$stmt->execute(['viewer', 'viewer@example.com', $viewerHash, 2]);
echo "Пользователи созданы (admin/password, viewer/password).\n";

// Заполняем остальные таблицы из seed.sql
$seed = file_get_contents(__DIR__ . '/sql/002_seed.sql');
if ($seed !== false) {
    // Убираем INSERT для roles и users, так как они уже созданы
    $lines = explode("\n", $seed);
    $filtered = [];
    $skip = false;
    foreach ($lines as $line) {
        if (preg_match('/INSERT INTO (roles|users)/', $line)) {
            $skip = true;
        }
        if ($skip && trim($line) === '') {
            $skip = false;
            continue;
        }
        if ($skip) continue;
        $filtered[] = $line;
    }
    $seed = implode("\n", $filtered);

    try {
        $pdo->exec($seed);
        echo "Тестовые данные загружены.\n";
    } catch (PDOException $e) {
        echo "Ошибка загрузки тестовых данных: " . $e->getMessage() . "\n";
    }
}

echo "\nУстановка завершена!\n";
echo "Вход: admin/password или viewer/password\n";
