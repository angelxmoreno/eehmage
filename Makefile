include .env
# Vars
CONTAINER_PREFIX = "${APP_PREFIX}"

help: ## Help menu
	@echo "App Tasks"
	@cat $(MAKEFILE_LIST) | pcregrep -o -e "^([\w]*):\s?##(.*)"
	@echo

ssh: ## connect to fpm container
	docker exec -it $(CONTAINER_PREFIX)-fpm ash

start: ## starts docker compose
	docker-compose -f docker-compose.yml up

restart: ## starts docker compose
	docker-compose restart

stop: ## stops all containers
	docker-compose stop
