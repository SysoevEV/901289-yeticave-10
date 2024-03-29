<?php
require_once("initial.php");
require_once("functions.php");
if (!empty($_SESSION)) {
    header("Location: /index.php");
    exit();
}

if (!$con) {
    $sign_up = include_template(
        "layout.php",
        ["content" => "Ошибка соединения с БД",
         "categories" => [],
         'title' => 'Регистрация аккаунта']
    );
    print($sign_up);
    die();
}

$errors = [];
$categories = get_categories($con);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $rules = [

        'email' => function () {
            return validate_email('email');
        },

        'password' => function () {
            return validate_filled('password', 50);
        },

        'name' => function () {
            return validate_filled('name', 50);
        },

        'message' => function () {
            return validate_filled('message', 200);
        },

    ];

    foreach ($form as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    $errors = array_filter($errors);

    if (check_free_email($con, $form, $errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (registration_date, email, username, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $password, $form['message']]);
        $res = mysqli_stmt_execute($stmt);
        if ($res && empty($errors)) {
            header("Location: /login.php");
            exit();
        }
    }
}
$page_content = include_template("sign-up.php", ["categories" => $categories, "errors" => $errors]);
$sign_up = include_template(
    "layout.php",
    ["content" => $page_content,
    "categories" => $categories,
    'title' => 'Регистрация аккаунта']
);

print($sign_up);
