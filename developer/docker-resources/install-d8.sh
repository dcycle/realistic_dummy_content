#!/bin/bash
#
# Install Drupal 8
#

set -e

cp -r /sites-default/* sites/default
echo -e "*****DRUPAL 8*****"
ls -lah sites/default

drush si \
  -y \
  --db-url=mysql://root:@database/drupal \
  --db-prefix=d8 \
  --account-name=admin \
  --account-pass=admin \
  standard \
  install_configure_form.update_status_module='array(FALSE,FALSE)'

drush en realistic_dummy_content devel_generate devel -y
