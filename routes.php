<?php

use Core\Helpers\UrlHelper;

const DEFAULT_CONTROLLER = 'Index';
$routes = array(
    'home' => array(
        'home' => ''
    )
);

UrlHelper::map_route("home", '');
// Define Resourceful models using the following syntax
// map_resource(MODELNAME);