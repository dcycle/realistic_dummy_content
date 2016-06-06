set -e

which docker > /dev/null 2> /dev/null || { echo -e "[error] Calling which docker yielded an error. Please run this from within a machine which has Docker installed. For example, if you are on Mac OS X, you might want to install Vagrant, Virtual Box, and a CoreOS vagrant machine."; exit 1; }

echo -e "About to destroy old container named rdc_dev_b1 if it exists."

docker kill rdc_dev_b1 > /dev/null 2> /dev/null || true
docker rm rdc_dev_b1 > /dev/null 2> /dev/null || true

echo -e "About to build new rdc_dev_b1 container for Backdrop 1 development."

docker build -f="Dockerfile-backdrop1" -t docker-realistic_dummy_content .
docker run -d -p 80 --name rdc_dev_b1 -v $(pwd):/var/www/html/modules/realistic_dummy_content/ docker-realistic_dummy_content

docker exec rdc_dev_b1 bash -c 'sleep 10 && cd /app && echo "create database if not exists backdrop"|mysql -uroot && ./core/scripts/install.sh --clean-url=0 --account-pass=admin --db-url=mysql://root:@localhost/backdrop && chmod -R 777 /app/files'
