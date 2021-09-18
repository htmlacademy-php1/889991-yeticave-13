<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);
$cat_id = $_GET["category_id"];
$category_name = get_category_name($con, $cat_id);
if ($cat_id) {
    $items_count = get_count_lot_cat($con, $cat_id);
    $cur_page = $_GET["page"] ?? 1;
    $cur_page = intval($cur_page);
    $page_items = 9;
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $goods = get_lots_cat($con, $cat_id, $page_items, $offset);
}

$header = include_template("header.php", [
    "categories" => $categories,
    "cat_id" => $cat_id
]);

$page_content = include_template("main-all-lots.php", [
    "categories" => $categories,
    "cat_id" => $cat_id,
    "goods" => $goods,
    "header" => $header,
    "pagination" => $pagination,
    "pages_count" => $pages_count,
    "pages" => $pages,
    "cur_page" => $cur_page
]);
$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => "Лоты категории '$category_name'",
    "search" => $search,
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);


print($layout_content);


