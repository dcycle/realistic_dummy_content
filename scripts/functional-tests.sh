set -e

echo -e ""
echo -e "-----"
echo -e ""
echo -e "About to run functional tests on your environments."

docker exec rdc_dev_d7 bash -c 'cd /srv/drupal/www && drush test-run RealisticDummyContentDatabaseTestCase'

echo -e ""
echo -e "Functional tests complete; no errors found."
echo -e ""
