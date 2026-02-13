path:=$(shell pwd)
user:=-u $(shell id -u):$(shell id -g)
dir:=-v $(path):/app -v /home/yf/.composer:/composer -w /app
php_image:=php:8.4
composer_image:=composer:latest

shell:
	docker run -it --rm $(dir) $(user) $(composer_image) /bin/bash

test:
	docker run -it --rm $(dir) $(user) $(php_image) php /app/vendor/bin/phpunit --colors=always --display-phpunit-deprecations

pint:
ifndef FILE
	$(error FILE is required, use: make pint FILE=path/to/file)
endif
	docker run --rm $(dir) $(user) $(php_image) php /app/vendor/bin/pint /app/$(FILE)
