#!/bin/bash
#
# Check for code incompatible with Drupal 9.
#
set -e

echo "=> Fail if non-Drupal 9 code is present in codebase"
# See https://github.com/dcycle/docker-drupal-check on why we're using
# this weird tag
docker run --rm -v "$(pwd)":/var/www/html/modules/realistic_dummy_content dcycle/drupal-check:1.2019-12-30-21-59-43-UTC realistic_dummy_content/api/src
