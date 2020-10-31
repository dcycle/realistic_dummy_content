#!/bin/bash
#
# Run some checks on a running environment: uninstall
#
set -e

docker-compose exec -T drupal /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content'
docker-compose exec -T drupal /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content_api'
echo " => Checking if errors occurred and are present in the watchdog"
docker-compose exec -T drupal /bin/bash -c '(drush ws|grep Error && exit 1 || exit 0)'
