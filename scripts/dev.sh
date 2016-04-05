set -e

which docker > /dev/null 2> /dev/null || { echo -e "[error] Calling which docker yielded an error. Please run this from within a machine which has Docker installed. For example, if you are on Mac OS X, you might want to install Vagrant, Virtual Box, and a CoreOS vagrant machine."; exit 1; }

echo -e "About to destroy old containers named rdc_dev_d7 and rdc_dev_d8."

docker kill rdc_dev_d7 > /dev/null
docker kill rdc_dev_d8 > /dev/null
docker rm rdc_dev_d7 > /dev/null
docker rm rdc_dev_d8 > /dev/null

echo -e "About to build new rdc_dev_d7 container for D7 development."

docker build -f="Dockerfile-dev" -t docker-realistic_dummy_content .
docker run -d -p 80:80 --name rdc_dev_d7 -v $(pwd):/srv/drupal/www/sites/all/modules/realistic_dummy_content/ docker-realistic_dummy_content

echo -e "About to build new rdc_dev_d8 container for D8 development."

docker build -f="Dockerfile-dev8" -t docker-realistic_dummy_content .
docker run -d -p 81:80 --name rdc_dev_d8 -v $(pwd):/srv/drupal/www/sites/all/modules/realistic_dummy_content/ docker-realistic_dummy_content

echo -e "About to enable realistic_dummy_content on d7 and d8 environments."

docker exec rdc_dev_d7 bash -c 'cd /srv/drupal/www && drush en -y realistic_dummy_content'
docker exec rdc_dev_d8 bash -c 'cd /srv/drupal/www && drush en -y realistic_dummy_content'

echo -e ""
echo -e "-----"
echo -e ""
echo -e "Congratulations! Your development environments are ready."
echo -e ""
echo -e "To log into your D7 environment go to:"
echo -e ""
echo -e ' ==> '$(./scripts/uli.sh rdc_dev_d7)|sed 's/default/172.17.8.101:80/g'
echo -e ""
echo -e "To log into your D8 environment go to:"
echo -e ""
echo -e ' ==> '$(./scripts/uli.sh rdc_dev_d8)|sed 's/default/172.17.8.101:81/g'
echo -e ""
echo -e "Replace 172.17.8.101 with the IP address you use to access your development server."
echo -e ""
echo -e "The same code for realistic_dummy_content can be used for both"
echo -e "Drupal 7 or Drupal 8. Changes you make to the code at "
echo -e "$(pwd) will be reflected on both your environments."
echo -e ""

