<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");


if (!$con) {
   $error = mysqli_connect_error();
} else {
   $sql = "SELECT character_code, name_category FROM categories";
   $result = mysqli_query($con, $sql);
   if ($result) {
        $categories = get_arrow($result);
    } else {
        $error = mysqli_error($con);
      }
}

$sql = get_query_list_lots ('2021-07-15');

$res = mysqli_query($con, $sql);
if ($res) {
   $goods = get_arrow($res);
} else {
   $error = mysqli_error($con);
}

$page_head = include_template("head.php", [
    "title" => "Главная"
]);

$page_content = include_template("main.php", [
   "categories" => $categories,
   "goods" => $goods
]);
$layout_content = include_template("layout.php", [
   "content" => $page_content,
   "categories" => $categories,
   "title" => "Главная",
   "is_auth" => $is_auth,
   "user_name" => $user_name
]);
$page_footer = include_template("footer.php", [
   "categories" => $categories
 ]);


print($page_head);
print($layout_content);
print($page_footer);

