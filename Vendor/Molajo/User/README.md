=======
User
=======

[![Build Status](https://travis-ci.org/Molajo/User.png?branch=master)](https://travis-ci.org/Molajo/User)

Instantiates a User Object, providing access to User Profile and other System data, Authentication (Database, HTTP, and
"Offline Access" testing. Also enables access to Registration processes, Login, Logout, Remember Me, Remind Me,
User Email and Assets, and Authorisation processes.

## System Requirements ##

* PHP 5.3.3, or above
* [PSR-0 compliant Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
* PHP Framework independent
* [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

### Installation

#### Install using Composer from Packagist

**Step 1** Install composer in your project:

```php
    curl -s https://getcomposer.org/installer | php
```

**Step 2** Create a **composer.json** file in your project root:

```php
{
    "require": {
        "Molajo/User": "1.*"
    }
}
```

**Step 3** Install via composer:

```php
    php composer.phar install
```

About
=====

Molajo Project observes the following:

 * [Semantic Versioning](http://semver.org/)
 * [PSR-0 Autoloader Interoperability](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
 * [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
 and [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * [Packagist] (https://packagist.org)


Submitting pull requests and features
------------------------------------

Pull requests [GitHub](https://github.com/Molajo/User/pulls)

Features [GitHub](https://github.com/Molajo/User/issues)

Author
------

Amy Stephen - <AmyStephen@gmail.com> - <http://twitter.com/AmyStephen>
License
-------

**Molajo User** is licensed under the MIT License - see the `LICENSE` file for details

More Information
----------------
- [Extend](https://github.com/Molajo/User/blob/master/.dev/Doc/extend.md)
- [Install](https://github.com/Molajo/User/blob/master/.dev/Doc/install.md)
