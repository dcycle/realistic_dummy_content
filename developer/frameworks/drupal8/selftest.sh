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
