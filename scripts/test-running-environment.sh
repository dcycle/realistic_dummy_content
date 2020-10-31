#!/bin/bash
#
# Run some checks on a running environment: basic
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"

echo 'Make sure values returned make sense with base and submodules enabled'
curl "$URL" | grep 'Drupal'

echo " => Running self-test"
docker-compose exec -T drupal /bin/bash -c 'drush eval "realistic_dummy_content_api_selftest()"'
echo " => Uninstalling comment module"
docker-compose exec -T drupal /bin/bash -c '/var/www/html/modules/custom/realistic_dummy_content/scripts/lib/docker-resources/uninstall-comment-module.sh'
