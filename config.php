<?php
define('SITEURL', 'http://localhost/ffr/'); // The URL for your application

define('DIRSEP', DIRECTORY_SEPARATOR);
$site_path = realpath(dirname(__FILE__).DIRSEP).DIRSEP;

define('SITEPATH', $site_path);
define('COREPATH', SITEPATH.'core'.DIRSEP);
define('CACHEPATH', SITEPATH.'cache'.DIRSEP);
define('CONTROLLERSPATH', SITEPATH.'controllers'.DIRSEP);
define('MODELSPATH', SITEPATH.'models'.DIRSEP);
define('VIEWSPATH', SITEPATH.'views'.DIRSEP);
define('HELPERSPATH', SITEPATH.'helpers'.DIRSEP);
define('CONFIGPATH', SITEPATH.'config'.DIRSEP);
define('FORMSPATH', SITEPATH.'forms'.DIRSEP);
define('LIBRARIESPATH', SITEPATH.'libraries'.DIRSEP);

// funciones principales
require_once COREPATH.'loader.php';
require_once CONFIGPATH . 'database.php';
require_once CONFIGPATH.'autoload.php';
?>