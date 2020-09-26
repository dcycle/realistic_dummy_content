#!/bin/bash
#
# This script is run when the Drupal docker container is ready. It prepares
# an environment for development or testing, which contains a full Drupal
# installation with a running website.
#
set -e

echo "Will try to connect to MySQL container until it is up. This can take up to 15 seconds if the container has just been spun up."
OUTPUT="ERROR"
while [[ "$OUTPUT" == *"ERROR"* ]]
do
  OUTPUT=$(echo 'show databases'|{ mysql -h mysql -u root --password=drupal 2>&1 || true; })
  if [[ "$OUTPUT" == *"ERROR"* ]]; then
    echo "MySQL container is not available yet. Should not be long..."
    sleep 2
  else
    echo "MySQL is up! Moving on..."
  fi
done

drush si -y --db-url "mysqli://root:drupal@mysql/drupal"
chown -R www-data:www-data /var/www/html/sites/default/files
drush cr
drush en -y realistic_dummy_content devel_generate
