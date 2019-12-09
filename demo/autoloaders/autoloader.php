<?php
spl_autoload_register(function($class) {
    if (strpos($class, 'Calgamo\\Router\\') === 0) {
        $name = substr($class, strlen('Calgamo\\Router\\'));
        $paths = explode('\\', $name);
        array_unshift($paths,'src');
        require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $paths) . '.php';
    }
});
