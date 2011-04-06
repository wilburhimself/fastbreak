<?php
define('DEFAULT_CONTROLLER', 'Index');
$routes = array(
    'home' => array(
        'home' => ''
    )
);

map_route("home", '');
// Define Resourceful models using the following syntax
// map_resource(MODELNAME);