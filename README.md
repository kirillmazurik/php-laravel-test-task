# How to:

1. Установить docker и docker-compose

2. Не обязательно!: Создать .env и заполнить своими данными. .env в репе хранится не должен. Тут он для упрощения установки

3. Клонируем репозиторий
```
git clone git@github.com:kirillmazurik/php-laravel-test-task.git
```

4. Заходим в папку с проектом
```
cd php-laravel-test-task
```

5. Поднять контейнер
```
docker compose up --build -d
```

6. Выполнить вход в контейнер
```
docker compose exec product-service bash
```

7. Установить зависимости
```
composer install
```

8. Накатить миграции
```
php artisan migrate --force
```

9. Выход
```
exit 
```

10. Выполнить вход в контейнер
```
docker compose exec order-service bash
```

11. Установить зависимости
```
composer install
```

12. Накатить миграции
```
php artisan migrate --force
```

13. Выход
```
exit 
```

# Swagger
Swagger доступен по адресам
```
http://localhost:8020/products/api/documentation
```
```
http://localhost:8020/orders/api/documentation
```

## По желанию выполнить в контейнерах:
### Форматирование кода:
```
vendor/bin/php-cs-fixer fix app
```
```
vendor/bin/php-cs-fixer fix tests
```

### Статический анализ кода:
```
vendor/bin/phpstan analyse app --memory-limit=512M --level=9
```
```
vendor/bin/phpstan analyse tests --level=9
```

### Автотесты:
```
vendor/bin/phpunit tests/
```

### Перегенерировать swagger
```
php artisan l5-swagger:generate
```
