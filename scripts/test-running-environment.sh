#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"

echo 'Make sure values returned make sense with base and submodules enabled'
curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status":"issues found; please check"'

docker-compose exec drupal /bin/bash -c 'drush pmu -y expose_status_details expose_status_ignore expose_status_severity'

echo 'Make sure values returned make sense with only the base module enabled'
curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status":"issues found; please check"'

#/bin/bash
#
# Self-tests.
#
set -e

./exec.sh drupal8 'drush eval "realistic_dummy_content_api_selftest()"'

./exec.sh drupal8 '/resources/uninstall-comment-module.sh'
echo -e 'Make sure we can run generate-realistic even if the'
echo -e 'comment module is disabled.'
./exec.sh drupal8 'drush generate-realistic'
echo -e 'Reenable comment and make sure we can run'
echo -e 'generate-realistic.'
./exec.sh drupal8 'drush en -y comment'
./exec.sh drupal8 'drush generate-realistic'

./exec.sh drupal8 'drush -y pm-uninstall realistic_dummy_content'
./exec.sh drupal8 'drush -y pm-uninstall realistic_dummy_content_api'
