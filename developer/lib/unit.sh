#!/bin/bash
#
# Run PHPUnit tests.
#
set -e

BASEPATH="$(pwd)"

cd "$BASEPATH"/.. && docker run -v "$(pwd)":/app phpunit/phpunit \
  --group realistic_dummy_content
