set -e

echo -e "About to destroy old containers named rdc_dev_d7 if it exists."

docker kill rdc_dev_d7 > /dev/null 2> /dev/null || true
docker rm rdc_dev_d7 > /dev/null 2> /dev/null || true

echo -e "About to build new rdc_dev_d7 container for D7 development."

docker build -f="Dockerfile-drupal7" -t docker-realistic_dummy_content .
docker run -d -p 80 --name rdc_dev_d7 -v $(pwd):/srv/drupal/www/sites/all/modules/realistic_dummy_content/ docker-realistic_dummy_content

echo -e "About to enable realistic_dummy_content on d7 environment."

docker exec rdc_dev_d7 bash -c 'cd /srv/drupal/www && drush en -y realistic_dummy_content devel_generate'
