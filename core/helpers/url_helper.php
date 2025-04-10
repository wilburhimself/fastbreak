<?php

namespace Core\Helpers;

use Core\Model;
use Core\Router;

// get site base url
function base_url(?string $link = null): string
{
    return "/{$link}"; // Assuming base URL is the root. Adjust if needed.
}

// redirecciona al usuario a una p�gina determinada
function redirect(string $url): void
{
    http_response_code(302);
    header('Location: ' . base_url() . $url);
    exit;
}

function redirect_to(string $controller, string $action, ?array $params = null): void
{
    redirect(construct_url($controller, $action, $params));
}

function redirect_back_or_default(?string $url = null): void
{
    $default = $url ?? base_url();
    $location = $_SERVER['HTTP_REFERER'] ?? $default;
    http_response_code(302);
    header('Location: ' . $location);
    exit;
}

// crea una direccion que se acopla a los standares de nuestra aplicacion
function construct_url($controller=null, $action=null, $params=null) {
    $attributes = array('htmlclass', 'htmlid', 'htmlrel', 'htmltitle');
    $output = '';
    if(!isset($controller) or $controller == '') {
        $controller = 'index';
    } else {
        $output .= $controller.'/';
        if(!isset($action) or $action == '') {
            $action = 'index';
        }
        if($action != 'index') {
            $output .= $action.'/';
        }

        if(isset($params)) {
            $calls_params = array();
            foreach($params as $key => $param) {
                if(!in_array($key, $attributes)) {
                    array_push($calls_params, $param);
                }
            }
        }

        if(!empty($calls_params) and sizeof($calls_params) > 0) {
            $output .= join('/', $calls_params);
        }
    }
    return $output;
}
function pluralize($str, $force = FALSE) {
    $str = strtolower(trim($str));
    $end = substr($str, -1);

    if ($end == 'y')
    {
        // Y preceded by vowel => regular plural
        $vowels = array('a', 'e', 'i', 'o', 'u');
        $str = in_array(substr($str, -2, 1), $vowels) ? $str.'s' : substr($str, 0, -1).'ies';
    }
    elseif ($end == 'h')
    {
        if (substr($str, -2) == 'ch' OR substr($str, -2) == 'sh')
        {
            $str .= 'es';
        }
        else
        {
            $str .= 's';
        }
    }
    elseif ($end == 's')
    {
        if ($force == TRUE)
        {
            $str .= 'es';
        }
    }
    else
    {
        $str .= 's';
    }

    return $str;
}

function map_route(string $name, string $route): string
{
    $functionName = $name . "_path";
    if (!function_exists($functionName)) {
        eval('function ' . $functionName . '(?array $params = null): string {
            $p = "' . $route . '";
            return $p;
        }');
    }
    return $functionName;
}

function generate_resource_functions(string $model): array
{
    $model = strtolower($model);
    $controller = pluralize($model);
    $functions = [
        "{$model}s_path" => function () use ($controller): string {
            return construct_url($controller, "index");
        },
        "{$model}_create_path" => function () use ($controller): string {
            return construct_url($controller, "create");
        },
        "{$model}_path" => function (Model $m): string {
            $controller = pluralize($m->type);
            return construct_url($controller, "show", ["id" => $m->id]);
        },
        "{$model}_edit_path" => function (Model $m): string {
            $controller = pluralize($m->type);
            return construct_url($controller, "edit", ["id" => $m->id]);
        },
        "{$model}_delete_path" => function (Model $m): string {
            $controller = pluralize($m->type);
            return construct_url($controller, "delete", ["id" => $m->id]);
        },
        "{$model}_save_path" => function (Model $m): string {
            $controller = pluralize($m->type);
            return construct_url($controller, "save");
        },
    ];
    return $functions;
}

function map_resource(string $model): array
{
    $functions = generate_resource_functions($model);
    foreach ($functions as $name => $definition) {
        if (!function_exists($name)) {
            $reflection = new \ReflectionFunction($definition);
            $code = $reflection->getStaticVariables();
            $params = '';
            if ($reflection->getNumberOfParameters() > 0) {
                $paramsReflection = $reflection->getParameters();
                $params = implode(', ', array_map(function ($param) {
                    $type = $param->getType();
                    $typeHint = $type ? $type->getName() . ' ' : '';
                    return $typeHint . '$' . $param->getName();
                }, $paramsReflection));
            }
            $returnType = $reflection->getReturnType();
            $returnTypeHint = $returnType ? ': ' . $returnType->getName() : '';
            eval('function ' . $name . '(' . $params . ')' . $returnTypeHint . ' { ' . $reflection->getBody() . ' }');
        }
    }
    return array_keys($functions);
}
