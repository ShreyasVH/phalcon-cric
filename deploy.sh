if ! lsof -i :$PORT > /dev/null; then
    echo "Starting"
    vendor/bin/phalcon-migrations run --config=app/config/migrations.php > migrations.log 2>&1
    php -S "0.0.0.0:$PORT" -t public > server.log 2>&1 &
fi