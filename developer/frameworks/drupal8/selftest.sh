#/bin/bash
#
# Self-tests.
#
set -e

./exec.sh drupal8 'drush eval "realistic_dummy_content_api_cms_selftest()"'

./exec.sh drupal8 'drush generate-realistic'
