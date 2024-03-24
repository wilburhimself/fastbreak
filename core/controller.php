<?php
Abstract class Controller {
    private $data = array();
    private $loader;
    private $render_type;
    private $cache = FALSE;
    private $cache_time;
    private $starttime;
    private $endtime;
    private $clas;
    private $view;

    public function __construct() {
        $this->loader = new Loader;
        $this->starttime = microtime(true);
    }

    protected function add_cache($time) {
        $this->cache = TRUE;
        $this->cache_time = $time;
    }

    public function __get($var) {
        if (property_exists($this, $var)) {
            return $this->$var;
        }
        return $this->loader->$var;
    }

    public function render($type, $message=null) {
        $this->render_type = $type;
        if (isset($message)) {
            print $message;
        }
    }

    function t($k, $v) {
        $this->data[$k] = $v;
    }

    public function __destruct() {
        if ($this->render_type == 'none' or $this->render_type == 'text') exit();
        $t = new Template($this->view);
        $t->load_vars($this->data);
        $return = $t->render();



        if(!isset($this->layout)) {
            if (!file_exists(VIEWSPATH.'layouts/application.php')) {
                print $return;
                return false;
            }
            $this->layout = 'application';
        }

        if(isset($this->layout)) {
            $t = new Template('layouts/'.$this->layout);
            $t->load_vars($this->data);
            $t->yield = $return;
            $output = $t->render();
            if ($this->cache == TRUE) {
                $this->Cache->add($this->cache_time, $output);
            }
            $this->endtime = microtime(true);
            $time = $this->endtime - $this->starttime;
            print $output;

            //print $time;
        }
    }

    public static function factory($controller) {
        $c = new $controller;
        $c->clas = $controller;
        return $c;
    }

    public function call_action($action, $params=null) {
        $this->view = strtolower($this->clas).DIRECTORY_SEPARATOR.$action;
        if(is_callable(array($this, $action)) == false) {
            // redirect_to('error', 'no_encontrada');
        } else {
            $new = sizeof($params) > 0 ? array_merge(array(), $params) : null;
            $this->$action($new);
        }
    }
}
?>
