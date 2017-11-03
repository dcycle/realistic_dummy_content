#!/bin/bash
#
# Use ./docker-compose-in-docker.sh ... instead of docker-compose ...
# We do not want to make assumptions about whether or not docker-compose
# is installed on a given platform (for example CoreOS does not have
# docker-compose). In keeping with the philosophy of Docker of keeping
# every dependency in a container, docker-compose being a dependency, we
# use it within a container.
#
# See https://hub.docker.com/r/docker/compose.
# At the time of this writing there is no "latest" tag for the image, so
# we manually entered the latest version, which can be changed of required.
#

docker run \
  -v /var/run/docker.sock:/var/run/docker.sock \
  -v "$PWD:/rootfs/$PWD" \
  -w="/rootfs/$PWD" \
  docker/compose:1.17.0 "$@"
