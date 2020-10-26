#!/bin/bash
set -e
source ~/.docker-host-ssh-credentials

# Create a droplet
PRIVATE_IP=$(ssh "$DOCKERHOSTUSER@$DOCKERHOST" \
  "./digitalocean/scripts/new-droplet.sh realistic-dummy-content")
# https://github.com/dcycle/docker-digitalocean-php#public-vs-private-ip-addresses
IP=$(ssh "$DOCKERHOSTUSER@$DOCKERHOST" "./digitalocean/scripts/list-droplets.sh" |grep "$PRIVATE_IP" --after-context=10|tail -1|cut -b 44-)
echo "Created Droplet at $IP"
sleep 90
ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \
  root@"$IP" \
  "git clone http://github.com/dcycle/realistic_dummy_content && \
  cd realistic_dummy_content && \
  ./scripts/ci.sh"
