ifneq (,$(wildcard ./.env))
    include .env
    export
endif

# Run this command first
init: configs-setup composer-install db-create db-migrations permissions-fix

up: docker-up
down: docker-down
restart: down up
rebuild: down docker-build
reset: rebuild up


docker-up:
	docker-compose -p $(DOCKER_PROJECT_TITLE) up -d
	@echo ***Success! Your app is ready and available at http://localhost:$(DOCKER_NGINX_PORT) and you can connect MySQL from your host machine on port $(DOCKER_MYSQL_PORT).***

docker-down:
	docker-compose -p $(DOCKER_PROJECT_TITLE) down --remove-orphans

docker-down-clear:
	docker-compose -p $(DOCKER_PROJECT_TITLE) down -v --remove-orphans

docker-pull:
	docker-compose -p $(DOCKER_PROJECT_TITLE) pull

docker-build:
	docker-compose -p $(DOCKER_PROJECT_TITLE) build

test:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm php /app/bin/phpunit

composer-install:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm sh -c "umask 002 && composer install --no-interaction"

console:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm zsh

cs:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm sh -c "php /app/vendor/bin/php-cs-fixer -v --allow-risky=yes --config=/app/.php-cs-fixer.dist.php fix /app/src/* /app/tests/*"

psalm:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm sh -c "php /app/vendor/bin/psalm*"

db-create:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm sh -c "php /app/bin/console doctrine:database:create --if-not-exists"

db-migrations:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm sh -c "php /app/bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration"

permissions-fix:
	docker-compose -p $(DOCKER_PROJECT_TITLE) run --rm php-fpm sh -c "chmod -R u+rwX,g+w,go+rX,o-w .; [ -d ./var/log ] && chmod -R 777 ./var/log; [ -d ./var/cache ] && chmod -R 777 ./var/cache; chmod -R o+rX ./public"

configs-setup:
	[ -f docker-compose.yaml ] && echo "Skip docker-compose.yaml" || cp docker-compose.yaml.dist docker-compose.yaml
	[ -f ./symfony/.env.local ] && echo "Skip .env.local" || cp ./symfony/.env ./symfony/.env.local
	[ -f ./.env ] && echo "Skip docker .env" || cp ./.env.dist ./.env
	[ -f ./symfony/phpunit.xml ] && echo "Skip phpunit.xml" || cp ./symfony/phpunit.xml.dist ./symfony/phpunit.xml

