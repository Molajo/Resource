=======
Resources
=======

[![Build Status](https://travis-ci.org/Molajo/Resources.png?branch=master)](https://travis-ci.org/Molajo/Resources)

*Resources* provides PHP applications with a generalized approach for locating and handling
    file and folder resources using URI namespaces.

## Basic Principles

With *Resources*, applications interact with filesystem resources using a URI namespace, rather than specifying
 file and folder names.

 This approach provides two important benefits:

 First, namespacing generalizes location
  information so that the application no longer requires hardcoded physical locations. The *Resources* package
   locates the physical location based on the URI namespace resulting in flexibility as to storage location and
   facility.

   Second, the *Resources* package uses an adapter handler to process process the application request
   for the resource. In this sense, the treatment required for each file request can be customized based on the
   URI scheme. For example, the handler for PHP classes can defined as an SPL autoloader while the handler for
   an XML configuration file might return an XML string read from the located file. In the case of a model, one
   might return a Read Controller, instantiated with all dependencies injected and ready for queries. A request
   for an image file might return an image, resized, as needed. The possibilities are endless.

## Examples of how Molajo uses Resources

### Class Handler

The *Resource* Class Handler is defined as an `SPL Autoloader`. PHP passes any requests to locate a class file
to this method which attempts to locate the file and includes it, if found.

### XML Handler

The *Resource* XML Handler locates and returns the path for a specified resource

```php
<?php
$field = $this->resource->get('xml:///Molajo/Field/Author.xml');

echo $field;
```

Results:
```html
<field name="author" type="char" null="0" default=" "/>
```

### Query Handler

The *Resource* Query Handler locates the model XML file, processes the extend and include
statements, generating the full model definition, instantiates the required Model class,
injecting required dependencies, injects the Model instance and other required dependencies
into the necessary Controller, and then passes the Controller instance back to
the application for processing.

```php
<?php
$controller = $this->dependencies['Resources']->get(
    'query:///Molajo/Datasource/CatalogTypes.xml',
    array('Parameters' => $parameters)
);

$catalog_types = $controller->getData();

foreach ($catalog_types as $item) {
    echo $item->id; // you get the picture
}
```

### Other Handlers

The *Resource* Package, as used in [Molajo](https://github.com/Molajo/Standard), has URI Handlers for Themes,
Views, JS, CSS, Files and Folders, and so on. Work on other usage types, such as Constants, Functions, and
Interfaces is underway. While *Resources* is still a work in progress, it is an integral part of the *Molajo*
application.

##Resource Definitions##

The first step is determining what resources and must be accessible to your application. Define the scheme
and request structure. Typical resources applications use include: classes, configuration files, CSS, JS, images, etc. The *Resources*
package provides Handlers for these typical use case.

All [Schemes](https://github.com/Molajo/Resources/blob/master/Files/SchemeArray.json) must be defined and handlers
created for each scheme:

```json
    "css": {
        "Name": "css",
        "RequireFileExtensions": ".css",
        "Handler": "Css"
    },
```

Next, all application resources should be mapped to namespace prefixes and inclusion and exclusion criteria
for that namespace:

```json

    "Molajo\\Administration": {
        "include_folders": [
            "Application\/Administration\/"
        ],
        "extension_level": 3,
        "exclude_folders": [
            ".dev",
            ".travis.yml",
            ".DS_Store",
            ".git",
            ".",
            "..",
            ".gitattributes",
            ".gitignore"
        ],
        "include_file_extensions": "",
        "exclude_file_extensions": "",
        "include_file_names": "",
        "exclude_file_names": "",
        "tags": []
    },
```

If overrides are required, define a generalized
[prioritization](https://github.com/Molajo/Resources/blob/master/Files/PriorityArray.json)
approaches for selecting which file is needed:

```json
    "User",
    "Tag",
    "Group",
    "Category",
    "Theme",
    "Plugin",
    "Menuitem",
    "Resource",
    "Wrap",
    "Template",
    "Page",
    "Application",
    "Site",
    "Sites",
    "System"
]
```

From that information, [resource maps](https://github.com/Molajo/Resources/blob/master/Files/ResourceMap.json)
can be compiled for performance purposes, if desired, although 100% dynamic resource location is supported.

This process also creates compiled data used by *Molajo's IoCC package* for [identifying concrete class dependencies]
(https://github.com/Molajo/Resources/blob/master/Files/ClassDependencies.json)
and for [mapping concretes to interfaces](https://github.com/Molajo/Resources/blob/master/Files/InterfaceMap.json).

###IoCC Services for Resources

Following are examples of how Molajo instantiates the *Resources* class and handlers:

* At start-up, the [Resources DI Injector](https://github.com/Molajo/Standard/blob/master/Kernel/Service/Resources/ResourcesInjector.php)
 instantiates the base handlers, and then injects those instances into constructed adapter.
* In some cases, a URI handler cannot be constructed until it's dependencies are available. As an example,
after the database is connected, the Query Handler can constructed and injected into the Resource Handler.
The [Resources Query DI Injector](https://github.com/Molajo/Standard/blob/master/Kernel/Service/Resourcesquery/ResourcesqueryInjector.php)
does just that once the database connection is available.

## Status

This is just a general description of the *Resources* package, feedback is welcome. Remember
it's still a work in progress and not ready for production use.
