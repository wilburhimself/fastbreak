<?php
require_once 'config.php';
require_once 'routes.php';
$router = new router($routes);
$router->delegate();
?>