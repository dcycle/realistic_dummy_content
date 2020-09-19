#!/bin/bash
#
# Run linting on PHP.
#
set -e

BASEPATH="$(pwd)"

echo -e '[lint] About to lint php files.'
echo -e '[lint] To ignore certain lines type:'
echo -e '[lint] // @codingStandardsIgnoreStart'
echo -e '[lint] ...'
echo -e '[lint] // @codingStandardsIgnoreEnd'

cd "$BASEPATH"/.. && docker run -v "$(pwd)":/code \
  dcycle/php-lint:2 \
  --ignore=developer/tmp/* \
  --standard=Drupal \
  --report=full \
  /code

cd "$BASEPATH"/.. && docker run -v "$(pwd)":/code \
  dcycle/php-lint:2 \
  --ignore=developer/tmp/* \
  --standard=Drupal \
  --report=full \
  /code/api/realistic_dummy_content_api.module
