set -e

echo -e ""
echo -e "-----"
echo -e ""
echo -e "About to fix for coding standards for realistic_dummy_content on the D8 environment."

docker exec rdc_dev_d8 bash -c '/root/.composer/vendor/bin/phpcbf  --standard=Drupal --ignore=./scripts/tmp/* /srv/drupal/www/sites/all/modules/realistic_dummy_content'
