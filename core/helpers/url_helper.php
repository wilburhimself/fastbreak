<?php

// get site base url
function base_url($link=null) {
    return SITEURL.$link;
}

// redirecciona al usuario a una p�gina determinada

function redirect($url) {
    $u = is_object($url) ? $url->permallink() : $url;
    return header('Location: '.base_url().$u);
}

function redirect_to($controller, $action, $params=null) {
    return header('Location: '.base_url().construct_url($controller, $action, $params));
}

function redirect_back_or_default($url=null) {
    $default = isset($url) ? $url : base_url();
    if ($_SERVER['HTTP_REFERER']) {
        header('location:'.$_SERVER['HTTP_REFERER']);
    } else {
        header('location:'.$default);
    }
}

// crea una direccion que se acopla a los standares de nuestra aplicacion
function construct_url($controller=null, $action=null, $params=null) {
    $attributes = array('htmlclass', 'htmlid', 'htmlrel', 'htmltitle');
    $output = '';
    if(!isset($controller) or $controller == '') {
        $controller = 'index';
    } else {
        $output .= $controller.'/';
        if(!isset($action) or $action == '') {
            $action = 'index';
        }
        if($action != 'index') {
            $output .= $action.'/';
        }

        if(isset($params)) {
            $calls_params = array();
            foreach($params as $key => $param) {
                if(!in_array($key, $attributes)) {
                    array_push($calls_params, $param);
                }
            }
        }

        if(!empty($calls_params) and sizeof($calls_params) > 0) {
            $output .= join('/', $calls_params);
        }
    }
    return $output;
}
function pluralize($str, $force = FALSE) {
    $str = strtolower(trim($str));
    $end = substr($str, -1);

    if ($end == 'y')
    {
        // Y preceded by vowel => regular plural
        $vowels = array('a', 'e', 'i', 'o', 'u');
        $str = in_array(substr($str, -2, 1), $vowels) ? $str.'s' : substr($str, 0, -1).'ies';
    }
    elseif ($end == 'h')
    {
        if (substr($str, -2) == 'ch' OR substr($str, -2) == 'sh')
        {
            $str .= 'es';
        }
        else
        {
            $str .= 's';
        }
    }
    elseif ($end == 's')
    {
        if ($force == TRUE)
        {
            $str .= 'es';
        }
    }
    else
    {
        $str .= 's';
    }

    return $str;
}
/**
 * @param  $name
 * @param  $route
 * @return void
 */
function map_route($name, $route) {
    $path = $name."_path";
    $GLOBALS["route"] = $route;
    if (!function_exists($path)) {
        eval('function '.$path.'($params=null){
            $p = "'.$GLOBALS["route"].'";
            return $p;
        }');
    }
    unset($GLOBALS['route']);
}
function map_resource($model) {
    $model = strtolower($model);
    $GLOBALS['model'] = $model;
    $list = $model."s_path";
    if (!function_exists($list)) {
        eval('function '.$list.'(){
            $controller = pluralize($GLOBALS["model"]);
            return construct_url($controller, "index");
        }');
    }

    $create = $model."_create_path";
    if (!function_exists($create)) {
        eval('function '.$create.'(){
            $controller = pluralize($GLOBALS["model"]);
            return construct_url($controller, "create");
        }');
    }

    $show = $model."_path";
    if (!function_exists($show)) {
        eval('function '.$show.'($model){
            $controller = pluralize($model->type);
            return construct_url($controller, "show", array("id" => $model->id));
        }');
    }

    $edit = $model."_edit_path";
    if (!function_exists($edit)) {
        eval('function '.$edit.'($model){
            $controller = pluralize($model->type);
            return construct_url($controller, "edit", array("id" => $model->id));
        }');
    }

    $delete = $model."_delete_path";
    if (!function_exists($delete)) {
        eval('function '.$delete.'($model){
            $controller = pluralize($model->type);
            return construct_url($controller, "delete", array("id" => $model->id));
        }');
    }

    $save = $model."_save_path";
    if (!function_exists($save)) {
        eval('function '.$save.'($model){
            $controller = pluralize($model->type);
            return construct_url($controller, "save");
        }');
    }
    unset($GLOBALS['model']);
}
