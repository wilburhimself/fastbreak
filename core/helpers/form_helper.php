<?php
    function form($model) {
        $form = array();
        if ($model->errors) {
            $form[] = errors($model->errors);
        }
        foreach ($model->fields as $label => $field) {
            if ($field['type'] == "string" || $field['type'] == "text" || $field['type'] == 'file' || $field['type'] == 'password') {
                $form[] = '<p>'.create_field($label, $field, $model->$label).'</p>';
            } elseif ($field['type'] == 'foreign') {
                $form[] = '<p>'.collection_select($model, $field).'</p>';
            }
        }
        if (isset($model->id)) {
            $form[] = '<input type="hidden" name="object[id]" value="'.$model->id.'" />';
        }
        return join("\n", $form);
    }

    function collection_select($instance, $field) {
        $output = '<label for="object_'.$field['label'].'">'.ucwords($field['label']).'</label>';
        $output .= '<select name="object['.$field['field_name'].']" id="object_'.$field['field_name'].'">';
            $object = new $field['class'];
            $p = $object->get();
            foreach ($p as $option) {
                $output .= '<option value="'.$option->id.'"';
                    if ($option->id == $instance->$field['field_name']) $output .= ' selected="selected" ';
                $output .= '>'.$option->$field['display'].'</option>';
            }
        $output .= '</select>';
        return $output;
    }

    function create_field($label, $field, $value="") {
        if ($value == "" && isset($_POST['object'][$label])) {
            $value = $_POST['object'][$label];
        }

        $output = '<label for="object_'.$label.'">'.ucwords($field['label']).'</label>';
        if ($field['type'] == 'string') {
            $output .= '<input id="object_'.$label.'" type="text" value="'.$value.'" name="object['.$label.']" />';
        }

        if ($field['type'] == 'password') {
            $output .= '<input id="object_'.$label.'" type="password" name="object['.$label.']" />';
        }
        if ($field['type'] == 'text') {
            $output .= '<textarea name="object['.$label.']" id="object_'.$label.'">'.$value.'</textarea>';
        }

        if ($field['type'] == 'file') {
            $output .= '<input id="object_'.$label.'" type="file" name="object['.$label.']" />';
        }
        return $output;

    }


    function form_open($url=null) {
        $url = isset($url) ? $url : $_SERVER['REQUEST_URI'];
        return '<form action="'.base_url($url).'" method="post">';
    }

    function form_open_multipart($url=null) {
        $url = isset($url) ? $url : '.';
        return '<form action="'.base_url($url).'" enctype="multipart/form-data" method="post">';
    }

    function form_close() {
        return '</form>';
    }
?>