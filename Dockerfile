# Our Dockerfile needs to be named "Dockerfile" because we are using CircleCI
# and it does not allow us to use the -f flag to specify another filename (for
# example Dockerfile-test). (See also ./scripts/test.sh).

FROM alberto56/docker-drupal:7.x-dev-2.0

ADD . ./srv/drupal/www/sites/all/modules/realistic_dummy_content/
RUN php -v
RUN cd ./srv/drupal/www && /usr/bin/drush dl coder-7.x-2.4 && /usr/bin/drush en coder_review -y
RUN cd ./srv/drupal/www && /usr/bin/drush en simpletest realistic_dummy_content -y
RUN cd ./srv/drupal/www && wget https://www.drupal.org/files/drupal-simpletest-fails-to-drop-tables-sqlite-1713332-21.patch
RUN cd ./srv/drupal/www && patch -p1 < drupal-simpletest-fails-to-drop-tables-sqlite-1713332-21.patch

EXPOSE 80

RUN cd ./srv/drupal/www && /usr/bin/drush --uri=http://127.0.0.1 test-run "Realistic dummy content"
# Run code review, but don't break the build in case of errors. We only want to keep track
# of metrics, not enforce adherence. Note that we are explicitly checking for *.module
# files because of https://www.drupal.org/node/2316653
RUN cd ./srv/drupal/www && /usr/bin/drush coder --minor sites/all/modules/realistic_dummy_content sites/all/modules/realistic_dummy_content/*.module sites/all/modules/realistic_dummy_content/api/*.module || true
