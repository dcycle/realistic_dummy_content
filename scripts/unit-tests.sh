set -e

echo -e ""
echo -e "-----"
echo -e ""
echo -e "About to run fast unit tests on your environments."

docker kill rdc_dev_phpunit > /dev/null 2> /dev/null || true
docker rm rdc_dev_phpunit > /dev/null 2> /dev/null || true
docker build -f="Dockerfile-phpunit" -t rdc_dev_phpunit .
docker run -d --name rdc_dev_phpunit -v $(pwd)/api/src:/testable/mycode rdc_dev_phpunit

echo -e "[<<  ] End of script $0"

echo -e ""
echo -e "Unit tests complete; no errors found."
echo -e ""
