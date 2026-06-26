-- ============================================================
-- Коллекция запросов для приложения и отчёта
-- ============================================================

-- 1. Количество задач по статусам
SELECT ts.name AS status, COUNT(t.id) AS task_count
FROM task_statuses ts
LEFT JOIN tasks t ON t.status_id = ts.id
GROUP BY ts.id, ts.name
ORDER BY ts.sort_order;

-- 2. Задачи по приоритетам
SELECT p.name AS priority, COUNT(t.id) AS task_count
FROM priorities p
LEFT JOIN tasks t ON t.priority_id = p.id
GROUP BY p.id, p.name
ORDER BY p.sort_order;

-- 3. Задачи по категориям
SELECT c.name AS category, COUNT(t.id) AS task_count
FROM categories c
LEFT JOIN tasks t ON t.category_id = c.id
GROUP BY c.id, c.name
ORDER BY c.name;

-- 4. Задачи конкретного пользователя
SELECT t.title, p.name AS project, ts.name AS status, t.due_date
FROM tasks t
JOIN projects p ON p.id = t.project_id
JOIN task_statuses ts ON ts.id = t.status_id
WHERE t.user_id = 1
ORDER BY t.due_date;

-- 5. Просроченные задачи (срок прошёл, задача не выполнена)
SELECT t.title, p.name AS project, u.username, t.due_date
FROM tasks t
JOIN projects p ON p.id = t.project_id
JOIN users u ON u.id = t.user_id
WHERE t.due_date < CURRENT_DATE AND t.completed_at IS NULL
ORDER BY t.due_date;

-- 6. Среднее время выполнения задачи (в днях)
SELECT AVG(EXTRACT(DAY FROM (completed_at - created_at))) AS avg_completion_days
FROM tasks
WHERE completed_at IS NOT NULL;

-- 7. Проекты с количеством задач
SELECT p.name AS project, COUNT(t.id) AS task_count
FROM projects p
LEFT JOIN tasks t ON t.project_id = p.id
GROUP BY p.id, p.name
ORDER BY task_count DESC;

-- 8. Задачи, завершённые за последнюю неделю
SELECT t.title, p.name AS project, u.username, t.completed_at
FROM tasks t
JOIN projects p ON p.id = t.project_id
JOIN users u ON u.id = t.user_id
WHERE t.completed_at >= CURRENT_DATE - INTERVAL '7 days'
ORDER BY t.completed_at DESC;

-- 9. Количество комментариев к каждой задаче
SELECT t.title, COUNT(c.id) AS comment_count
FROM tasks t
LEFT JOIN comments c ON c.task_id = t.id
GROUP BY t.id, t.title
ORDER BY comment_count DESC;

-- 10. Список задач (полный) с связанными данными
SELECT
    t.id,
    t.title,
    t.due_date,
    t.completed_at,
    u.username AS assignee,
    p.name AS project,
    ts.name AS status,
    pr.name AS priority,
    c.name AS category
FROM tasks t
JOIN users u ON u.id = t.user_id
JOIN projects p ON p.id = t.project_id
JOIN task_statuses ts ON ts.id = t.status_id
JOIN priorities pr ON pr.id = t.priority_id
LEFT JOIN categories c ON c.id = t.category_id
ORDER BY t.created_at DESC;
