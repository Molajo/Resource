=======
Number to Text Utility
=======

[![Build Status](https://travis-ci.org/Molajo/NumberToText.png?branch=master)](https://travis-ci.org/Molajo/NumberToText)

Converts a numeric value up to a 999 quattuordecillion to translatable term.

## Basic Usage ##

1. Instantiate the `Translate` class, passing in the desired value for `$locale`.
2. Instantiate the `NumberToText` class, injecting the `Translation` instance.
3. Use the `NumberToText` class's `convert` method, passing in a numeric value to get the text equivalent.

```php
    use Molajo\NumberToText\Translations\Translate;
    use Molajo\NumberToText\Utility as NumberToText;

    $locale_instance  = new Translate('en-GB');
    $numberToText = new NumberToText($locale_instance);

    echo $numberToText->convert(1000003);

```

**Note:** Do not use negative numbers, commas or decimals.

## Translations ##

To translate the `NumberToText` class into your preferred language,
 copy the `Translations\enGB.php` file to the same folder, giving it
 a file and class name for the appropriate locale. Translate the
  values array. When instantiating the Translate
 class, pass in the locale.

 If you want to share your translation with others, fork `NumberToText` and issue a pull request
 for your translation. Your contributions are much appreciated.

```php
    use Molajo\Translations\Translate;
    use Molajo\NumberToText\Utility as NumberToText;

    $locale_instance  = new Translate('en-GB');
    $numberToText = new NumberToText($locale_instance);

    echo $numberToText->convert(1000003);

```

## Install using Composer from Packagist

### Step 1: Install composer in your project

```php
    curl -s https://getcomposer.org/installer | php
```

### Step 2: Create a **composer.json** file in your project root

```php
{
    "require": {
        "Molajo/NumberToText": "1.*"
    }
}
```

### Step 3: Install via composer

```php
    php composer.phar install
```

## Requirements and Compliance
 * PHP framework independent, no dependencies
 * Requires PHP 5.3, or above
 * [Semantic Versioning](http://semver.org/)
 * Compliant with:
    * [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) and [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) Namespacing
    * [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) Coding Standards
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * Author [AmyStephen](http://twitter.com/AmyStephen)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * Listed on [Packagist] (http://packagist.org) and installed using [Composer] (http://getcomposer.org/)
 * Use github to submit [pull requests](https://github.com/Molajo/NumberToText/pulls) and [features](https://github.com/Molajo/NumberToText/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
