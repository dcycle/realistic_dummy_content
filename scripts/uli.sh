#!/bin/bash
#
# Get a login link for the environment.
#
set -e

docker-compose exec drupal /bin/bash -c "drush -l $(docker-compose port drupal 80) uli"
