# +-----------------------------------------+
# Блок для быстрой пересборки всего проекта |
# +-----------------------------------------+

# пересборка проекта с очисткой базы данных
rebuild:
	sudo docker compose down --volumes --rmi all
	sudo rm -R ./docker/mysql/data/*
	sudo docker compose up -d --build

# перед восстановлением базы необходимо выполнить mv

# восстановление данных
recreate:
	sudo docker compose exec app php artisan db:wipe
	sudo docker compose exec app php artisan migrate:refresh --seed

# +------------------------------------------------------+
# Блок алиасов для отдельных команд в логическом порядке |
# +------------------------------------------------------+

# полная очистка базы данных
dbw:
	sudo docker compose exec app php artisan db:wipe

# создание таблиц и необходимых данных
mrs:
	sudo docker compose exec app php artisan migrate:refresh --seed

# перемещает файлы из обработанных в еще не обработанные 
mv:
	mv ./storage/app/private/parsing/telegram/deleted/* storage/app/private/parsing/telegram/results

# выкачивает посты из тг
pex:
	sudo docker compose exec app php artisan parsing:tg-events-extract 5

# категоризирует выкаченный материал и сохраняет результат анализа в файл
pep:
	sudo docker compose exec app php artisan parsing:extraction-preparation

# добавляет мероприятия на основе созданного ai файла
pue:
	sudo docker compose exec app php artisan parsing:update-events

# бережно добавляет событиям изображения если есть иначе генерирует их
icp:
	sudo docker compose exec app php artisan illustrate:create-preview

# инициирует пайплайн
pinit:
	sudo docker compose exec app php artisan pipeline:init-event-mining

# запускает этап обновления
pdupdate:
	sudo docker compose exec app php artisan pipeline:update-details
