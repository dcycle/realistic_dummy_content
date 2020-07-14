FROM dcycle/drupal:8drush

ADD ./frameworks/drupal8/docker-resources /resources

RUN export COMPOSER_MEMORY_LIMIT=-1 && composer require drupal/devel

RUN cp -r sites/default /sites-default

EXPOSE 80
