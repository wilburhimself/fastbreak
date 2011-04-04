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

        if(sizeof($calls_params) > 0) {
            $output .= join('/', $calls_params);
        }
    }

    return $output;
}
