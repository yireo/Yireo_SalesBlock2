# Yireo SalesBlock2 for Magento 2

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/magento2-salesblock2)
<!-- badges.specs.end -->


Prevent Magento 2 orders from being placed, based on specific rules.

Also see [yireo.com/software/magento-extensions/salesblock2](https://www.yireo.com/software/magento-extensions/salesblock2)

## Installation 
To install this module, use composer:
```bash
composer require yireo/magento2-salesblock2
```

Afterwards, enable the module and run the setup upgrade, to make sure the database table is properly created:
```bash
bin/magento module:enable Yireo_SalesBlock2
bin/magento setup:upgrade
```

Next, install (and enable) one or more of the additional plugins. Without these plugins, the SalesBlock2 extension will not work:

- [github.com/yireo/Yireo_SalesBlock2ByIp](https://github.com/yireo/Yireo_SalesBlock2ByIp)
- [github.com/yireo/Yireo_SalesBlock2ByEmail](https://github.com/yireo/Yireo_SalesBlock2ByEmail)
- [github.com/yireo/Yireo_SalesBlock2ByGeo](https://github.com/yireo/Yireo_SalesBlock2ByGeo) (also requires the PHP GeoIP module)

## Usage
Navigate in the Magento Admin Panel to the Store Configuration to enable this module. Then, navigate in the Magento Admin Panel to **Sales > Sales Block Rules** to configure a rule. A rule consists of the following parts:

- **Enable**: Yes or no.
- **Label**: For managing things in your backend.
- **Conditions**: One or more conditions that **all** need to be met, before the rule is a match. The conditions are activated only through additional modules (see above). For instance, you could say that you are blocking sales for a person, coming from a certain IP range **and** using a specific email address.
- **Frontend label**: The message to display to the blocked customer on the frontend.
- **Frontend text**: An additional explanation to display to the blocked customer.

For additional details, see the READMEs of all submodules.

## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2/compile.yml?label=compile)
<!-- badges.test.end -->
