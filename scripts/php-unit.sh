#!/bin/bash
#
# Run unit tests.
#
set -e

docker run --rm -v "$(pwd)":/app dcycle/phpunit:1 \
  --group realistic_dummy_content
