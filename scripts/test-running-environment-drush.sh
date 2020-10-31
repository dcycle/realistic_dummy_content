#!/bin/bash
#
# Run some checks on a running environment: drush
#
set -e

if [ -z "$1" ]; then
  >&2 echo "Please specify a docker compose service such as drupal"
fi

docker-compose exec -T "$1" /bin/bash -c 'drush version'
echo -e 'Make sure we can run generate-realistic even if the'
echo -e 'comment module is disabled.'
docker-compose exec -T "$1" /bin/bash -c 'drush generate-realistic'
echo -e 'Reenable comment and make sure we can run'
echo -e 'generate-realistic.'
docker-compose exec -T drupal /bin/bash -c 'drush en -y comment'
docker-compose exec -T "$1" /bin/bash -c 'drush generate-realistic'
