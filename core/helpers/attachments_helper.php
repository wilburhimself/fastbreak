<?php
function upload_path($type, $id) {
   return './uploads/'.$type.'/'.$id.'/';
}

function prepare_path($type, $id) {
    $upload_path = upload_path($type, $id);
    if (!file_exists('./uploads/'.$type)) {
        mkdir('./uploads/'.$type);
    }
    if (!file_exists($upload_path)) {
        mkdir($upload_path);
    }
    return $upload_path;
}

function upload_to($type, $id, $file, $field) {
    $path = prepare_path($type, $id);
    $file_info = File::Factory($file, $path, $field);
}