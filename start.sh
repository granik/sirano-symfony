#!/bin/bash

# ./start.sh        выбираем Develope/Production и разворачиваем

export COMPOSE_PROJECT_NAME=respiratory;

# Develope вариант
#export DOCKER_IMAGE_TAG=latest
#export COMPOSE_FILE=base-dev.yml:env-dev.yml;

# Production вариант
export DOCKER_IMAGE_TAG=production
export COMPOSE_FILE=base-prod.yml:env-prod.yml;

docker-compose stop && docker-compose up -d --build