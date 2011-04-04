<?php
class File {
    public $filename;
    public $filename_tmp;
    public $filetype;
    public $filesize;
    public $filepath;

    private static $instance;

    private function __construct($file, $path, $field) {
        $this->parse_file($file, $path, $field);
    }

    public function upload() {
        if (move_uploaded_file($this->filename_tmp, $this->filepath)) {
            return $this;
        }
        return false;
    }

    protected function parse_file($file, $path, $field) {
        $this->filename = $file['name'][$field];
        $this->filename_tmp = $file['tmp_name'][$field];
        $this->filetype = $file['type'][$field];
        $this->filesize = $file['size'][$field];
        $this->filepath = $path . $this->filename;
    }

    public static function Factory($file, $path, $field) {
        if (!isset(self::$instance)) {
            self::$instance = new File($file, $path, $field);
        }
        return self::$instance;
    }

}