#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"

echo 'Make sure values returned make sense with base and submodules enabled'
curl "$URL" | grep 'Drupal'

#/bin/bash
#
# Self-tests.
#
set -e

docker-compose exec drupal /bin/bash -c 'drush eval "realistic_dummy_content_api_selftest()"'
docker-compose exec drupal /bin/bash -c '/resources/uninstall-comment-module.sh'
echo -e 'Make sure we can run generate-realistic even if the'
echo -e 'comment module is disabled.'
docker-compose exec drupal /bin/bash -c 'drush generate-realistic'
echo -e 'Reenable comment and make sure we can run'
echo -e 'generate-realistic.'
docker-compose exec drupal /bin/bash -c 'drush en -y comment'
docker-compose exec drupal /bin/bash -c 'drush generate-realistic'

docker-compose exec drupal /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content'
docker-compose exec drupal /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content_api'
