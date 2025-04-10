<?php

namespace Core;

class Session {
    public function __set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function __get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }
}