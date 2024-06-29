# LeeShan87/reactphp-multi-loop

[![CI status](https://github.com/LeeShan87/reactphp-multi-loop/workflows/CI/badge.svg)](https://github.com/LeeShan87/reactphp-multi-loop/actions)
[![installs on Packagist](https://img.shields.io/packagist/dt/leeshan87/reactphp-multi-loop?color=blue&label=installs%20on%20Packagist)](https://packagist.org/packages/LeeShan87/reactphp-multi-loop)

Manage multiple LoopInterfaces easily when writing tests.

## Quickstart example

```php
$loop1 = Factory::create();
$loop1->futureTick(function(){
    echo 'hello ';
});
MultiLoop::addLoop($loop1, 'loop1');
$loop2 = Factory::create();
$loop2->futureTick(function (){
    echo 'world!';
});
MultiLoop::addLoop($loop1, 'loop2');
MultiLoop::tickAll();
```

## Install

The recommended way to install this library is [through Composer](https://getcomposer.org).
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

This will install the latest supported version:

```bash
$ composer require leeshan87/reactphp-multi-loop:^0.1
```

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

This project aims to run on any platform and thus does not require any PHP
extensions and supports running on legacy PHP 5.3 through current PHP 8+.
It's _highly recommended to use PHP 7+_ for this project.

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org):

```bash
$ composer install
```

To run the test suite, go to the project root and run:

```bash
$ php vendor/bin/phpunit
```

## License

MIT
