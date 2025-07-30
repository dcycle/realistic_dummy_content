#!/bin/bash
#
# Run unit tests a la Drupal. Requires the container to be running.
#
set -e

docker-compose exec -T drupal /bin/bash -c '
  composer require --dev phpunit/phpunit &&
  vendor/bin/phpunit -c core/phpunit.xml.dist modules/custom/realistic_dummy_content/api/tests
'
