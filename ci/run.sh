#!/bin/bash
sourcedir=/source

cp /source/magento/integration-tests/install-config-mysql.php $webdir/dev/tests/integration/etc
cp /source/magento/integration-tests/phpunit.xml $webdir/dev/tests/integration/etc

chmod 700 ~/.ssh
cp ~/ssh/id* ~/.ssh
chmod 600 ~/.ssh/id*

#cp composer.json /var/www/html
#cd /var/www/html
#composer update

composer config repositories.yireo-salesblock2 vcs git@gitlab.yireo.com:magento2-extensions/Yireo_SalesBlock2.git
composer require yireo/magento2-salesblock2:dev-master

bin/magento dev:tests:run integration
