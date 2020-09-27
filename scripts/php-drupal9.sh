#!/bin/bash
#
# Be ready for Drupal 9.
#
set -e

echo "=> Identify deprecated code so we're ready for Drupal 9"
# See https://github.com/dcycle/docker-drupal-check on why we're using
# this weird tag
docker run --rm -v "$(pwd)":/var/www/html/modules/realistic_dummy_content dcycle/drupal-check:1.2019-12-30-21-59-43-UTC realistic_dummy_content/api/src
