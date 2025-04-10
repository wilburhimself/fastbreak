<?php
namespace Core\Helpers;

class ValidationHelper
{
    public static function required(mixed $value): bool
    {
        return !empty(trim($value));
    }

    public static function isValid(string $rule, mixed $value): bool
    {
        $methodName = [self::class, $rule];
        return is_callable($methodName) && call_user_func($methodName, $value);
    }

    public static function format_error_message(array $error): string
    {
        return "Field {$error['field']} is {$error['rule']}";
    }

    public static function errors(array $errors): string
    {
        if (empty($errors)) {
            return '';
        }

        $errorMessages = array_map([self::class, 'format_error_message'], $errors);
        $errorListItems = array_map(fn($message) => "<li>{$message}</li>", $errorMessages);

        return '<ul id="errors">' . implode('', $errorListItems) . '</ul>';
    }
}
?>