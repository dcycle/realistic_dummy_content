#!/bin/bash
#
# Deploy a development or testing environment.
# If this is passed with the argument "9", then Drupal 9 is used. Otherwise
# Drupal 8 is used.
#
set -e

echo ''
echo '-----'
echo 'About to create the realistic_dummy_content_default network if it does not exist,'
echo 'because we need it to have a predictable name when we try to connect'
echo 'other containers to it (for example browser testers).'
echo 'See https://github.com/docker/compose/issues/3736.'
docker network ls | grep realistic_dummy_content_default || docker network create realistic_dummy_content_default

echo ''
echo '-----'
echo 'About to start persistent (-d) containers based on the images defined'
echo 'in ./Dockerfile-* files. We are also telling docker-compose to'
echo 'rebuild the images if they are out of date.'
if [ "$1" == 9 ]; then
  docker-compose -f docker-compose.yml -f docker-compose.drupal9.yml up -d --build
else
  docker-compose up -d --build
fi

echo ''
echo '-----'
echo 'Running the deploy scripts on the container.'
docker-compose exec -T drupal /bin/bash -c 'cd ./modules/custom/realistic_dummy_content/scripts/lib/docker-resources && ./deploy.sh'

echo ''
echo '-----'
echo ''
echo 'If all went well you can now access your site at:'
echo ''
./scripts/uli.sh
echo ''
