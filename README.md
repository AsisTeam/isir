# AsisTeam > ISIR (Czech insolvency registry)

[![Build Status](https://img.shields.io/travis/com/AsisTeam/isir.svg?style=flat-square)](https://travis-ci.com/AsisTeam/isir)
[![Licence](https://img.shields.io/packagist/l/AsisTeam/isir.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/isir)
[![Downloads this Month](https://img.shields.io/packagist/dm/AsisTeam/isir.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/isir)
[![Downloads total](https://img.shields.io/packagist/dt/AsisTeam/isir.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/isir)
[![Latest stable](https://img.shields.io/packagist/v/AsisTeam/isir.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/isir)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

## Credits

The development is under [AsisTeam s.r.o.](https://www.asisteam.cz/).
Feel free to use. Your contributions are very welcome. Feel free to publish pull requests.

<img src="https://www.asisteam.cz/img/logo.svg" width="200" alt="Asisteam" title="Asisteam"/>

## Overview

<img src="https://isir.justice.cz/isir/common/images/leftHeader_new.JPG" width="200" alt="Insolvencni rejstrik logo" title="Insolvencni rejstrik"/>


This PHP API wrapper allows you to check whether there is some insolvency record for given person/company in Czech insolvency registry.

- [InsolvencyChecker - detailed usage documentation](https://github.com/AsisTeam/isir/blob/master/.docs/README.md)

## Install

```
composer require asisteam/isir
```

## Versions

| State       | Version | Branch   | PHP      |
|-------------|---------|----------|----------|
| stable      | `^1.0`  | `master` | `>= 7.1` |


## Tests

Check code quality and run tests
```
composer phpstan-install
composer ci
```

or separately

```
composer qa
composer phpstan-install
composer phpstan
composer tests
```

Note: integration tests are skipped as they do request to real api endpoints.
The validity of assertions in integration tests may change too.

## Authors

<table>
  <tbody>
    <tr>
      <td align="center">
        <a href="https://github.com/kedlas">
            <img width="100" height="100" src="https://avatars3.githubusercontent.com/u/3510893?s=460&v=4&s=150">
        </a>
        <br/>
        <a href="https://github.com/kedlas">Tomas Sedlacek</a></p>
      </td>
      <td align="center">
        <a href="https://github.com/holantomas">
            <img width="100" height="100" src="https://avatars3.githubusercontent.com/u/5030499?s=460&v=4&s=150">
        </a>
        <br/>
        <a href="https://github.com/holantomas">Tomas Holan</a></p>
      </td>
    </tr>
  </tbody>
</table>


