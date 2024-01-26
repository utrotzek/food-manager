#!/usr/bin/env bash
docker exec -e XDEBUG_MODE="" $(docker compose ps -q app) composer "$@"
