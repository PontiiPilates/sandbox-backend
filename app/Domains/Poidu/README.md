# Deployment

Клонировать репозиторий

```bash
git clone https://github.com/PontiiPilates/sandbox-backend.git
```

Поднять проект в докере

```bash
docker compose up -d --build
```

```bash
docker compose exec app bash
```

```bash
composer install
```

```bash
chmod 777 storage/ bootstrap/ -R
```

```bash
exit
```

Наполнение данными

```bash
sudo docker compose exec app php artisan migrate:refresh --seed
```

```bash
sudo docker compose exec app php artisan app:update-events
```