#!/bin/bash
if [ "$#" -ne  "1" ]
  then
    echo "Please supply exactly one argument, not $#"
    echo ""
    echo "Usage:"
    echo ""
    echo "Mirrors this git repo from one location to another. This was introduced"
    echo "because Travis CI only works on Github repos, whereas we want the canonical"
    echo "repo for this project to be on Drupal.org."
    echo ""
    echo "First make sure you have access to the source and destinations via ssh key."
    echo "Then call, (for example) periodically from continuous integration server:"
    echo ""
    echo "./scripts/mirror.sh git@example.com/destination/repo"
else
  # We are mirroring from an existing directory.
  # See http://blog.plataformatec.com.br/2013/05/how-to-properly-mirror-a-git-repository/
  git fetch --prune
  git push --prune $1 +refs/remotes/origin/*:refs/heads/* +refs/tags/*:refs/tags/*
fi
