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

echo '=> Tests on Drupal 8 environment.'
./scripts/test-running-environment.sh

echo '=> Destroy the Drupal 8 environment.'
./scripts/destroy.sh

echo '=> Deploy a Drupal 9 environment.'
./scripts/deploy.sh 9

echo '=> Drupal PHPUnit tests on required Drupal 9 environment.'
./scripts/php-unit-drupal.sh

echo '=> Tests on Drupal 9 environment.'
./scripts/test-running-environment.sh

echo '=> Destroy the Drupal 9 environment.'
./scripts/destroy.sh
