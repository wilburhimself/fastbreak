<?php

use Core\Router;
use Config\Database;

const DS = DIRECTORY_SEPARATOR;
const BASEPATH = __DIR__ . DS;
const COREPATH = BASEPATH . 'core' . DS;
const CONTROLLERSPATH = BASEPATH . 'controllers' . DS;
const MODELSPATH = BASEPATH . 'models' . DS;
const VIEWSPATH = BASEPATH . 'views' . DS;
const HELPERSPATH = COREPATH . 'helpers' . DS;
const LIBRARIESPATH = BASEPATH . 'libraries' . DS;

require_once BASEPATH . 'routes.php';

/**
 * Autoload classes
 *
 * @param string $className
 * @return void
 */
spl_autoload_register(function (string $className): void {
    // project-specific namespace prefix
    $prefix = 'Core\\';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relativeClassName = substr($className, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = COREPATH . str_replace('\\', DS, $relativeClassName) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

$router = new Router($routes);
$router->route();
