<?php

namespace Core;

class File
{
    public string $filename;
    public string $filename_tmp;
    public string $filetype;
    public int $filesize;
    public string $filepath;

    private static ?File $instance = null;

    private function __construct(array $file, string $path, string $field)
    {
        $this->parse_file($file, $path, $field);
    }

    public function upload(): self
    {
        if (move_uploaded_file($this->filename_tmp, $this->filepath)) {
            return $this;
        }
        throw new \Exception("File upload failed.");
    }

    protected function parse_file(array $file, string $path, string $field): void
    {
        $this->filename = $file['name'][$field];
        $this->filename_tmp = $file['tmp_name'][$field];
        $this->filetype = $file['type'][$field];
        $this->filesize = $file['size'][$field];
        $this->filepath = rtrim($path, '/') . '/' . $this->filename;
    }

    public static function Factory(array $file, string $path, string $field): File
    {
        if (self::$instance === null) {
            self::$instance = new self($file, $path, $field);
        }
        return self::$instance;
    }
}