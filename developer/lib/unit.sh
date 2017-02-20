#!/bin/bash
#
# Run PHPUnit tests.
#
set -e

docker run -v "$(pwd)":/app phpunit/phpunit:5.7.12 \
  --group realistic_dummy_content
