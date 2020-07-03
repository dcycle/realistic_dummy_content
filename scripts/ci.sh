#!/bin/bash
#
# Run all tests on a CI server.
#
set -e

./scripts/php-drupal9.sh
cd ./developer && ./test.sh
cd ..
