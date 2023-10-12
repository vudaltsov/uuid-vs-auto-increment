SHELL := /bin/bash
PHP := docker-compose run -it php
ifeq (locally,$(firstword $(MAKECMDGOALS)))
	PHP :=
endif

locally:;@:
.PHONY: locally

##
## Проект
## ------

vendor: composer.json composer.lock ## Собрать vendor
	$(PHP) composer install
	$(PHP) touch vendor

var:
	mkdir -p var

up: var ## Запустить приложение
	docker-compose up --detach --remove-orphans
	$(MAKE) vendor
.PHONY: up

down: ## Остановить приложение
	docker-compose down --remove-orphans
.PHONY: down

php: ## Зайти в контейнер PHP
	$(PHP) $(if $(cmd),$(cmd),sh)
.PHONY: php

restart: down up ## Перезапустить приложение
.PHONY: restart

bench: up
	#Composer\Config::disableProcessTimeout
    #VUdaltsov\UuidVsAutoIncrement\\BenchmarkCommand
.PHONY: bench

##
## Контроль качества кода
## ----------------------

check: composer-validate composer-unused composer-normalize composer-require composer-audit lint rector psalm ## Запустить все проверки
.PHONY: check

composer-validate: ## Провалидировать composer.json и composer.lock при помощи composer validate (https://getcomposer.org/doc/03-cli.md#validate)
	$(PHP) composer validate --strict --no-check-publish
.PHONY: composer-validate

lint: var vendor ## Проверить PHP code style при помощи PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
.PHONY: lint

fixcs: var vendor ## Исправить ошибки PHP code style при помощи PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(PHP) vendor/bin/php-cs-fixer fix --diff --verbose
.PHONY: fixcs

psalm: var vendor ## Запустить полный статический анализ PHP кода при помощи Psalm (https://psalm.dev/)
	$(PHP) vendor/bin/psalm --no-diff $(file)
.PHONY: psalm

rector: var vendor ## Запустить полный анализ PHP кода при помощи Rector (https://getrector.org)
	$(PHP) vendor/bin/rector process --dry-run
.PHONY: rector

rector-fix: var vendor ## Запустить исправление PHP кода при помощи Rector (https://getrector.org)
	$(PHP) vendor/bin/rector process
.PHONY: rector-fix

composer-unused: vendor ## Обнаружить неиспользуемые зависимости Composer при помощи composer-unused (https://github.com/icanhazstring/composer-unused)
	$(PHP) vendor/bin/composer-unused
.PHONY: composer-unused

composer-require: vendor ## Обнаружить неявные зависимости от внешних пакетов при помощи ComposerRequireChecker (https://github.com/maglnet/ComposerRequireChecker)
	$(PHP) vendor/bin/composer-require-checker check
.PHONY: composer-require

composer-audit: ## Обнаружить уязвимости в зависимостях Composer при помощи composer audit (https://getcomposer.org/doc/03-cli.md#audit)
	$(PHP) composer audit
.PHONY: composer-audit

composer-normalize: vendor ## Упорядочить composer.json
	$(PHP) composer normalize --dry-run --diff
.PHONY: composer-normalize

composer-normalize-fix: vendor ## Упорядочить composer.json
	$(PHP) composer normalize --diff
.PHONY: composer-normalize-fix

# -----------------------

help:
	@awk ' \
		BEGIN {RS=""; FS="\n"} \
		function printCommand(line) { \
			split(line, command, ":.*?## "); \
        	printf "\033[32m%-28s\033[0m %s\n", command[1], command[2]; \
        } \
		/^[0-9a-zA-Z_-]+: [0-9a-zA-Z_-]+\n[0-9a-zA-Z_-]+: .*?##.*$$/ { \
			split($$1, alias, ": "); \
			sub(alias[2] ":", alias[2] " (" alias[1] "):", $$2); \
			printCommand($$2); \
			next; \
		} \
		$$1 ~ /^[0-9a-zA-Z_-]+: .*?##/ { \
			printCommand($$1); \
			next; \
		} \
		/^##(\n##.*)+$$/ { \
			gsub("## ?", "\033[33m", $$0); \
			print $$0; \
			next; \
		} \
	' $(MAKEFILE_LIST) && printf "\033[0m"
.PHONY: help
.DEFAULT_GOAL := help
