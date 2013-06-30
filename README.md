=======
Resource Resources (will be renamed to Resources)
=======

[![Build Status](https://travis-ci.org/Molajo/Resources.png?branch=master)](https://travis-ci.org/Molajo/Resources)

*Resource Resources* provides PHP applications with a generalized approach for locating and handling
    file and folder resources using URI namespaces.

## Basic Usage ##

Instead of requiring the application know the location of file and folder resources,
 so that it can use those values when requesting filesystem resources:

```php
<?

```

The application is able to interact with the filesystem in a more generic way using URI namespaces.

```php
<?

```

The **Resources** translates the URI into a filesystem path for use of that value with filesystem operations:

```php
<?

```
The **URI namespace**

##Instantiate the Resource Resources Adapter##

### Example: Class Loader

- autoload
- addPath

Registration is automatic if class loading is activated.

get(value);



####Example: Get File location####
```php
    $locator = new Molajo\Resources\Adapter();
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
    $adapter = new Molajo\Resources\Adapter();

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
    $adapter = new Molajo\Resources\Adapter();

    try {
        $filtered = $adapter->filter($field_name, $field_value, $loader_type_chain, $options);

    } catch (Exception $e) {
        //handle the exception
    }

    // Success!
    echo $filtered;
```

### Overrides

```php

$sep = '\\';
$mapping = array(
   '\\Acme\\Blog\\' => 'src/blog',
   '\\Acme\\Demo\\Parser.php' => 'src/Parser.php',
);

echo match_path('\\Acme\\Blog\\ShowController.php', $mapping, $sep);
// => "src/blog/ShowController.php"

echo match_path('\\Acme\\Demo\\Parser.php', $mapping, $sep);
// => "src/Parser.php"

    $adapter = new Molajo\Resources\Adapter();

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
        "Molajo/Resources": "1.*"
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
 * Use github to submit [pull requests](https://github.com/Molajo/Resources/pulls) and [features](https://github.com/Molajo/Resources/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
