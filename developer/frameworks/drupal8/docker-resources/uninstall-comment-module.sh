#!/bin/bash
#
# Uninstall the comment module for Drupal 8
#

set -e

echo "About to delete all entites of type comment, so we can uninstall the comment module."
drush eval "\Drupal::entityManager()->getStorage('field_config')->load('node.article.comment')->delete();"
echo "There should not longer be any comments at this point."
drush cron
drush -y pm-uninstall comment
