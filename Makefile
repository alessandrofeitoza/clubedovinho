.PHONY: up install_dependencies generate_proxies migrate_database load_fixtures install_frontend compile_frontend generate_keys

up:
	docker compose up -d

down:
	docker compose down

container_php:
	docker compose exec -it php bash

container_mysql:
	docker compose exec -it mysql bash

composer_install:
	docker compose exec -T php bash -c "composer install"

db_migrate:
	docker compose exec -T php bash -c "php bin/console doctrine:migrations:migrate -n"

db_fixtures:
	docker compose exec -T php bash -c "php bin/console doctrine:fixtures:load -n --append"

tests: load_fixtures
	docker compose exec -T php bash -c "php bin/phpunit"

cache_clear:
	docker compose exec -T php bash -c "php bin/console cache:clear"

reset:
	rm -rf var/
	docker compose exec -T php bash -c "php bin/console cache:clear"
	docker compose exec -T php bash -c "php bin/console d:d:d -f"
	docker compose exec -T php bash -c "php bin/console d:d:c"
	docker compose exec -T php bash -c "php bin/console doctrine:migrations:migrate -n"

code_style:
	docker compose exec -T php bash -c "php bin/console app:code-style"

generate_keys:
	docker compose exec -T php bash -c "php bin/console lexik:jwt:generate-keypair --overwrite"

# Comando iniciar o projeto do zero
setup: up install_dependencies generate_proxies migrate_database load_fixtures install_frontend compile_frontend generate_keys
