#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"

echo 'Make sure values returned make sense with base and submodules enabled'
curl "$URL" | grep 'Drupal'

echo " => Running self-test"
docker-compose exec drupal /bin/bash -c 'drush eval "realistic_dummy_content_api_selftest()"'
echo " => Uninstalling comment module"
docker-compose exec drupal /bin/bash -c '/var/www/html/modules/custom/realistic_dummy_content/scripts/lib/docker-resources/uninstall-comment-module.sh'
echo -e 'Make sure we can run generate-realistic even if the'
echo -e 'comment module is disabled.'
if [ -v "$1" ]; then
  docker-compose exec "$1" /bin/bash -c 'drush generate-realistic'
fi
echo -e 'Reenable comment and make sure we can run'
echo -e 'generate-realistic.'
docker-compose exec drupal /bin/bash -c 'drush en -y comment'
if [ -v "$1" ]; then
  docker-compose exec "$1" /bin/bash -c 'drush generate-realistic'
fi

docker-compose exec drupal /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content'
docker-compose exec drupal /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content_api'
echo " => Checking if errors occurred and are present in the watchdog"
docker-compose exec drupal /bin/bash -c '(drush ws|grep Error && exit 1 || exit 0)'
