#!/bin/bash
#
# Run unit tests a la Drupal. Requires the container to be running.
#
set -e

docker-compose exec -T drupal /bin/bash -c 'drush en -y simpletest && php core/scripts/run-tests.sh realistic_dummy_content'
