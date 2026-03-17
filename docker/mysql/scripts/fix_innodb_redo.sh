#!/bin/bash

DATA_DIR="/home/backend-developer/sandbox-backend/docker/mysql/data"

echo "🔧 Проверка наличия директории #innodb_redo..."

if [ ! -d "$DATA_DIR/#innodb_redo" ]; then
    echo "❌ Директория #innodb_redo не найдена. Создание..."
    sudo mkdir -p "$DATA_DIR/#innodb_redo"
    sudo chown 999:999 "$DATA_DIR/#innodb_redo"
    sudo chmod 755 "$DATA_DIR/#innodb_redo"
    echo "✅ Директория #innodb_redo создана."
else
    echo "✅ Директория #innodb_redo уже существует."
fi


echo "🔧 Проверка владельца директории data..."
CURRENT_OWNER=$(stat -c '%U' "$DATA_DIR")
if [ "$CURRENT_OWNER" != "mysql" ] && [ "$CURRENT_OWNER" != "999" ]; then
    echo "⚠️ Владелец $CURRENT_OWNER, замена на 999:999..."
    sudo chown -R 999:999 "$DATA_DIR"
    sudo chmod -R 755 "$DATA_DIR"
    echo "✅ Права исправлены."
else
    echo "✅ Владелец корректный."
fi

echo "🎉 Готово. Можно запускать MySQL."