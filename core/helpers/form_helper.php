<?php
namespace Core\Helpers;

use Core\Model;
use Core\Helpers\ValidationHelper;

class FormHelper
{
    public static function form(Model $model): string
    {
        $form = [];
        if (!empty($model->errors)) {
            $form[] = ValidationHelper::errors($model->errors);
        }
        foreach ($model->fields as $label => $field) {
            $form[] = match ($field['type']) {
                "string", "text", "file", "password" => '<p>' . self::createField($label, $field, $model->$label) . '</p>',
                'foreign' => '<p>' . self::collectionSelect($model, $field) . '</p>',
                default => '',
            };
        }
        if (isset($model->id)) {
            $form[] = '<input type="hidden" name="object[id]" value="' . $model->id . '" />';
        }
        return implode("\n", $form);
    }

    private static function collectionSelect(Model $instance, array $field): string
    {
        $output = '<label for="object_' . $field['label'] . '">' . ucwords($field['label']) . '</label>';
        $output .= '<select name="object[' . $field['field_name'] . ']" id="object_' . $field['field_name'] . '">';
        $object = new $field['class'];
        $options = $object->get();
        foreach ($options as $option) {
            $selected = ($option->id == $instance->{$field['field_name']}) ? ' selected="selected" ' : '';
            $output .= '<option value="' . $option->id . '"' . $selected . '>' . $option->{$field['display']} . '</option>';
        }
        $output .= '</select>';
        return $output;
    }

    private static function createField(string $label, array $field, string $value = ""): string
    {
        $value = $value ?: ($_POST['object'][$label] ?? "");
        $output = '<label for="object_' . $label . '">' . ucwords($field['label']) . '</label>';
        $output .= match ($field['type']) {
            'string' => '<input id="object_' . $label . '" type="text" value="' . htmlspecialchars($value) . '" name="object[' . $label . ']" />',
            'password' => '<input id="object_' . $label . '" type="password" name="object[' . $label . ']" />',
            'text' => '<textarea name="object[' . $label . ']" id="object_' . $label . '">' . htmlspecialchars($value) . '</textarea>',
            'file' => '<input id="object_' . $label . '" type="file" name="object[' . $label . ']" />',
            default => '',
        };
        return $output;
    }

    public static function formOpen(?string $url = null): string
    {
        $url = $url ?? $_SERVER['REQUEST_URI'];
        return '<form action="' . base_url($url) . '" method="post">';
    }

    public static function formOpenMultipart(?string $url = null): string
    {
        $url = $url ?? '.';
        return '<form action="' . base_url($url) . '" enctype="multipart/form-data" method="post">';
    }

    public static function formClose(): string
    {
        return '</form>';
    }
}