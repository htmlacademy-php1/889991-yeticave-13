<?php

        /**
 * Форматирует цену лота - разделяет пробелом разряды числа, добавляет знак рубля
 * @param integer $num Цена лота
 * @return string Как цена будет показываться в карточке
 */
        function format_num ($num) {
           $num = ceil($num);
           $num = number_format($num, 0, '', ' ');

           return $num ." " ."₽";
        }

