#!/bin/bash
#
# Static analysis.
#
set -e

echo '=> Static analysis of code.'
echo 'If you are getting a false negative, use:'
echo ''
echo '// @phpstan-ignore-next-line'
# Doing this in several steps due to out of memory error.
for FILE in \
  /var/www/html/modules/custom/realistic_dummy_content/api/src/Framework \
  /var/www/html/modules/custom/realistic_dummy_content/api/src/includes \
  /var/www/html/modules/custom/realistic_dummy_content/api/src/traits \
  /var/www/html/modules/custom/realistic_dummy_content/api/realistic_dummy_content_api.module
do
  docker run --rm \
    -v "$(pwd)":/var/www/html/modules/custom/realistic_dummy_content \
    dcycle/phpstan-drupal:2 \
    -c /var/www/html/modules/custom/realistic_dummy_content/scripts/lib/phpstan/phpstan.neon \
    "$FILE"
done
