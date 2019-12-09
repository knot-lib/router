<?php
/** @var Calgamo\Router\Router $router */
global $router;

$router->route('/','GET');
$router->route('/','POST');
$router->route('/product/sample/product_AAA','GET');
$router->route('/product/sample/product_BBB','POST');
$router->route('/download/123','GET');
$router->route('/math/PI/3.1415926535','GET');
$router->route('/fruits/apple/banana/mango/kiwi','GET');
$router->route('/asdasd','GET');
