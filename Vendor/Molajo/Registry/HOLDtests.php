<?php

/**
 *  Exists Unit Tests
 *
 *
 * $this->registry->createRegistry('Unit');
 * $this->registry->set('Unit', 'Test', 'Value');
 * echo $this->registry->get('Unit', 'Test');
 *
 * $results = $this->registry->exists('Unit');
 * if ($results === true) {
 * echo 'Success - Unit Registry Exists';
 * } else {
 * echo 'Failure';
 * }
 * $results = $this->registry->exists('NotUnit');
 *
 * if ($results === true) {
 * echo 'Failure';
 * } else {
 * echo 'Success - NotUnit Registry does not exist';
 * }
 */

/**
 *  Test Lock
 *
 * $this->registry->createRegistry('Unit');
 * $this->registry->set('Unit', 'Test', 'Value');
 * $this->registry->get('Unit', '*');
 * $this->registry->lock('Unit');
 * $this->registry->set('Unit', 'Test', 'Change Value'); //should fail
 * $this->registry->get('Unit', '*');
 */


/**
 * Testing for Create Registry
 *
 *   $this->registry->createRegistry('Unit');
 * $this->registry->set('Unit', 'Test', 'Value');
 * $this->registry->get('Unit', '*');
 * $this->registry->createRegistry('Unit');
 * $this->registry->get('Unit', '*');
 */


/** Unit testing
 *
 * $this->registry->createRegistry('Unit');
 * $this->registry->set('Unit', 'Test1', 'Value1');
 * $this->registry->set('Unit', 'Test2', 'Value2');
 * $this->registry->set('Unit', 'Dog4', 'Dog2');
 *
 * $this->registry->createRegistry('Dog');
 *
 * echo $this->registry->get('Unit', 'Test2');
 * $array = $this->registry->get('Unit', 'Test*');
 * var_dump($array);
 *
 * $this->registry->get('Unit', '*');
 * $array = $this->registry->get('Unit');
 * var_dump($array);
 *
 * $this->registry->get('*');
 * $this->registry->get('*', '*');
 * $this->registry->get('Pork');
 * $this->registry->get('Pork', 'X'); */


/**
 *  Exists Unit Tests
 *
 *
 * $this->registry->createRegistry('Unit');
 * $this->registry->set('Unit', 'Test', 'Value');
 * echo $this->registry->get('Unit', 'Test');
 *
 * $results = $this->registry->exists('Unit');
 * if ($results === true) {
 * echo 'Success - Unit Registry Exists';
 * } else {
 * echo 'Failure';
 * }
 * $results = $this->registry->exists('NotUnit');
 *
 * if ($results === true) {
 * echo 'Failure';
 * } else {
 * echo 'Success - NotUnit Registry does not exist';
 * }
 */
