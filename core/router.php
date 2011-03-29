<?php
class router {
    private $path; // Controller's routes
    private $params;
    private $args = array(); // Action's arguments

    public function __construct($routes) {
        $this->routes = $routes;
        $this->uri_string = key($_GET);
        $this->uri_string = substr($this->uri_string, 1);
        $this->set_path(CONTROLLERSPATH);
    }
    /*
     *
     *  Specifies a path for controllers folder
     *
     */
    private function set_path($path) {
        if(!is_dir($path)) {
            exit('Ruta Invalida para el controlador');
        }
        $this->path = $path;
    }
    
    public function delegate() {
        $this->get_parts();
        $file = $this->path.$this->controller.'.php';
        if(!is_file($file) or !is_readable($file)) {
            // redirect_to('error', 'no_encontrada');
        } else {
            include $file;
        }
        
        $cont = $this->controller;
        $controller = new $cont;
        $controller->clas = $this->controller;
        $controller->view = $this->controller.DIRECTORY_SEPARATOR.$this->action;
        if(is_callable(array($controller, $this->action) == false)) {
            // redirect_to('error', 'no_encontrada');
        } else {
            $action = $this->action;
            $new = sizeof($this->params) > 0 ? array_merge(array(), $this->params) : null;
            $controller->$action($new);
        }
    }

    private function filter_uri() {
        $bad	= array('$', 		'(', 		')',	 	'%28', 		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $this->uri_string);
    }

    private function _get_route() {
        $request = $this->filter_uri();
        if (isset($this->routes[$request]))
		{
			$call = $this->routes[$request];
            return $call;
		}

        foreach ($this->routes as $key => $val) {
            $action = $val;
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
            if ( preg_match('#^'.$key.'$#', $request)) {
                if (strpos($action, '$') !== FALSE AND strpos($key, '(') !== FALSE) {
                    $v = preg_replace('#^'.$key.'$#', $action, $request);
                    return $v;
                }
            }
        }
        return $request;
    }

    private function get_parts() {
        $route = $this->_get_route();
        $route = explode('/', $route);

        // Erases empty parts
        foreach($route as $key => $r) {
            if($route[$key] == '') {
                unset($route[$key]);
            }
        }
        $this->route_parts = $route;
        $this->get_controller();
        $this->get_action();
        $this->get_params();
    }
    
    private function get_controller() {

        if(!isset($this->route_parts[0])) {
            $this->controller = DEFAULT_CONTROLLER;
        } else {
            $this->controller = ucwords($this->route_parts[0]);
            unset($this->route_parts[0]);
        }
    }
    
    private function get_action() {
        if(!isset($this->route_parts[1])) {
            $this->action = 'index';
        } else {
            $this->action = $this->route_parts[1];
            unset($this->route_parts[1]);
        }
    }
    
    private function get_params() {
        if(sizeof($this->route_parts) > 0) {
            $this->params = $this->route_parts;
        }
    }
}
?>