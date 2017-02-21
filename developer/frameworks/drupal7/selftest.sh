#/bin/bash
#
# Self-tests.
#
set -e

./exec.sh drupal7 'drush eval "realistic_dummy_content_api_cms_selftest()"'
./exec.sh drupal7 \
  "drush en -y simpletest && \
  php ./scripts/run-tests.sh \
    --class \
    --url http://localhost \
    --verbose \
    RealisticDummyContentDatabaseTestCase"
