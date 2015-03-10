docker build -f="Dockerfile-dev" -t docker-realistic_dummy_content .
docker run -d -p 80:80 -v $(pwd):/srv/drupal/www/sites/all/modules/realistic_dummy_content/ docker-realistic_dummy_content
