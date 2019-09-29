<?php
require_once("initial.php");
require_once("functions.php");
if (!empty($_SESSION)) {
    http_response_code(403);
    return;
}

$errors = [];

if ($con) {

    $categories = get_categories($con);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST;
        $rules = [

            'email' => function () {
                return validate_email('email');
            },

            'password' => function () {
                return validate_filled('password');
            },

            'name' => function () {
                return validate_filled('name');
            },

            'message' => function () {
                return validate_filled('message');
            },

        ];

        foreach ($form as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }

        }
        $errors = array_filter($errors);

        if (empty($errors)) {
            $email = mysqli_real_escape_string($con, $form['email']);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {

                $errors['email'] = 'Пользователь с этим email уже зарегистрирован';


            } else {

                $password = password_hash($form['password'], PASSWORD_DEFAULT);


                $sql = 'INSERT INTO users (registration_date, email, username, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';


                $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $password, $form['message']]);

                $res = mysqli_stmt_execute($stmt);


                if ($res && empty($errors)) {

                    header("Location: /pages/login.html");
                    exit();
                }

            }

        }


    }
    $page_content = include_template("sign-up.php", ["categories" => $categories, "errors" => $errors]);
    $sign_up = include_template("layout.php", ["content" => $page_content, "categories" => $categories, 'title' => 'Регистрация аккаунта']);

    print($sign_up);
} else {

    $sign_up = include_template("layout.php", ["content" => "Ошибка соединения с БД", "categories" => [], 'title' => 'Регистрация аккаунта']);

    print($sign_up);
}


?>
