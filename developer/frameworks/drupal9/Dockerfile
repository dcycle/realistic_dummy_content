FROM dcycle/drupal:9

ADD ./frameworks/drupal9/docker-resources /resources

RUN export COMPOSER_MEMORY_LIMIT=-1 && composer require drupal/devel

RUN cp -r sites/default /sites-default

EXPOSE 80
