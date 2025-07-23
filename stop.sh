if lsof -i:$PORT > /dev/null; then
    echo "Stopping"
    kill -QUIT $(cat php-fpm.pid)
fi