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
$page_content = include_template("main-sign-up.php", [
    "header" => $header,
    "categories" => $categories
]);

if ($is_auth) {
    $page_content = include_template("main-403.php", [
        "header" => $header,
        "categories" => $categories
    ]);
    $layout_content = include_template("layout.php", [
        "content" => $page_content,
        "categories" => $categories,
        "title" => "Доступ запрещен",
        "is_auth" => $is_auth,
        "user_name" => $user_name
    ]);
    print($layout_content);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ["email", "password", "name", "message"];
    $errors = [];

    $rules = [
        "email" => function($value) {
            return validate_email($value);
        },
        "password" => function($value) {
            return validate_length ($value, 6, 8);
        },
        "message" => function($value) {
            return validate_length ($value, 12, 1000);
        }
    ];

    $user = filter_input_array(INPUT_POST,
    [
        "email"=>FILTER_SANITIZE_SPECIAL_CHARS,
        "password"=>FILTER_SANITIZE_SPECIAL_CHARS,
        "name"=>FILTER_SANITIZE_SPECIAL_CHARS,
        "message"=>FILTER_SANITIZE_SPECIAL_CHARS
    ], true);

    foreach ($user as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }
        if (in_array($field, $required) && empty($value)) {
            $errors[$field] = "Поле $field нужно заполнить";
        }
    }

    $errors = array_filter($errors);


    if (count($errors)) {
        $page_content = include_template("main-sign-up.php", [
            "categories" => $categories,
            "header" => $header,
            "user" => $user,
            "errors" => $errors
        ]);
    } else {
        $users_data = get_users_data ($con);
        $emails = array_column($users_data, "email");
        $names = array_column($users_data, "user_name");
        if (in_array($user["email"], $emails)) {
            $errors["email"] = "Пользователь с таким е-mail уже зарегистрирован";
        }
        if (in_array($user["name"], $names)) {
            $errors["name"] = "Пользователь с таким именем уже зарегистрирован";
        }

        if (count($errors)) {
            $page_content = include_template("main-sign-up.php", [
                "categories" => $categories,
                "header" => $header,
                "user" => $user,
                "errors" => $errors
            ]);
        } else {
            $result = add_user_database($con, $user);
            if ($result) {
                header("Location: /login.php");
            } else {
                $error = mysqli_error($con);
            }
        }
    }
}


$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => "Регистрация",
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);



print($layout_content);


