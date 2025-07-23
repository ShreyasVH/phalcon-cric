if ! lsof -i :$PORT > /dev/null; then
    echo "Starting"
    vendor/bin/phalcon-migrations run --config=app/config/migrations.php > migrations.log 2>&1
    php-fpm --fpm-config php-fpm.conf
fi