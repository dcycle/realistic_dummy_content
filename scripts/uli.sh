D7PORT=$(docker ps|grep rdc_dev_d7|sed 's/.*0.0.0.0://g'|sed 's/->.*//g')
D8PORT=$(docker ps|grep rdc_dev_d8|sed 's/.*0.0.0.0://g'|sed 's/->.*//g')
B1PORT=$(docker ps|grep rdc_dev_b1|sed 's/.*0.0.0.0://g'|sed 's/->.*//g')
echo -e "To log into your D7 environment go to:"
echo -e ""
echo -e ' ==> '$(./scripts/uli-for-container.sh rdc_dev_d7)|sed "s/default/172.17.8.101:$D7PORT/g"
echo -e ""
echo -e "To log into your D8 environment go to:"
echo -e ""
echo -e ' ==> '$(./scripts/uli-for-container.sh rdc_dev_d8)|sed "s/default/172.17.8.101:$D8PORT/g"
echo -e ""
echo -e "To log into your Backdrop 1 environment go to:"
echo -e ""
echo -e " ==> http://172.17.8.101:$B1PORT and you will need to create a new"
echo -e '     site through the GUI for now due to'
echo -e '     https://github.com/backdrop-contrib/drush/issues/34.'
echo -e ""
echo -e "Replace 172.17.8.101 with the IP address you use to access your development server."
