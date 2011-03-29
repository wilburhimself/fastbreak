<?php
    function required($value) {
        if (!empty($value)) {
            return true;
        } else {
            return false;
        }
    }
    function is_valid($rule, $value) {
        if (function_exists($rule)) {
            return call_user_func($rule, $value);
        }
    }

    function construct_message($error) {
        return "Field ".$error['field']." is ".$error['rule'];
    }

    function errors($errors) {
        $output = '<ul id="errors">';
        foreach ($errors as $error) {
            $output .= '<li>'.construct_message($error).'</li>';
        }
        $output .= '</ul>';
        return $output;
    }
?>