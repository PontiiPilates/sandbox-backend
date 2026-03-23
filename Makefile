rebuild:
	sudo docker compose down --volumes --rmi all
	sudo rm -R ./docker/mysql/data/*
	sudo docker compose up -d --build

mv:
	mv ./storage/app/private/parsing/telegram/deleted/* storage/app/private/parsing/telegram/results

refresh:
	sudo docker compose exec app php artisan db:wipe
	sudo docker compose exec app php artisan migrate:refresh --seed
	sudo docker compose exec app php artisan parsing:update-events


# Получение изображений для событий

# Добавление промптов для генерации изображений
# sudo docker compose exec app php artisan illustrate:add-prompt

# Генерация и получение изображений на основе промптов
# sudo docker compose exec app php artisan illustrate:create-preview





