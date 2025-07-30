#!/bin/bash
#
# Run tests, meant to be run on CirlceCI.
#
set -e

# echo '=> Run fast tests.'
# ./scripts/test.sh

echo '=> Deploy a Drupal 11 environment.'
./scripts/deploy.sh

echo '=> Drupal PHPUnit tests on required Drupal 11 environment.'
./scripts/php-unit-drupal.sh

# echo '=> Tests on Drupal 11 environment using drush 13.'
./scripts/test-running-environment.sh
./scripts/test-running-environment-drush.sh drupal
./scripts/test-running-environment-delete.sh

echo '=> Destroy the Drupal 11 environment.'
./scripts/destroy.sh
