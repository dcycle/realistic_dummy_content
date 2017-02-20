#!/bin/bash
#
# Run linting on shell scripts.
#
set -e

# See https://github.com/dcycle/docker-shell-lint
find . -name "*.sh" -print0 | \
  xargs -0 docker run -v "$(pwd)":/code dcycle/shell-lint
