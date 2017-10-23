#!/bin/bash
#
# Uninstall the comment module for Drupal 8
#

set -e

drush ev '\Drupal::service("entity_field.manager")->getFieldStorageDefinitions("node")["comment"]->delete();'
drush cron
drush -y pm-uninstall comment
