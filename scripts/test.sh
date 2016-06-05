set -e

./scripts/dev.sh
./scripts/lint.sh
./scripts/unit-tests.sh
./scripts/functional-tests.sh
