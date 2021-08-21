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

$head_404 = include_template("head.php", [
        "title" => "Страница не найдена"
    ]);

$page_footer = include_template("footer.php", [
   "categories" => $categories
]);
$page_404 = include_template("404.php", [
    "categories" => $categories
]);

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql = get_query_lot ($id);
} else {
    print($head_404);
    print($page_404);
    print($page_footer);
    die();
}

$res = mysqli_query($con, $sql);
if ($res) {
   $lot = get_arrow($res);
} else {
   $error = mysqli_error($con);
}

if(!$lot) {
    print($head_404);
    print($page_404);
    print($page_footer);
    die();
}


$page_head = include_template("head.php", [
    "title" => $lot["title"]
]);
$page_content = include_template("main-lot.php", [
   "categories" => $categories,
   "lot" => $lot
]);
$layout_content = include_template("layout-lot.php", [
   "content" => $page_content,
   "title" => $lot["title"],
   "is_auth" => $is_auth,
   "user_name" => $user_name
]);

print($page_head);
print($layout_content);
print($page_footer);


