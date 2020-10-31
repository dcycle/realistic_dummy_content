#!/bin/bash
#
# Run tests, meant to be run on CirlceCI.
#
set -e

echo '=> Run fast tests.'
./scripts/test.sh

echo '=> Deploy a Drupal 8 environment.'
./scripts/deploy.sh

echo '=> Drupal PHPUnit tests on required Drupal 8 environment.'
./scripts/php-unit-drupal.sh

echo '=> Tests on Drupal 8 environment using drush 8 and drush 10.'
./scripts/test-running-environment.sh
./scripts/test-running-environment-drush.sh drupal
./scripts/test-running-environment-drush.sh drupal8drush8
./scripts/test-running-environment-delete.sh

echo '=> Destroy the Drupal 8 environment.'
./scripts/destroy.sh

echo '=> Deploy a Drupal 9 environment.'
./scripts/deploy.sh 9

echo '=> Drupal PHPUnit tests on required Drupal 9 environment.'
./scripts/php-unit-drupal.sh

echo '=> Tests on Drupal 9'
./scripts/test-running-environment.sh
./scripts/test-running-environment-drush.sh drupal
./scripts/test-running-environment-delete.sh

echo '=> Destroy the Drupal 9 environment.'
./scripts/destroy.sh
