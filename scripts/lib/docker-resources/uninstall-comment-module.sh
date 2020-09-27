#!/bin/bash
#
# Uninstall the comment module for Drupal 8 or 9.
#

set -e

echo 'About to delete the comment module.'
echo 'Deleting all comment entity content...'
# We want to interpret $comment as a literal, not a variable.
# shellcheck disable=SC2016
drush ev 'foreach(\Drupal::entityTypeManager()->getStorage("comment")->loadMultiple() as $comment) { $comment->delete(); }'
echo 'Deleting the comment storage...'
drush ev "\Drupal::entityTypeManager()->getStorage('field_config')->load('node.article.comment')->delete();"
echo 'Run cron to avoid the fields pending deletion issue...'
drush cron
echo 'Uninstalling the comme module...'
drush -y pm-uninstall comment
