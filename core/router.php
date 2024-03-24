<?php

declare(strict_types=1);

class Router {
    private string $path; // Controller's routes
    private array $params = [];
    private array $routes;
    private string $uriString;
    private array $routeParts = [];
    private string $controller;
    private string $action;
    private array $args = []; // Action's arguments
    public static array $rs = [];

    public function __construct(array $routes) {
        $this->routes = $routes;
        self::$rs = $routes;
        $this->uriString = key($_GET) ?? ''; // Use null coalescing operator for default value
        $this->uriString = trim(substr($this->uriString, 1), '/'); // Remove leading/trailing slashes
        $this->set_path(CONTROLLERSPATH);
    }

    private function set_path(string $path): void {
        if (!is_dir($path)) {
            exit('Ruta Invalida para el controlador'); // Maintain the error message
        }
        $this->path = $path;
    }

    public function route(): void {
        $this->get_parts();
        $file = $this->path . $this->controller . '.php';
        if (!is_file($file) || !is_readable($file)) {
            // Implement error handling (redirect or exception)
            throw new Exception('Controller not found'); // Example for exception handling
        } else {
            require_once $file;
        }

        $controller = Controller::factory($this->controller);
        $controller->call_action($this->action, $this->params);
    }

    private function filter_uri(): string {
        $bad = array('$', '(', ')', '%28', '%29');
        $good = array('&#36;', '&#40;', '&#41;', '&#40;', '&#41;');

        return str_replace($bad, $good, $this->uriString);
    }

    private function _get_route(): string {
        $request = $this->filter_uri();

        if (isset($this->routes[$request])) {
            return $this->routes[$request];
        }

        foreach ($this->routes as $key => $val) {
            if ($key === $request) {
                return $val;
            }

            if (strpos($key, ':') !== false) {
                $regex = str_replace(
                    ['/', ':any', ':num'],
                    ['\/', '.+', '[0-9]+'],
                    $key
                );
                $regex = '#^' . $regex . '$#';

                if (preg_match($regex, $request)) {
                    $action = str_replace([':any', ':num'], ['.+', '[0-9]+'], $val);
                    $v = preg_replace($regex, $action, $request);
                    return $v;
                }
            }
        }

        return $request;
    }

    private function get_parts(): void {
        $route = $this->_get_route();
        $routeParts = explode('/', $route);

        // Remove empty parts using array_filter
        $this->routeParts = array_filter($routeParts, function ($value) {
            return $value !== '';
        });

        $this->get_controller();
        $this->get_action();
        $this->get_params();
    }

    private function get_controller(): void {
        $this->controller = strtolower($this->routeParts[0] ?? DEFAULT_CONTROLLER);
        unset($this->routeParts[0]);
    }

    private function get_action(): void {
        $this->action = $this->routeParts[1] ?? 'index';
        unset($this->routeParts[1]);
    }

    private function get_params(): void {
        $this->params = $this->routeParts;
    }

    public static function get(string $named_route): string {
        if (array_key_exists($named_route, self::$rs)) {
            if (is_array(self::$rs[$named_route])) {
                return self::$rs[$named_route][$named_route];
            }
        }
        return ''; // Or throw an exception for a missing route
    }
}
