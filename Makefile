service=laravel

.DEFAULT_GOAL := help

## Help command to show all the possible commands
help:
	@echo "$$(tput bold)Available rules:$$(tput sgr0)"
	@echo
	@sed -n -e "/^## / { \
		h; \
		s/.*//; \
		:doc" \
		-e "H; \
		n; \
		s/^## //; \
		t doc" \
		-e "s/:.*//; \
		G; \
		s/\\n## /---/; \
		s/\\n/ /g; \
		p; \
	}" ${MAKEFILE_LIST} \
	| LC_ALL='C' sort --ignore-case \
	| awk -F '---' \
		-v ncol=$$(tput cols) \
		-v indent=19 \
		-v col_on="$$(tput setaf 6)" \
		-v col_off="$$(tput sgr0)" \
	'{ \
		printf "%s%*s%s ", col_on, -indent, $$1, col_off; \
		n = split($$2, words, " "); \
		line_length = ncol - indent; \
		for (i = 1; i <= n; i++) { \
			line_length -= length(words[i]) + 1; \
			if (line_length <= 0) { \
				line_length = ncol - indent - length(words[i]) - 1; \
				printf "\n%*s ", -indent, " "; \
			} \
			printf "%s ", words[i]; \
		} \
		printf "\n"; \
	}' \
	| more $(shell test $(shell uname) == Darwin && echo '--no-init --raw-control-chars')


## Setup dev box
setup:
	docker-compose --project-name=$(service) -f build/local/docker-compose.yml up -d --build --force-recreate
	$(MAKE) composer-install

## Run cli command in docker container
cli-command:
	docker exec -t -i $(service)_app /bin/sh -c '$(command)'

#------------------------------------
# Docker container management
#------------------------------------
## Setup your local
docker-local:
	$(MAKE) dc-network-local
	docker-compose --project-name=$(service) -f build/local/docker-compose.yml up -d

## Stop containers
docker-down:
	docker-compose --project-name=$(service) -f build/local/docker-compose.yml down

## Access the container shell
docker-sh:
	docker exec -it $(service)_app /bin/sh

## Assign to IP on private network. Enable connection from other containers on the network.
dc-network-local:
	docker network inspect incentives_incentives_network >/dev/null  2>&1 || docker network create --gateway 172.16.9.1 --subnet 172.16.9.0/24 incentives_incentives_network_network || exit 0

## Build/rebuild containers - e.g. use after Dockerfile update
docker-build:
	docker-compose --project-name=$(service) -f build/local/docker-compose.yml build

## View the logs
docker-logs:
	docker-compose --project-name=$(service) -f build/local/docker-compose.yml logs -f

#------------------------------------
# Composer commands
#------------------------------------
## Composer install
composer-install:
	$(MAKE) cli-command command='composer install'

## Composer update
composer-update:
	$(MAKE) cli-command command='composer update $(package)'

## Composer require
composer-require:
	$(MAKE) cli-command command='composer require $(package)'

## Composer require dev
composer-require-dev:
	$(MAKE) cli-command command='composer require --dev $(package)'

## Composer remove
composer-remove:
	$(MAKE) cli-command command='composer remove $(package)'

#------------------------------------
# Testing Commands
#------------------------------------
## Run all the tests
test: cs-check phpstan phpunit behat

## Run all the Unit tests
phpunit:
	$(MAKE) cli-command command='php artisan test $(test)'

## Run all the Unit tests with output reported
phpunit-report:
	$(MAKE) cli-command command='php artisan test --log-junit Results/Incentives.xml $(test)'

## Generate HTML Unit Test report with coverage
phpunit-coverage-html:
	$(MAKE) cli-command command='php artisan test --coverage-html unitreports/'

## Check for the coding standard errors
cs-check:
	$(MAKE) cli-command command='./vendor/bin/ecs check ./app ./tests --config ecs.php';

## Fix coding standard errors
cs-fix:
	$(MAKE) cli-command command='./vendor/bin/ecs check ./app ./tests --config ecs.php --fix';

## Static Analyse your application
phpstan:
	$(MAKE) cli-command command='./vendor/bin/phpstan analyse --memory-limit=-1'

## Run Behat tests
behat:
	$(MAKE) cli-command command='php artisan db:wipe --database=test'
	$(MAKE) cli-command command='php artisan migrate --database=test'
	$(MAKE) cli-command command='php ./vendor/bin/behat --no-coverage $(test)'

## Run Behat tests with code coverage
behat-coverage:
	$(MAKE) cli-command command='php artisan db:wipe --database=test'
	$(MAKE) cli-command command='php artisan migrate --database=test'
	$(MAKE) cli-command command='XDEBUG_MODE=off php -d memory_limit=512M ./vendor/bin/behat $(test)'

## Mutation Testing
mutation:
	$(MAKE) cli-command command='./vendor/bin/infection --test-framework-options="--verbose --testsuite=Domain"';

