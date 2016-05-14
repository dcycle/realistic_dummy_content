set -e

which docker > /dev/null 2> /dev/null || { echo -e "[error] Calling which docker yielded an error. Please run this from within a machine which has Docker installed. For example, if you are on Mac OS X, you might want to install Vagrant, Virtual Box, and a CoreOS vagrant machine."; exit 1; }

echo -e "About to destroy old containers named rdc_dev_d7, rdc_dev_d8 and rdc_dev_b1 if they exist."

# Before destroying, keep a backup of our backdrop site.
docker exec rdc_dev_b1 /bin/bash -c 'mkdir -p /var/www/html/modules/realistic_dummy_content/scripts/tmp/backdrop'
docker exec rdc_dev_b1 /bin/bash -c 'mysqldump backdrop > /var/www/html/modules/realistic_dummy_content/scripts/tmp/backdrop/database.sql'
docker exec rdc_dev_b1 /bin/bash -c 'cp -r /var/www/html/files /var/www/html/modules/realistic_dummy_content/scripts/tmp/backdrop/files'
docker exec rdc_dev_b1 /bin/bash -c 'cp -r /var/www/html/settings.php /var/www/html/modules/realistic_dummy_content/scripts/tmp/backdrop/settings.php'

docker kill rdc_dev_d7 > /dev/null 2> /dev/null || true
docker kill rdc_dev_d8 > /dev/null 2> /dev/null || true
docker kill rdc_dev_b1 > /dev/null 2> /dev/null || true
docker rm rdc_dev_d7 > /dev/null 2> /dev/null || true
docker rm rdc_dev_d8 > /dev/null 2> /dev/null || true
docker rm rdc_dev_b1 > /dev/null 2> /dev/null || true

echo -e "About to build new rdc_dev_d7 container for D7 development."

docker build -f="Dockerfile-drupal7" -t docker-realistic_dummy_content .
docker run -d -p 80 --name rdc_dev_d7 -v $(pwd):/srv/drupal/www/sites/all/modules/realistic_dummy_content/ docker-realistic_dummy_content

echo -e "About to build new rdc_dev_d8 container for D8 development."

docker build -f="Dockerfile-drupal8" -t docker-realistic_dummy_content .
docker run -d -p 80 --name rdc_dev_d8 -v $(pwd):/srv/drupal/www/sites/all/modules/realistic_dummy_content/ docker-realistic_dummy_content

echo -e "About to build new rdc_dev_b1 container for Backdrop 1 development."

docker build -f="Dockerfile-backdrop1" -t docker-realistic_dummy_content .
docker run -d -p 80 --name rdc_dev_b1 -v $(pwd):/var/www/html/modules/realistic_dummy_content/ docker-realistic_dummy_content

echo -e "About to enable realistic_dummy_content on d7, d8 and b1 environments."

docker exec rdc_dev_d7 bash -c 'cd /srv/drupal/www && drush en -y realistic_dummy_content devel_generate'
docker exec rdc_dev_d8 bash -c 'cd /srv/drupal/www && drush en -y realistic_dummy_content devel_generate'
docker exec -t -i rdc_dev_b1 /bin/bash -c 'echo "create database backdrop;"|mysql -uroot'

echo -e ""
echo -e "-----"
echo -e ""
echo -e "Congratulations! Your development environments are ready."
echo -e ""
./scripts/uli.sh
echo -e ""
echo -e "The same code for realistic_dummy_content can be used for"
echo -e "Drupal 7, Drupal 8 and Backdrop 1. Changes you make to the code at "
echo -e $(pwd)" will be reflected on all your environments."
echo -e ""
echo -e "You will have to install your Backdrop instance manually in the GUI"
echo -e "because drush site-install is not working for Backdrop:"
echo -e ""
echo -e " => backdrop database: backdrop"
echo -e " => backdrop mysql username: root"
echo -e " => backdrop mysql password: <no password>"
