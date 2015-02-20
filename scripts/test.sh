# Our Dockerfile needs to be named "Dockerfile" because we are using CircleCI
# and it does not allow us to use the -f flag to specify another filename (for
# example Dockerfile-test). In
# https://circleci.com/gh/alberto56/realistic_dummy_content/3, we see that we get
# the error "flag provided but not defined: -f" when we try to use the -f flag.
# See ./scripts/dev.sh for an example usage of the -f flag.
docker build .
