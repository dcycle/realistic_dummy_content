docker exec rdc_dev_d7 bash -c 'cd /srv/drupal/www && drush cc all'
docker exec rdc_dev_d8 bash -c 'cd /srv/drupal/www && drush cache-rebuild'
