#!/bin/bash
#
# Lint shell scripts.
#
set -e

echo '=> Linting shell scripts.'
echo 'To ignore false negatives, use:'
echo '# shellcheck disable=SC2016'
# See https://github.com/dcycle/docker-shell-lint
find . -name "*.sh" -print0 | \
  xargs -0 docker run -v "$(pwd)":/code dcycle/shell-lint
