<?php
    load_helper(array(
        'url', 'html', 'form', 'validation', 'dates',
    ));

    function __autoload($class_name) {
        if(file_exists(COREPATH.$class_name.'.php')) {
            require_once COREPATH.$class_name.'.php';
        }
        if(file_exists(CONTROLLERSPATH.$class_name.'.php')) {
            require_once CONTROLLERSPATH.$class_name.'.php';
        }
        if(file_exists(MODELSPATH.$class_name.'.php')) {
            require_once MODELSPATH.$class_name.'.php';
        }
    }
