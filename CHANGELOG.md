# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.2] - 18-05-2022
### Removed
- removed SVG supporting, move to separate module https://bitbucket.org/overdosedigital/module-magento-svg-support


## [1.5.1] - 16-04-2022
- Fix error linked with wrong declaration of Overdose\Core\Service\ImageResize::resizeFromThemes

## [1.5.0] - 16-04-2022
- Allow to upload 'svg' image for admin cms pages and blocks, products, page builder, favicon, logo, category page.
- Please DO NOT upload watermark svg image at admin > content > design > configuration > theme edit > Product Image Watermarks.

## [1.4.2] - 06-04-2022
### Added
- Update list of Custom CSP

## [1.4.1] - 22-02-2022
### Added
- Show "env labels" in sticky header.

## [1.4.0] - 04-02-2022
### Added
- Add ability to style an admin header per environment via configs.

## [1.3.3] - 02-12-2021
### Changed
- Update Google API config const name (fixed typo).

## [1.3.2] - 02-08-2021
### Fixed
- Fixed issue with "Admin Notification". Changed name email template.

## [1.3.1] - 29-07-2021
### Fixed
- Issue with textarea sources field. Now could be possible to enter values via coma and also from new line.

## [1.3.0] - 13-07-2021
### Added
- Added config "frame-ancestors" to `config.xml` because of not supported in Csp.  
- Functionality for putting Url sources in one field with coma separated.

### Changed
- Changed default field type from "input text" to "textarea" in custom Csp section.

## [1.2.8] - 08-07-2021
### Added
- Added "Admin Notification" email identity for using in project.

## [1.2.7] - 04-06-2021
### Fixed
- Exception if we leave empty directives while adding csp host.

## [1.2.6] - 23-04-2021
### Added
- "Check / Money Order" by IP: add default "NZ VPN" IP address.

## [1.2.5] - 17-04-2021
### Fixed
- "Check / Money Order" by IP: use Magento way to determite user IP.

## [1.2.4] - 16-04-2021
### Fixed
- "Check / Money Order" by IP: support by Magento Cloud.

## [1.2.3] - 09-04-2021
### Added
- Global config for "Google API key"
- File Constants.php with names of configs

## [1.2.2] - 17-03-2021
### Added
- CSP Managment: fix trailer comma.

## [1.2.1] - 16-03-2021
### Added
- CSP Managment: fix backand (helper) if empty options.

## [1.2.0] - 19-02-2021
### Added
- CSP Managment. Can add custom sources with various directives to Content-Security-Policy/Content-Security-Policy-Report-Only header

## [1.1.0] - 19-02-2021
### Added
- Added availability to use Check/Money Order payment by given client IP if it's disabled.
