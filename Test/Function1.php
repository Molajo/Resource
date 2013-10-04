<?php
$function1 = function () use ($frontController) {

    echo '<br />Function 1 echo: ' . $frontController->injected_value;

    return $frontController;
};
