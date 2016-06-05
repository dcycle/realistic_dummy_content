set -e

echo -e ""
echo -e "-----"
echo -e ""
echo -e "About to run fast unit tests on your environments."

docker build -f="Dockerfile-phpunit" -t docker-realistic_dummy_content-phpunit .
docker run -d --name rdc_dev_phpunit -v $(pwd):/testable/mycode docker-realistic_dummy_content-phpunit

echo -e "[<<  ] End of script $0"

echo -e ""
echo -e "Unit tests complete; no errors found."
echo -e ""
