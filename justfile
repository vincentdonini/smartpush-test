_default:
    just --list

setup-app:
    @just dev-up
    @just composer-install
    @just load-fixtures
    @just install-node-modules

dev-up:
    docker-compose up -d --build

dev-down:
	docker compose down --remove-orphans

reload-container:
    docker compose stop
    docker compose up -d

composer-install:
    docker compose exec www composer install

install-node-modules:
    @cd front && npm install && npm start

docker-logs CONTAINER LIMIT="100":
    docker compose logs {{CONTAINER}} -f --tail {{LIMIT}}

docker-restart CONTAINER:
    docker compose restart {{CONTAINER}}

encore-dev-watch:
    @cd front && npm start

reset-node-modules:
    @cd front && rm -rf node_modules
    @just install-node-modules

clear-cache:
    docker compose exec www bash -c "php bin/console cache:clear --verbose"

db-restart:
    -@just db-drop
    @just db-create
    @just db-schema-update

db-drop:
    docker compose exec www bash -c 'php bin/console doctrine:database:drop --force --verbose --no-debug'

db-create:
    docker compose exec www bash -c 'php bin/console doctrine:database:create --verbose --no-debug'

db-schema-diff:
    docker compose exec www bash -c 'php bin/console doctrine:schema:update --dump-sql'

db-schema-update:
    docker compose exec www bash -c 'php bin/console doctrine:schema:update --force --complete'

reload-local-fixtures:
	@just db-restart
	@just load-fixtures

load-fixtures:
    docker compose exec www bash -c "php bin/console doctrine:fixtures:load --no-interaction"

shell-backend:
    docker compose exec www bash
