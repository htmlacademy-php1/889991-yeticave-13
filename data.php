<?php
$categories = [
           "boards" => "Доски и лыжи",
           "attachment" => "Крепления",
           "boots" => "Ботинки",
           "clothing" => "Одежда",
           "tools" => "Инструменты",
           "other" => "Разное"
         ];

$goods = [
           [
              "title" => "2014 Rossignol District Snowboard",
              "category" => $categories["boards"],
              "price" => 10999,
              "image" => "img/lot-1.jpg",
              "expiration" => "2021-08-10"
           ],
           [
              "title" => "DC Ply Mens 2016/2017 Snowboard",
              "category" => $categories["boards"],
              "price" => 159999,
              "image" => "img/lot-2.jpg",
              "expiration" => "2021-08-11"
           ],
           [
              "title" => "Крепления Union Contact Pro 2015 года размер L/XL",
              "category" => $categories["attachment"],
              "price" => 8000,
              "image" => "img/lot-3.jpg",
              "expiration" => "2021-08-12"
           ],
           [
              "title" => "Ботинки для сноуборда DC Mutiny Charocal",
              "category" => $categories["boots"],
              "price" => 	10999,
              "image" => "img/lot-4.jpg",
              "expiration" => "2021-08-13"
           ],
           [
              "title" => "Куртка для сноуборда DC Mutiny Charocal",
              "category" => $categories["clothing"],
              "price" => 7500,
              "image" => "img/lot-5.jpg",
              "expiration" => "2021-08-14"
           ],
           [
              "title" => "Маска Oakley Canopy",
              "category" => $categories["other"],
              "price" => 5400,
              "image" => "img/lot-6.jpg",
              "expiration" => "2021-08-15"
           ],
        ];
$is_auth = rand(0, 1);

$user_name = 'Ярослав';
//console_log($user_name);
