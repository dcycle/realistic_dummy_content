#!/bin/bash
#
# Use ./docker-compose-in-docker.sh ... instead of docker-compose ...
# We do not want to make assumptions about whether or not docker-compose
# is installed on a given platform (for example CoreOS does not have
# docker-compose). In keeping with the philosophy of Docker of keeping
# every dependency in a container, docker-compose being a dependency, we
# use it within a container.
#

docker run \
  -v /var/run/docker.sock:/var/run/docker.sock \
  -v "$PWD:/rootfs/$PWD" \
  -w="/rootfs/$PWD" \
  docker/compose "$@"
