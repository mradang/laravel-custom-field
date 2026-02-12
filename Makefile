path:=$(shell pwd)
name:=$(notdir $(shell pwd))
user:=1000:1000
dir:=-v $(path):/var/www/html -v /home/yf/.composer:/composer

build:
	docker build -t $(name) .

shell:
	docker run -it --rm $(dir) -u $(user) $(name) sh -c "/bin/bash"

test:
	docker run -it --rm $(dir) -u $(user) $(name) sh -c "/var/www/html/vendor/bin/phpunit --colors=always --display-phpunit-deprecations"

pint:
ifndef FILE
	$(error FILE is required, use: make pint FILE=path/to/file)
endif
	docker run --rm $(dir) -u $(user) $(name) sh -c "/var/www/html/vendor/bin/pint /var/www/html/$(FILE)"
