#!/bin/bash
#
# Uninstall the comment module for Drupal 8
#

set -e

drush eval "\Drupal::entityManager()->getStorage('field_config')->load('node.article.comment')->delete();"
drush cron
drush -y pm-uninstall comment
