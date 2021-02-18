# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
- Remove PHP dep from composer

## [2.1.0] - 18 February 2021
### Fixed
- Code compliance with Magento PHPCS
- Fixed issue with Guest Checkout email not being properly picked up

### Added
- Added additional event observer to guarantee blocking of guest orders
- Debugging with `var/log/yireo_salesblock.log` file

## [2.0.4] - 24 November 2020
### Fixed
- Issue when checkout is accessed directly
- Removed deprecated observer
- Set items_count to 0 as well to force "no-items" in cart

## [2.0.3] - 29 July 2020
### Added
- Magento 2.4 compatibility

## [2.0.2] - 15 July 2019
### Added
- `config.xml` with default settings
- Handy methods in rule repository
- Strict typing in rule repository and interface
- Integration test for rule repository
- Integration test for rule helper with simulation of IP and email matching

## [2.0.1] - 4 July 2019
### Added
- Add KeepAChangeLog support
- Move configuration to separate **Yireo** section

## [2.0.0] - November 2018
### Added
- Magento 2.3 compatibility
- Major rewrite to decouple rules from main module

## [1.0.1] - August 2018
### Added
- Create separate Configuration class instead of using Helper
- Add message in grid when module is disabled through setting

## [1.0.0] - August 2018
### Added
- Public release
- Composer support

## [0.0.3] - June 2019
### Added
- Beta testing on limited sites
- Improved exception handling

## [0.0.2] - November 2017
### Added
- Main migration of functionality
- Setup UiComponents
- Basic Integration Tests

## [0.0.1] - September 2017
### Added
- First draft
