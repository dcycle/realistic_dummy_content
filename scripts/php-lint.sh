#!/bin/bash
#
# Lint PHP code.
#
set -e

echo '=> Linting PHP code.'
echo 'If you are getting a false negative, use:'
echo ''
echo '// @codingStandardsIgnoreStart'
echo '...'
echo '// @codingStandardsIgnoreEnd'
echo ''
docker run -v "$(pwd)":/code dcycle/php-lint:2 --extensions=php,module,install,inc --standard=DrupalPractice /code
docker run -v "$(pwd)":/code dcycle/php-lint:2 --extensions=php,module,install,inc --standard=Drupal /code
