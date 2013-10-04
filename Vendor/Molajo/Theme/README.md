**NOT COMPLETE**

=======
Document Package
=======

[![Build Status](https://travis-ci.org/Molajo/Asset.png?branch=master)](https://travis-ci.org/Molajo/Asset)

Theme processing which parses output for delimited tags, verifies user permissions, pushes input data into Templates,
  and then replaces delimited tags with rendered output. Processing continues recursively until no additional
  tags are found in the rendered output. Adapters are available for the following Templating Engines:
  Molajo Theme Engine, Mustache, and Twig.

## System Requirements ##

* PHP 5.3.3, or above
* [PSR-0 compliant Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
* PHP Framework independent
* [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

http://phly_mustache.readthedocs.org/en/latest/documentation.html

    <define name="JS_DECLARATIONS_LITERAL" value="JsDeclarations"/>
    <define name="JS_DECLARATIONS_DEFER_LITERAL" value="JsDeclarationsDefer"/>
    <define name="JS_DEFER_LITERAL" value="Jsdefer"/>
    <define name="JS_LITERAL" value="Js"/>
