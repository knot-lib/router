<?php
function global_dispatcher($path, $vars, $event)
{
    call_user_func('on_'.$event, $path, $vars, $event);
}
function on_internal_server_error($path, $vars, $event)
{
    echo "[$path] 500 Internal Server Error" . PHP_EOL;
}
function on_not_found($path, $vars, $event)
{
    echo "[$path] 404 Not Found" . PHP_EOL;
}
function on_home($path, $vars, $event)
{
    echo "[$path] home" . PHP_EOL;
}
function on_product($path, $vars, $event)
{
    $product_id = $vars['product_id'] ?? null;
    $type = gettype($product_id);
    echo "[$path] product product_id: $product_id($type)" . PHP_EOL;
}
function on_download($path, $vars, $event)
{
    $file_id = $vars['file_id'] ?? null;
    $type = gettype($file_id);
    echo "[$path] download file_id: $file_id($type)" . PHP_EOL;
}
function on_math($path, $vars, $event)
{
    $pi = $vars['pi'] ?? null;
    $type = gettype($pi);
    echo "[$path] math file_id: $pi($type)" . PHP_EOL;
}
function on_fruits($path, $vars, $event)
{
    $fruits = $vars['fruits'] ?? null;
    $type = gettype($fruits);
    echo "[$path] fruits : " . implode(' ,',$fruits) . "($type)" . PHP_EOL;
}

