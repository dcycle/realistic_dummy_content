#!/bin/bash
#
# Destroy the environment.
#
set -e

docker-compose down -v
docker network rm realistic_dummy_content_default
