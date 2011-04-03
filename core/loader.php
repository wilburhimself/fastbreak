<?php
class Loader {
    private $classes = array();
    public function __get($class) {
        if (!isset($this->classes[$class])) {
            $this->classes[$class] = new $class;
        }
        return $this->classes[$class];
    }
}
function helper($helper) {
    if (is_array($helper)) {
        foreach ($helper as $h) {
            if (file_exists(COREPATH.'helpers'.DIRECTORY_SEPARATOR.$h.'_helper.php')) {
                require_once COREPATH.'helpers'.DIRECTORY_SEPARATOR.$h.'_helper.php';
            } else {
                require_once HELPERSPATH.$h.'_helper.php';
            }
        }
    } else {
        require_once HELPERSPATH.$helper.'_helper.php';
    }
}
function model($model) {
    require_once MODELSPATH.$model.'.php';
}
function library($library) {
    if (is_array($library)) {
        foreach ($library as $l) {
            if (is_dir(LIBRARIESPATH.$l)) {
                require_once(LIBRARIESPATH.$l.'.php');
            } else {
                die('Failed to load library '.$l);
            }
        }
    } else {
        require_once(LIBRARIESPATH.$library.'.php');
    }
}
// obtiene la conexi�n existente a la base de datos
// si no existe la crea!
function get_db() {
    global $db;
    if(!isset($db)) {
        $db = new mysql_db;
    }
    return $db;
}

function load_view($view, $data=array()) {
    $t = new Template($view);
    $t->load_vars($data);
    return $t->render();
}

function partial_collection($data, $view, $extra_vars=array()) {
    $output = '';
    $on = isset($extra_vars['object_name']) ? $extra_vars['object_name'] : 'object';
    $current = '';
    $current_reverse = sizeof($data);
    foreach ($data as $$on) {
        $current++;
        $$on->load_related();
        $t = new Template($view);
        $t->object = $$on;
        $t->extra = $extra_vars;
        $t->current = $current;
        $t->current_reverse = $current_reverse;
        $output .= $t->render();
        $current_reverse--;
        if (isset($extra_vars['limit']) and $extra_vars['limit'] == $current) break;
    }
    return $output;
}