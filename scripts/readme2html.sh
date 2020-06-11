#!/bin/bash
#
# Generate html from md. Useful for updating the description on Drupal.
#
set -e

docker pull dcycle/md2html:1
docker run --rm -v "$(pwd):/app/code" \
  dcycle/md2html:1 -t html5 README.md -o README.html

# If one enters <code>&gt;</code> in Drupal.org, it is erroneously
# output as <code class="language-php">&amp;gt;</code>; because we know
# that this only happens in certain contexts (")->") in our case, we can
# simply reconvert it to something which will appear correctly in
# Drupal.org.
# See https://www.drupal.org/node/3103954
# To fix it we cannot use sed because it's used differently on different
# systems, perl seems a good alternative, see
# https://stackoverflow.com/a/4247319/1207752
perl -i -pe's/&amp;&amp;/&&/g' README.html
