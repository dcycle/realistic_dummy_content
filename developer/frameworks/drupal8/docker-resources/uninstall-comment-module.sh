#!/bin/bash
#
# Uninstall the comment module for Drupal 8
#

set -e

echo 'About to delete the comment module.'
echo 'Deleting all comment entity content...'
# We want to interpret $comment as a literal, not a variable.
# shellcheck disable=SC2016
drush ev 'foreach(entity_load_multiple("comment") as $comment) { $comment->delete(); }'
echo 'Running cron to delete the actual comments...'
drush cron
echo 'Uninstalling the comme module...'
drush -y pm-uninstall comment
