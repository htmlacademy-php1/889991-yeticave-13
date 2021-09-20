<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");
require_once("getwinner.php");

$categories = get_categories($con);

$sql = get_query_list_lots ();

$result = mysqli_query($con, $sql);
if ($result) {
    $goods = get_arrow($result);
} else {
    $error = mysqli_error($con);
}

$page_content = include_template("main.php", [
    "categories" => $categories,
    "goods" => $goods
]);
$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => "Главная",
    "is_auth" => $is_auth,
    "user_name" => $user_name ?? false
]);


print($layout_content);


