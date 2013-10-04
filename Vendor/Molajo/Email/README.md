=======
Email Package
=======

[![Build Status](https://travis-ci.org/Molajo/Email.png?branch=master)](https://travis-ci.org/Molajo/Email)

Email Services API for PHP applications enabling interaction with multiple Email types
(ex., PHP Mail, PHP Mailer, Swiftmailer, etc.).

## At a glance ...

1. Instantiate a Mail Handler.
2. Instantiate the Adapter, injecting it with the Handler.
3. Set mail parameters.
4. Send mail.

```php

    // 1. Instantiate an Email Handler.
    $options                     = array();
    $options['mailer_transport'] = 'mail';
    $options['site_name']        = 'Sitename';
    $options['Fieldhandler']    = new Fieldhandler();

    $class   = 'Molajo\\Email\\Handler\\PhpMailer';
    $handler = new $class($options);

    // 2. Instantiate the Adapter, injecting it with the Handler.
    $class         = 'Molajo\\Email\\Adapter';
    $this->adapter = new $class($handler);

    // 3. Set email parameters
    $this->adapter->set('to', 'AmyStephen@gmail.com,Fname Lname');
    $this->adapter->set('from', 'AmyStephen@gmail.com,Fname Lname');
    $this->adapter->set('reply_to', 'AmyStephen@gmail.com,FName LName');
    $this->adapter->set('cc', 'AmyStephen@gmail.com,FName LName');
    $this->adapter->set('bcc', 'AmyStephen@gmail.com,FName LName');
    $this->adapter->set('subject', 'Welcome to our Site');
    $this->adapter->set('body', 'Stuff goes here');
    $this->adapter->set('mailer_html_or_text', 'text');

    // 4. Send Email.
    $this->adapter->send();

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
        "Molajo/Email": "1.*"
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
 * Use github to submit [pull requests](https://github.com/Molajo/Email/pulls) and [features](https://github.com/Molajo/Email/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
