<?php

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function($class)
{
    if (strpos($class, 'KnotLib\\Router\\Test\\') === 0) {
        $name = substr($class, strlen('KnotLib\\Router\\Test\\'));
        $name = array_filter(explode('\\',$name));
        $file = __DIR__ . '/include/' . implode('/',$name) . '.php';
        if (is_file($file)){
            /** @noinspection PhpIncludeInspection */
            require_once $file;
        }
    }
});
