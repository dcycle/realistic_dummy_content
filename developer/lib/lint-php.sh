#!/bin/bash
#
# Run linting on PHP.
#
set -e

docker run -v "$(pwd)":/code dcycle/php-lint --standard=DrupalPractice /code
docker run -v "$(pwd)":/code dcycle/php-lint --standard=Drupal /code
