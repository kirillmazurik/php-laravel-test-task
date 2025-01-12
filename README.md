# How to:

1. Установить docker и docker-compose

2. Не обязательно!: Создать .env и заполнить своими данными. .env в репе хранится не должен. Тут он для упрощения установки (cp .env.example .env)

Клонируем репозиторий
git clone  git@github.com:kirillmazurik/php-task-gateway.git .

cd php-task-gateway

3. Поднять контейнер
```
docker compose up --build -d
```

4. Выполнить вход в контейнер
```
docker compose exec product-service bash
```

5. Установить зависимости
```
composer install
```

6. Накатить миграции
```
./artisan migrate --force
```
exit 

docker compose exec order-service bash
```

5. Установить зависимости
```
composer install
```

6. Накатить миграции
```
./artisan migrate --force
```
exit 

7. Обновление routes и конфига
```
./artisan optimize
```

8. Swagger доступен по адресу
```
http://localhost:8000/api/documentation
```

## По желанию выполнить в контейнере app:
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

```
php artisan l5-swagger:generate
```
