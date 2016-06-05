set -e

echo -e ""
echo -e "-----"
echo -e ""
echo -e "About to check for coding standards for realistic_dummy_content on the D8 environment."

docker exec rdc_dev_d8 bash -c '/root/.composer/vendor/bin/phpcs  --standard=Drupal --ignore=./scripts/tmp/* /srv/drupal/www/sites/all/modules/realistic_dummy_content'
docker exec rdc_dev_d8 bash -c '/root/.composer/vendor/bin/phpcs  --standard=DrupalPractice --ignore=./scripts/tmp/* /srv/drupal/www/sites/all/modules/realistic_dummy_content'

echo -e ""
echo -e "Coding standards check complete; no errors found."
echo -e ""
