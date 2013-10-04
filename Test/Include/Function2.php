<?php
$function2 = function () use ($frontController) {

    echo '<br />Function 2 echo: ' . $frontController->injected_value;

    return $frontController;
};
