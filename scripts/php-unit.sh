#!/bin/bash
#
# Run unit tests.
#
set -e

docker run --rm -v "$(pwd)":/app phpunit/phpunit \
  --group realistic_dummy_content
