<?php

// Construye un link siguiendo los standares de la aplicacion
function link_to($text, $controller=null, $action=null, $params=null) {
    $attributes = array('htmlclass', 'htmlid', 'htmlrel', 'htmltitle', 'htmlconfirm');
    $output = '<a href="'.base_url();
    $output .= construct_url($controller, $action, $params);
    $output .= '"';
    $output .= isset($params['htmlid']) ? ' id="'.$params['htmlid'].'"' : '';
    $output .= isset($params['htmlclass']) ? ' class="'.$params['htmlclass'].'"' : '';
    $output .= isset($params['htmlrel']) ? ' rel="'.$params['htmlrel'].'"' : '';
    $output .= isset($params['htmltitle']) ? ' title="'.$params['htmltitle'].'"' : '';
    $output .= isset($params['htmlconfirm']) ? ' onclick="confirm(\''.$params['htmlconfirm'].'\');"' : '';
    $output .= '>'.$text.'</a>';

    return $output;
}

function anchor ($text, $url, $attrs=null) {
    $link_format = '<a href="%s" %s>%s</a>';
    return sprintf($link_format, base_url().$url, $attrs, $text);
}

/* add stylesheet */
function add_css($css, $media="screen") {
    echo '<link type="text/css" rel="stylesheet" media="'.$media.'" href="'.base_url().'assets/stylesheets/'.$css.'.css" />';
}
/* add stylesheet */
function add_less($css) {
    print '<link type="text/css" rel="stylesheet/less" href="'.base_url().'assets/stylesheets/'.$css.'.less" />';
}

/* add javascript */
function add_js($js) {
    return '<script type="text/javascript" src="'.base_url().'assets/javascript/'.$js.'.js"></script>';
}