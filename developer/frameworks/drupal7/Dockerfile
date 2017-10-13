FROM dcycle/drupal:7

ADD ./frameworks/drupal7/docker-resources /resources

RUN drush dl devel -y

RUN cp -r sites/default /sites-default

EXPOSE 80
