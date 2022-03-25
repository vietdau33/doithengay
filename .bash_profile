run(){
    docker exec -it 7622a418f975 sh -c $1
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
