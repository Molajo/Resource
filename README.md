=======
Resource Locator
=======

[![Build Status](https://travis-ci.org/Molajo/Locator.png?branch=master)](https://travis-ci.org/Molajo/Locator)

*Resource Locator* provides PHP applications with a generalized approach for locating and handling
    file and folder resources for tasks like class loading, file location, css accumulation
     using URI namespaces.

## Basic Usage ##



###Locate Resource###


###Instantiate the Resource Locator Adapter###


####Example: Get File location####
```php
    $locator = new Molajo\Locator\Adapter();
    $resource = $adapter->get($uri);

    echo $uri;
```

####Example: SPL Class Loader####

Normal CLass commands invoke the Class Handler

```php
    $instance = new $class();
```

##Application Setup Process##

####Planning####

What resources must be accessible to your application?

Typical resources include: classes, configuration files, CSS, JS, etc.

For each you'll need to define:
- scheme
- namespace - resource location pairs
- handling requirements

Create Resource Map



###Define Scheme###

```php

    try {
        $filtered = $adapter->filter($field_name, $field_value, $loader_type_chain, $options);

    } catch (Exception $e) {
        //handle the exception
    }

    // Success!
    echo $filtered;
```

###Define Namespaces###

```php
    $adapter = new Molajo\Locator\Adapter();

    try {
        $filtered = $adapter->filter($field_name, $field_value, $loader_type_chain, $options);

    } catch (Exception $e) {
        //handle the exception
    }

    // Success!
    echo $filtered;
```

###Create Resource Map###

```php
    $adapter = new Molajo\Locator\Adapter();

    try {
        $filtered = $adapter->filter($field_name, $field_value, $loader_type_chain, $options);

    } catch (Exception $e) {
        //handle the exception
    }

    // Success!
    echo $filtered;
```

###Create Resource Map###

```php
    $adapter = new Molajo\Locator\Adapter();

    try {
        $filtered = $adapter->filter($field_name, $field_value, $loader_type_chain, $options);

    } catch (Exception $e) {
        //handle the exception
    }

    // Success!
    echo $filtered;
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
        "Molajo/Locator": "1.*"
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
 * Use github to submit [pull requests](https://github.com/Molajo/Locator/pulls) and [features](https://github.com/Molajo/Locator/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
