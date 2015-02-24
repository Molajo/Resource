<?php
$class   = new ReflectionClass('Molajo\\Resource\\ResourceMap');
$methods = $class->getMethods();
foreach ($methods as $method) {
    echo '     * @covers  ' . $method->class . '::' . $method->name . PHP_EOL;
}
