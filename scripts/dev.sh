docker build -f="Dockerfile-dev" -t docker-realistic_dummy_content .
# using no-cache to get the result of drush uli until
# https://github.com/docker/docker/issues/1996 is fixed
docker run -d -p 80:80 docker-realistic_dummy_content
