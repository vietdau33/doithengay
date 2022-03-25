run(){
    docker exec -it $(docker ps -aqf "name=app") sh -c $1
}
setup(){
    run "cd /app && composer install"
    run "cd /app && npm install"
    run "cd /app && npm run dev"
    run "cd /app && php -r \"!file_exists('.env') && copy('.env.example', '.env');\""
    run "cd /app && php artisan key:generate"
}
art(){
    run "php artisan $1"
}
make(){
    run "art make:$1 $2"
}
migrate(){
    run "art migrate"
}
mix(){
    run "npm run production"
}
watch(){
    run "npm run watch"
}
