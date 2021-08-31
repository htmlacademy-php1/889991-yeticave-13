<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);

$page_404 = include_template("404.php", [
    "categories" => $categories
]);

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql = get_query_lot ($id);
} else {
    print($page_404);
    die();
};

$res = mysqli_query($con, $sql);
if ($res) {
   $lot = get_arrow($res);
} else {
   $error = mysqli_error($con);
}

if(!$lot) {
    print($page_404);
    die();
}


$page_content = include_template("main-lot.php", [
   "categories" => $categories,
   "lot" => $lot
]);
$layout_content = include_template("layout-lot.php", [
   "content" => $page_content,
   "categories" => $categories,
   "title" => $lot["title"],
   "is_auth" => $is_auth,
   "user_name" => $user_name
]);

print($layout_content);



