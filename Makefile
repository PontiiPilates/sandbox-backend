update:
	sudo apt update

soft_fix_db_crash:
# 	todo: добавить восстановление innodb_redo

hard_fix_db_crash:
	sudo docker compose down mysql --volumes --rmi all
	mv ./storage/app/private/parsing/telegram/deleted/* storage/app/private/parsing/telegram/results
	sudo rm -R ./docker/mysql/data/*
	sudo docker compose up -d --build mysql

db_recreate:
	sudo docker compose exec app php artisan db:wipe
	sudo docker compose exec app php artisan migrate:refresh --seed
	sudo docker compose exec app php artisan parsing:update-events