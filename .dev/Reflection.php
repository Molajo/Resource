<?php
$class   = new ReflectionClass('Molajo\\Resource\\Proxy');
$methods = $class->getMethods();
foreach ($methods as $method) {
    echo '     * @covers  ' . $method->class . '::' . $method->name . PHP_EOL;
}
