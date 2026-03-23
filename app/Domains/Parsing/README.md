## Авторизация в Telegram

 - `api/v1/parsing/tg-auth` - возвращает Qr-код если пользователь не авторизован | информацию об авторизованном пользователе

## Экстракция контента из Telegram

```shell
docker compose exec app php artisan parsing:tg-events-extract 5
```
 - 5 - не сохранять посты старше 5 дней
 - -R - очистить таблицу экстракций

## Отправка экстрагированного контента на категоризацию

```shell
docker compose exec app php artisan parsing:extraction-preparation
```

## Обновление мероприятий категоризированными
```shell
docker compose exec app php artisan parsing:update-events
```

## Запуск планировщика
```shell
docker compose exec app php artisan schedule:work
```