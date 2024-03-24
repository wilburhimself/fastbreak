<?php
    helper([
        'url', 'html', 'form', 'validation', 'dates', 'attachments'
    ]);

    function autoload($class_name) {
        $class_name = strtolower($class_name);
        if(file_exists(COREPATH.$class_name.'.php')) {
            require_once COREPATH.$class_name.'.php';
        }
        if(file_exists(CONTROLLERSPATH.$class_name.'.php')) {
            require_once CONTROLLERSPATH.$class_name.'.php';
        }
        if(file_exists(MODELSPATH.$class_name.'.php')) {
            require_once MODELSPATH.$class_name.'.php';
        }
        if(file_exists(FORMSPATH.$class_name.'.php')) {
            require_once FORMSPATH.$class_name.'.php';
        }
    }
