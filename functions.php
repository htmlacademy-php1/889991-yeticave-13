<?php
/**
 * Форматирует цену лота - разделяет пробелом разряды числа, добавляет знак рубля
 * @param integer $num Цена лота
 * @return string Как цена будет показываться в карточке
*/
    function format_num ($num) {
        $num = ceil($num);
        $num = number_format($num, 0, '', ' ');

        return "$num ₽";
        }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
        }

/**
 * Возвращеет количество целых часов и остатка минут от настоящего времени до даты
 * @param string $date Дата истечения времени
 * @return array
*/
    function get_time_left ($date) {
        date_default_timezone_set('Europe/Moscow');
        $final_date = date_create($date);
        $cur_date = date_create("now");
        $diff = date_diff($final_date, $cur_date);
        $format_diff = date_interval_format($diff, "%d %H %I");
        $arr = explode(" ", $format_diff);

        $hours = $arr[0] * 24 + $arr[1];
        $minutes = intval($arr[2]);
        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $res[] = $hours;
        $res[] = $minutes;

        return $res;
    }
