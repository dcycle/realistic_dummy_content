#!/bin/bash
#
# Be ready for Drupal 9.
#
set -e

echo "=> Identify deprecated code so we're ready for Drupal 9"
# I want to ignore ./api/src/Framework/Drupal7.php;
# https://github.com/mglaman/drupal-check/issues/156 suggests phpstan, but
# phpstan says out of memory, so I'll just remove it and put it back
# after, hope no one notices.
mv ./api/src/Framework/Drupal7.php ./api/src/Framework/Drupal7.php.txt
# See https://github.com/dcycle/docker-drupal-check on why we're using
# this weird tag
docker run --rm -v "$(pwd)":/var/www/html/modules/realistic_dummy_content dcycle/drupal-check:1.2019-12-30-21-59-43-UTC realistic_dummy_content
mv ./api/src/Framework/Drupal7.php.txt ./api/src/Framework/Drupal7.php
