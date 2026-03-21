# пересборка базы данных вместе с пересборкой контейнера
db_hard_refresh: recreate_mysql_container sleep mv_files refresh_db

# обновление базы данных
db_soft_refresh: mv_files refresh_db



# пересборка контейнера вместе с очисткой волумсов и образов
recreate_mysql_container:
	sudo docker compose down mysql --volumes --rmi all
	sudo rm -R ./docker/mysql/data/*
	sudo docker compose up -d --build mysql

# обновление базы данных
refresh_db:
	sudo docker compose exec app php artisan db:wipe
	sudo docker compose exec app php artisan migrate:refresh --seed
	sudo docker compose exec app php artisan parsing:update-events

# перемещение использованных файлов в неиспользованные
mv_files:
	mv ./storage/app/private/parsing/telegram/deleted/* storage/app/private/parsing/telegram/results

# пауза
sleep:
	sleep 3