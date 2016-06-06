set -e

which docker > /dev/null 2> /dev/null || { echo -e "[error] Calling which docker yielded an error. Please run this from within a machine which has Docker installed. For example, if you are on Mac OS X, you might want to install Vagrant, Virtual Box, and a CoreOS vagrant machine."; exit 1; }

./scripts/dev-d7.sh
./scripts/dev-d8.sh
./scripts/dev-b1.sh

echo -e ""
echo -e "-----"
echo -e ""
echo -e "Congratulations! Your development environments are ready."
echo -e ""
./scripts/uli.sh
echo -e ""
echo -e "The same code for realistic_dummy_content can be used for"
echo -e "Drupal 7, Drupal 8 and Backdrop 1. Changes you make to the code at "
echo -e $(pwd)" will be reflected on all your environments."
