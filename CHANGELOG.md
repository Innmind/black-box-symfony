# Changelog

## 1.2.1 - 2025-01-19

### Fixed

- Support for PHP `8.4`

## 1.2.0 - 2023-12-02

### Added

- Support for `symfony/framework-bundle:~7.0`
- Support for `symfony/browser-kit:~7.0`
- Support for `symfony/http-foundation:~7.0`
- Support for `symfony/http-kernel:~7.0`

## 1.1.1 - 2023-09-02

### Fixed

- The kernel was not completely resetted
- The client is no longer kept in memory as it conflicts when the kernel is shutdown

## 1.1.0 - 2023-09-02

### Added

- `Innmind\BlackBox\Symfony\Bundle\FrameworkBundle\Test\WebTestCase`
