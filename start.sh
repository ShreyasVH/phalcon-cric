vendor/bin/phalcon-migrations run --config=app/config/migrations.php
php -S "0.0.0.0:$PORT" -t public;