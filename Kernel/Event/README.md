**NOT COMPLETE**

=======
Events
=======

## Events

A certain point in time in a process that a *requester* schedules with a *dispatcher*
 which then delegates management of the event to an *event dispatcher* responsible to
  initiate registered *listeners* and return the collective results to the *dispatcher*
  which, in turn, provides the *requester* the results.

### Requester

A requester can be any part of the application (i.e., a Controller, a Plugin, a Template, etc.)
 which requests the scheduling of of an event with dispatcher, providing information with the
   request and using the results returned.

```php

    // Requester schedules an Event with the Dispatcher and uses the results.
    $results = $dispatcher->schedule('event', $options);

```

### Dispatcher

The Dispatcher's job is to keep track of registrations for events by listeners so that
it can respond with a list of listeners when a Requester schedules the event by
delegating the management of the event activities to an Event Dispatcher.


```php

    // Dispatcher delegates Event Management to Event Dispatcher, passes results back to Requester
    $results = delegate($event, getListeners(event), EventDispatcher);

```

### Event Dispatcher

The Event Dispatcher is delegated the responsibility of managing the event by the Dispatcher.
This means looping through a list of registered Listeners registered, authenticating and
authorising the user with the listener,
registered callback and then returning the collective results to the Dispatcher. Each
Event Dispatcher handles a certain type of event and will have different methods.


```php

    // Event Dispatcher initiates the callback from each listener one at a time
    for each ($listeners as $listener) {
        // orders listeners by priority
        // authenticates
        // authorises
        // initiate the listener
        // determines if it should continue or stop
        $this->listener->checkUserAuthorisation($event_name, $user);
        $class = $listener->namespace;
        $listener = new $class($listener->options);
        $results = $listener->callback;
    }

```

### Listener

As with the Requester, the Listener can also be any part of the application. Most of the time,
Listeners will be Plugins, but can also be a Controller, or the User object, etc.
The Listeners involvement in the Event process is simple. It registers to listen to an
 event with the Dispatcher. Very specifically, "listening" means when the Event is scheduled,
 a callback provided by the Listeners should be invoked. The Listener then will finish the
 initiated process and pass back the results to the Event Dispatcher.

```php

    $this->dispatcher->register($event_name, $priority, $callback);

    $this->dispatcher->unregister($event_name);

    $this->dispatcher->register($event_name, $priority, $function ($whatever) use ($this) {
           // do whatever;
    };

    $this->dispatcher->registerLocation($folder);

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
        "Molajo/Pagination": "1.*"
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
 * Use github to submit [pull requests](https://github.com/Molajo/Pagination/pulls) and [features](https://github.com/Molajo/Pagination/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
