#!/bin/bash
sourcedir=/module_source
webdir=/var/www/html
module=Yireo_SalesBlock2
composer_name=magento2-salesblock2

# Test before proceeding
cd $webdir
test -f bin/magento || exit 1
test -f app/etc/env.php || exit 1
test -d dev/tests/integration || exit 1

# Setup integration testing
echo "Copy files for integration tests"
cp $sourcedir/docker/files/install-config-mysql.php $webdir/dev/tests/integration/etc
cp $sourcedir/docker/files/phpunit.xml $webdir/dev/tests/integration

# Setup this extension
echo "Installing this extension"
mkdir -p $webdir/app/code/Yireo
ln -s /module_source $webdir/app/code/Yireo/SalesBlock2

# Use the module
echo "Enabling this extension"
cd $webdir
bin/magento module:enable ${module}
bin/magento setup:upgrade

# Run tests
echo "Run integration tests"
cd $webdir
bin/magento dev:tests:run integration

