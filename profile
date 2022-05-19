exec(){
	docker-compose exec app sh
}
run(){
	docker-compose exec app sh -c "$1"
}
art(){
	run "php artisan $1"
}
make(){
	art "make:$1 $2"
}
migrate(){
    art "migrate"
}
add(){
    git add $1
}
cm(){
    git commit -m "$1"
}
push(){
    git push origin master
}
