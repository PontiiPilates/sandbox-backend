## Авторизация в Telegram

 - `api/v1/parsing/tg-auth` - возвращает Qr-код если пользователь не авторизован | информацию об авторизованном пользователе

## Экстракция контента из Telegram

```shell
docker compose exec app php artisan parsing:tg-events-extract 10
```
 - 10 - не сохранять посты старше 10 дней
 - -R - очистить таблицу экстракций