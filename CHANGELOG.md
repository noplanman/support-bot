# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

Exclamation symbols (:exclamation:) note something of importance e.g. breaking changes. Click them to learn more.

## [Unreleased]
### Added
- Code checkers to ensure coding standard.
### Changed
- Bumped Manager to 1.5.
- Logging is now decoupled with custom Monolog logger.
### Deprecated
### Removed
### Fixed
### Security

## [0.2.0] - 2019-06-01
### Changed
- Bumped Manager to 1.4
### Fixed
- Only post release message when a new release is actually "published". (#25)

## [0.1.0] - 2019-04-15
### Added
- First minor version that contains the basic functionality.
- Simple logging of incoming webhook requests from GitHub and Travis-CI.
- Post welcome messages to PHP Telegram Bot Support group.
- Post release announcements to PHP Telegram Bot Support group. (#17)
- Extended `.env.example` file.

[Unreleased]: https://github.com/php-telegram-bot/support-bot/compare/master...develop
[0.2.0]: https://github.com/php-telegram-bot/support-bot/compare/0.1.0...0.2.0
