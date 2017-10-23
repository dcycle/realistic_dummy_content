#!/bin/bash
#
# Uninstall the comment module for Drupal 8
#

set -e

echo 'About to delete the comment module.'
echo 'Deleting all comment entity content...'
drush ev '\Drupal::service("entity_field.manager")->getFieldStorageDefinitions("node")["comment"]->delete();'
echo 'Running cron to delete the actual comments...'
drush cron
echo 'Uninstalling the comme module...'
drush -y pm-uninstall comment
