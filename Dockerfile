# Our Dockerfile needs to be named "Dockerfile" because we are using CircleCI
# and it does not allow us to use the -f flag to specify another filename (for
# example Dockerfile-test). (See also ./scripts/test.sh).

FROM alberto56/docker-drupal:latest

ADD . ./srv/drupal/www/sites/all/modules/realistic_dummy_content/
RUN cd ./srv/drupal/www && /usr/bin/drush en simpletest realistic_dummy_content -y
RUN cd ./srv/drupal/www && wget https://www.drupal.org/files/drupal-simpletest-fails-to-drop-tables-sqlite-1713332-21.patch
RUN cd ./srv/drupal/www && patch -p1 < drupal-simpletest-fails-to-drop-tables-sqlite-1713332-21.patch

EXPOSE 80

RUN cd ./srv/drupal/www && drush --uri=http://127.0.0.1 test-run "Realistic dummy content"
