<?php

namespace Core;

class Request
{
    private array $data = [];
    public string $method;

    public function __construct() {
        $this->get_data();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function is_post(): bool
    {
        return sizeof($this->data) > 0;
    }

    public function is_ajax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public function __set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function __get(string $key): mixed
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    private function get_data(): void
    {
        if (!empty($_POST)) {
            foreach ($_POST as $k => $v) {
                $this->$k = $v;
            }
            unset($_POST);
        } elseif (!empty($_GET)) {
            foreach ($_GET as $k => $v) {
                $this->$k = $v;
            }
            unset($_GET);
        }
    }
}
