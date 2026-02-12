path:=$(shell pwd)
docker-compose:=docker compose -f docker/docker-compose.yml
user:=1000:1000

build:
	$(docker-compose) build

shell:
	$(docker-compose) run -it --rm -u $(user) php sh -c "/bin/bash"

test:
	$(docker-compose) run -it --rm -u $(user) php sh -c "/var/www/html/vendor/bin/phpunit --colors=always --display-phpunit-deprecations"
