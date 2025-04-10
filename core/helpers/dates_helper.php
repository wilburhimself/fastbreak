<?php
namespace Core\Helpers;

use DateTimeInterface;

class DatesHelper
{
    public static function timeFormat(string $format, DateTimeInterface $time): string
    {
        $formatStrings = [
            'day' => $time->format('d \d\e F'),
            'short' => $time->format('l d \d\e F'),
            'long' => $time->format('l d \d\e F \a \l\a\s h:i'),
            'datetime' => $time->format('Y-m-d H:i:s'),
            'date' => $time->format('Y-m-d'),
            'hour' => $time->format('H:i'),
        ];

        return htmlentities($formatStrings[$format] ?? '');
    }

    public static function timePicker(): string
    {
        $currentYear = (int)date('Y');
        $years = array_combine(range($currentYear - 1, $currentYear + 3), range($currentYear - 1, $currentYear + 3));
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
        $days = array_combine(range(1, 31), range(1, 31));
        $hours = array_combine(range(1, 12), range(1, 12));
        $minutes = array_combine(['00', '15', '30', '45'], ['00', '15', '30', '45']);

        $output = '<label>Fecha</label>';
        $output .= '<select name="year">';
        foreach ($years as $value => $label) $output .= "<option value=\"{$value}\">{$label}</option>";
        $output .= '</select>';

        $output .= '<select name="month">';
        foreach ($months as $value => $label) $output .= "<option value=\"{$value}\">{$label}</option>";
        $output .= '</select>';

        $output .= '<select name="day">';
        foreach ($days as $value => $label) $output .= "<option value=\"{$value}\">{$label}</option>";
        $output .= '</select><br />';

        $output .= '<label>Hora</label>';
        $output .= '<select name="hour">';
        foreach ($hours as $value => $label) $output .= "<option value=\"{$value}\">{$label}</option>";
        $output .= '</select>';

        $output .= '<select name="minute">';
        foreach ($minutes as $value => $label) $output .= "<option value=\"{$value}\">{$label}</option>";
        $output .= '</select>';

        return $output;
    }
}