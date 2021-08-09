<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");

$con = mysqli_connect("localhost", "newuser", "", "yeticave");
if (!$con) {
   $error = mysqli_connect_error();
} else {
   $sql = "SELECT character_code, name_category FROM categories";
   $result = mysqli_query($con, $sql);
      if($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
      } else {
        $error = mysqli_error($con);
      }
}

$sql = "SELECT lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots
JOIN categories ON lots.category_id=categories.id
WHERE date_creation > '2021-07-15' ORDER BY date_creation DESC";

$res = mysqli_query($con, $sql);
if ($res) {
   $goods = mysqli_fetch_all($res, MYSQLI_ASSOC);
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
   "user_name" => $user_name
]);

print($layout_content);


