<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);

$page_content = include_template("main-404.php", [
    "categories" => $categories,
 ]);
 $layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => "Страница не найдена",
    "is_auth" => $is_auth,
    "user_name" => $user_name
 ]);

 $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql = get_query_lot ($id);
} else {
    print($layout_content);
    die();
};

$res = mysqli_query($con, $sql);
if ($res) {
   $lot = get_arrow($res);
} else {
   $error = mysqli_error($con);
}

if(!$lot) {
    print($layout_content);
    die();
}

$history = get_bets_history($con, $id);
if (!empty($history)) {
    $current_price = max($lot["start_price"], $history[0]["price_bet"]);
} else {
    $current_price = $lot["start_price"];
}
$name_user = $history[0]["user_name"] ?? '';
$min_bet = $current_price + $lot["step"];

$header = include_template("header.php", [
    "categories" => $categories
]);
$page_content = include_template("main-lot.php", [
    "categories" => $categories,
    "header" => $header,
    "lot" => $lot,
    "is_auth" => $is_auth,
    "current_price" => $current_price,
    "min_bet" => $min_bet,
    "id" => $id,
    "history" => $history,
    "name_user" => $name_user
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bet = filter_input(INPUT_POST, "cost", FILTER_VALIDATE_INT);

    if ($bet < $min_bet) {
        $error = "Ставка не может быть меньше $min_bet";
    }
    if (empty($bet)) {
        $error = "Ставка должна быть целым числом, болше ноля";
    }

    if ($error) {
        $page_content = include_template("main-lot.php", [
            "categories" => $categories,
            "header" => $header,
            "lot" => $lot,
            "is_auth" => $is_auth,
            "current_price" => $current_price,
            "min_bet" => $min_bet,
            "error" => $error,
            "id" => $id,
            "history" => $history,
            "name_user" => $name_user
        ]);
    } else {
        $res = add_bet_database($con, $bet, $_SESSION["id"], $id);
        header("Location: /lot.php?id=" .$id);
    }
}


$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => $lot["title"],
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);

print($layout_content);



