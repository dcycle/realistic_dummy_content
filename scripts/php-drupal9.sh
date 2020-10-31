#!/bin/bash
#
# Check for code incompatible with Drupal 9.
#
set -e

echo "=> Fail if non-Drupal 9 code is present in codebase"
docker run --rm -v "$(pwd)":/var/www/html/modules/realistic_dummy_content dcycle/drupal-check:1 realistic_dummy_content/api/src
