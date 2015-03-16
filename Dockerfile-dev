# See https://github.com/alberto56/docker-drupal
FROM alberto56/docker-drupal:7.x-dev-2.0

RUN cd ./srv/drupal/www && /usr/bin/drush en realistic_dummy_content -y
RUN cd ./srv/drupal/www && /usr/bin/drush uli
RUN echo ''
RUN echo 'To log into this environment, please call ./scripts/uli.sh'
RUN echo ''
