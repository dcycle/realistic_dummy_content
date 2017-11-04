#/bin/bash
#
# Self-tests.
#
set -e

./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush eval "realistic_dummy_content_api_selftest()"'
./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c \
  "drush en -y simpletest && \
  php ./scripts/run-tests.sh \
    --class \
    --url http://localhost \
    --verbose \
    RealisticDummyContentDatabaseTestCase"

./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush generate-realistic'
./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush dis -y comment && drush generate-realistic'

./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush -y dis realistic_dummy_content'
./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush -y dis realistic_dummy_content_api'
./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content'
./docker-compose-in-docker.sh exec -T drupal7 /bin/bash -c 'drush -y pm-uninstall realistic_dummy_content_api'
