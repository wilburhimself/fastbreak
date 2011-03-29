<?php
function timeformat($format, $time) {
    $formats = array(
        'day' => '%d de %B',
        'short' => '%A %d de %B',
        'long' => '%A %d de %B a las %I:%M',
        'datetime' => '%Y-%m-%d %H:%M:%S',
        'date' => '%Y-%m-%d',
        'hour' => '%H:%M',
    );
    return htmlentities(strftime($formats[$format], $time));
}

function timepicker() {
    $y = date('Y');
    $years = range($y - 1, $y + 3);
    $u =array();
    foreach($years as $e) {
        $u[$e] = $e;
    }
    $output = form_label('Fecha').form_dropdown('year', $u);
    $months = array(
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    );
    $output .= form_dropdown('month', $months);
    $days = range(1, 31);
    $u =array();
    foreach($days as $e) {
        $u[$e] = $e;
    }
    $output .= form_dropdown('day', $u);
    $output .= '<br />';
    $hours = range(1, 12);
    $u =array();
    foreach($hours as $e) {
        $u[$e] = $e;
    }
    $output .= form_label('Hora').form_dropdown('hour', $u);
    $u =array();
    $minute = array('00', '15', '30', '45');
    foreach($minute as $e) {
        $u[$e] = $e;
    }
    $output .= form_dropdown('minute', $u);

    return $output;
}