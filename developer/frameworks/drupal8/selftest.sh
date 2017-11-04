#/bin/bash
#
# Self-tests.
#
set -e

./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c 'drush eval "realistic_dummy_content_api_selftest()"'

./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c '/resources/uninstall-comment-module.sh'
echo -e 'Make sure we can run generate-realistic even if the'
echo -e 'comment module is disabled.'
./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c 'drush generate-realistic'
echo -e 'Reenable comment and make sure we can run'
echo -e 'generate-realistic.'
./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c 'drush en -y comment'
./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c 'drush generate-realistic'

./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content'
./docker-compose-in-docker.sh exec -T drupal8 /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content_api'
