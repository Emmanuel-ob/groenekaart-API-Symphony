# Strips the make target from the input args
API_CONTAINER = 'template_api'
MYSQL_CONTAINER = 'template_mysql'
API_EXEC = docker exec -it $(API_CONTAINER) sh -c
MYSQL_EXEC = docker exec -it $(MYSQL_CONTAINER) sh -c
DATABASES = api_template
ARGS= ''

-include $(PWD)/Makefile.local

.PHONY: go
go:
	docker exec -it $(API_CONTAINER) bash

.PHONY: composer
composer:
	$(API_EXEC) 'composer $(ARGS)'

.PHONY: console
console:
	$(API_EXEC) 'bin/console $(ARGS)'

.PHONY: up-local
up-local: ## Start the docker hub in non-detached mode, so as to see logs and enable debugging, meant for local development
	docker compose up --build --force-recreate --remove-orphans
	$(API_EXEC) 'composer install'

.PHONY: up
up: ## Start the docker hub in detached mode
	docker compose up --build --force-recreate --remove-orphans -d
	$(API_EXEC) 'composer install'

.PHONY: stop
stop:
	docker compose stop

.PHONY: restart
restart:
	docker compose restart

.PHONY: down
down:
	docker compose down

.PHONY: pull
pull:
	docker compose pull

.PHONY: dump-sql
dump-sql:
	$(API_EXEC) 'bin/console doctrine:schema:update --dump-sql'

.PHONY: test
test:
	$(API_EXEC) 'vendor/bin/phpstan analyse'
	$(API_EXEC) 'vendor/bin/phpcs -p -s'

.PHONY: phpcbf
phpcbf:
	$(API_EXEC) 'vendor/bin/phpcbf $(ARGS)'

.PHONY: migrate
migrate: ## Run migration imports
	docker compose up migrations

.PHONY: terminal
terminal: ## Attach to main terminal
	docker attach $(API_CONTAINER) --sig-proxy=false

.PHONY: db-export
db-export:
	${MYSQL_EXEC} "mysqldump --databases ${DATABASES} --result-file='/${MYSQL_CONTAINER}.sql' --routines --add-drop-trigger --no-tablespaces -uroot -pb9295cf7267616a53da504fd06f8bf7050f40a19efdbdefcbbc4eb3f80ba51fd"
	docker cp ${MYSQL_CONTAINER}:${MYSQL_CONTAINER}.sql ${MYSQL_CONTAINER}.sql
	${MYSQL_EXEC} "rm ${MYSQL_CONTAINER}.sql"

.PHONY: db-import
db-import:
	docker cp ${MYSQL_CONTAINER}.sql ${MYSQL_CONTAINER}:${MYSQL_CONTAINER}.sql
	${MYSQL_EXEC} "mysql -uroot -pb9295cf7267616a53da504fd06f8bf7050f40a19efdbdefcbbc4eb3f80ba51fd < '/${MYSQL_CONTAINER}.sql'"
	${MYSQL_EXEC} "rm ${MYSQL_CONTAINER}.sql"

