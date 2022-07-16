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

## Run cli command in docker container
cli-command:
	docker exec -it $(service)_app /bin/bash -c '$(command)'

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