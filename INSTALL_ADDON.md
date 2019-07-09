# Installation add-ons
Do make sure to install one or more of the following packages as well:

    composer require yireo/magento2-salesblock2-by-ip
    composer require yireo/magento2-salesblock2-by-email
    composer require yireo/magento2-salesblock2-by-geo

Without those additional packages, this extension does nothing. Once the package is installed, also enable the
corresponding module:

    bin/magento module:enable Yireo_SalesBlock2ByIp 
    bin/magento module:enable Yireo_SalesBlock2ByEmail 
    bin/magento module:enable Yireo_SalesBlock2ByGeo

