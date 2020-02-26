#!/bin/bash

# ./start.sh        выбираем Develope/Production и разворачиваем

# Develope вариант
#export DOCKER_IMAGE_TAG=latest
#export COMPOSE_FILE=base-dev.yml:env-dev.yml;
#export COMPOSE_PROJECT_NAME=respiratory;

# Production вариант
export DOCKER_IMAGE_TAG=production
export COMPOSE_FILE=base-prod.yml:env-prod.yml;
export COMPOSE_PROJECT_NAME=respiratory;

docker-compose stop && docker-compose up -d --build