FROM dcycle/drupal:8

ADD ./frameworks/drupal8/docker-resources /resources

RUN drush dl devel -y

RUN cp -r sites/default /sites-default

EXPOSE 80
