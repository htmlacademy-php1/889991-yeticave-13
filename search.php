<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);
$search = $_GET["search"];
if ($search) {
    $items_count = get_count_lots($con, $search);
    $cur_page = $_GET["page"] ?? 1;
    $page_items = 9;
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $goods = get_found_lots($con, $search, $page_items, $offset);
}

$header = include_template("header.php", [
    "categories" => $categories
]);

$page_content = include_template("main-search.php", [
    "categories" => $categories,
    "search" => $search,
    "goods" => $goods,
    "header" => $header,
    "panagination" => $panagination,
    "pages_count" => $pages_count,
    "pages" => $pages,
    "cur_page" => $cur_page
]);
$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => "Результат поиска",
    "search" => $search,
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);


print($layout_content);


