# Changelog

All Notable changes to `payu` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Version - 4.0.0
- Laravel 7 Support

## Date - 2018-03-13

### Added
- Laravel 6 Support
- PayuMoney Verification Functionality.
- Package Autodiscovery.

### Changed
- The Verificaion return response. It now returnes the list of `PayuPayment` instances.

### Fixed
- Code Standards.
- Bug Fixes.

### Removed
- Older Verificaion return response.

## Earlier

### Added
- Payment Verify Functionality.
- Simple Polymorphic Relation.
- More Payment API to make life easier.

### Deprecated
- Nothing

### Fixed
- Bugs about Callback URL.
- Migration File Bug fixed.
- Bugs about CSRF token.
- Other Code Improvements.

### Removed
- Support for Laravel 5.1 due to lack of `web` middleware.

### Security
- Security patches.
