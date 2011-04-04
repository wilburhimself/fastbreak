<?php
class Request {
    private $data = array();
    public $method;

    public function __construct() {
        $this->get_data();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function is_post() {
        return sizeof($this->data) > 0;
    }

    public function is_ajax() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');     
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

    public function get_data() {
        if (!empty($_POST)) {
            foreach ($_POST as $k => $v) {
                $this->$k = $v;
            }
            unset ($_POST);
        }
    }
}
