<div align="center">

<h1>Task Manager</h1>

Учебное веб-приложение для управления проектами и задачами

[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![Nginx](https://img.shields.io/badge/Nginx-009639?logo=nginx&logoColor=white)](https://nginx.org)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://docker.com)

</div>

## О проекте

Task Manager — веб-приложение для управления проектами и задачами с разграничением прав доступа. Сделано в рамках производственной практики по профессиональному модулю 11.01 «Разработка, администрирование и защита баз данных». Бэкенд написан на PHP без фреймворка, фронтенд — на HTML, CSS и vanilla JS.

## Возможности

В приложении две роли: администратор может создавать, менять и удалять всё, зритель только просматривает. Управлять можно проектами, задачами, категориями, приоритетами и статусами, а задачи фильтруются по проекту, статусу и исполнителю.

## База данных

Схема связывает пользователей, проекты, задачи, комментарии и справочники статусов, приоритетов и категорий. Все связи сделаны внешними ключами, удаление и обновление каскадные.

В приложении шесть аналитических отчётов с JOIN и агрегатными функциями. Подготовленные запросы собраны в [`sql/003_queries.sql`](sql/003_queries.sql): их десять, среди них количество задач по статусам, просроченные задачи, среднее время выполнения и завершённые за неделю.

## Запуск

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php install.php
```

Последняя команда запускает установщик базы. Приложение открывается на `http://localhost:8090`. Тестовых пользователей двое: `admin` с полным доступом и `viewer` только для просмотра, пароль у обоих — `password`.

## Структура проекта

```text
├── docker/         # конфиги Nginx и PHP-FPM
├── sql/            # DDL, начальные данные, коллекция запросов
├── src/            # PHP: конфиг, PDO, авторизация, helpers
├── public/         # корень веб-сервера
├── views/          # HTML-шаблоны: layouts, CRUD, отчёты
├── install.php     # CLI-установщик БД
├── docker-compose.yml
└── .env.example
```

---

<div align="center">

[backspace-08](https://github.com/backspace-08)

</div>
