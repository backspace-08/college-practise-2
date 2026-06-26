<div align="center">

# Task Manager

**Учебное веб-приложение для управления проектами и задачами**

[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)](https://docker.com)
[![Nginx](https://img.shields.io/badge/Nginx-009639?logo=nginx&logoColor=white)](https://nginx.org)

**Разработка, администрирование и защита баз данных**

</div>

## О проекте

Веб-приложение для управления проектами и задачами с разграничением прав доступа. Разработано в рамках производственной практики по профессиональному модулю 11.01 «Разработка, администрирование и защита баз данных».

Реализовано:
- **8 таблиц** в PostgreSQL с внешними ключами, индексами и каскадными операциями
- **Ролевая модель**: администратор (полный CRUD) и зритель (только просмотр)
- **CRUD** для проектов, задач, категорий, приоритетов и статусов
- **6 аналитических отчётов** с JOIN и агрегатными функциями
- **Фильтрация** задач по проекту, статусу и исполнителю

## Стек

| Компонент | Технология |
|-----------|-----------|
| **Backend** | PHP 8.4 (чистый, без фреймворка) |
| **Database** | PostgreSQL 16 |
| **Web server** | Nginx |
| **Infra** | Docker, docker-compose |
| **Frontend** | HTML, CSS, vanilla JS |

## Быстрый старт

```bash
cd app
cp .env.example .env
docker compose up -d --build
docker compose exec app php install.php
```

Приложение: http://localhost:8090

**Учётные данные:**
| Логин | Пароль | Роль |
|-------|--------|------|
| `admin` | `password` | Полный доступ |
| `viewer` | `password` | Только просмотр |

## Структура БД

```
users ──┐
         ├── tasks ──┬── projects
         │           ├── task_statuses
         │           ├── priorities
         │           └── categories
         └── comments
```

Все связи — с внешними ключами, каскадным удалением и изменением.

## Запросы в отчёт

В `sql/003_queries.sql` — 10 подготовленных запросов: количество по статусам, просроченные задачи, среднее время выполнения, завершённые за неделю и др.

## Структура проекта

```
├── docker/         # Nginx + PHP-FPM конфиги
├── sql/            # DDL, seed, коллекция запросов
├── src/            # PHP: конфиг, PDO, авторизация, helpers
├── public/         # Корень веб-сервера
├── views/          # HTML-шаблоны (layouts, CRUD, отчёты)
├── install.php     # CLI-установщик БД
├── docker-compose.yml
└── .env
```
