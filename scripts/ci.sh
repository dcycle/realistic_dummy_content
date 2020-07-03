#!/bin/bash
#
# Run all tests on a CI server.
#
set -e

cd ./developer && ./test.sh
cd ..
