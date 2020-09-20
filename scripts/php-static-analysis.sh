#!/bin/bash
#
# Static analysis.
#
set -e

echo '=> Static analysis of code.'
echo 'If you are getting a false negative, use:'
echo ''
echo '// @phpstan:ignoreError'
docker run --rm \
  -v "$(pwd)":/var/www/html/modules/custom/realistic_dummy_content \
  dcycle/phpstan-drupal:1 \
  -c /var/www/html/modules/custom/realistic_dummy_content/scripts/lib/phpstan/phpstan.neon \
  /var/www/html/modules/custom
