<?php
class Template {
    private $data = array();
    public function __construct($file) {

        $this->file = VIEWSPATH.$file.'.php';
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }

    public function load_vars($data) {
        foreach($data as $k => $v) {
            $this->$k = $v;
        }
    }

    public function render() {
        ob_start();
        extract($this->data);
        include $this->file;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
