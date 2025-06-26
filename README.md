# Установка и запуск проекта в Docker
После запуска:
- Сайт доступен: http://localhost

Следуйте этим шагам для локального развертывания проекта.

1. Запустите Docker-контейнеры:
   ```bash
   docker-compose up -d
2. Установите зависимости composer:
   ```bash
   composer install
3. Создание файла .env на основе .env.example:
   ```bash
   cp .env.example .env
4. Настройте .env:
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=excdev-mysql
    DB_PORT=3306
    DB_DATABASE=excdev_db
    DB_USERNAME=root
    DB_PASSWORD=root
5. Войдите в контейнер excdev-php:
   ```bash
   docker exec -it excdev-php bash
6. Внутри контейнера запустите миграцию:
   ```bash
   php artisan migrate

## Команды для фронта
Эти команды выполняются вне контейнера, в корне проекта.
1. Установить npm:
   ```bash
   npm install
2. Запустите vite:
   ```bash
   vite

## Очереди и команды
1. Откройте отдельную консоль и запустите воркер очередей:
   ```bash
   docker exec -it excdev-php php artisan queue:work

## Создание пользователя и операция
Все команды ниже выполняются внутри контейнера excdev-php
1. Создание пользователя:
   ```bash
   php artisan make:user --login=Admin --name=Admin --email=admin@mail.ru  --password=12345678
    
2. Создание операции:
   ```bash
   php artisan balance:operate --login=Admin --amount=1234 --type=credit --description=Salary
   
## Тестирование
1. Создайте .env.testing на основе .env.example:
   ```bash
   cp .env.example .env.testing
2. Настройте .env.testing:
   ```bash
    DB_CONNECTION=sqlite
    DB_DATABASE=:memory:
3. Внутри контейнера запустите тесты:
   ```bash
   docker exec -it excdev-php php artisan test
