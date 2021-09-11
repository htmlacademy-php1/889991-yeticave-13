<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);

$header = include_template("header.php", [
    "categories" => $categories
]);
if ($is_auth) {
    $bets = get_bets($con, $_SESSION["id"]);
}
$page_content = include_template("main-my-bets.php", [
    "categories" => $categories,
    "header" => $header,
    "bets" => $bets,
    "is_auth" => $is_auth

]);


$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => $lot["title"],
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);

print($layout_content);



