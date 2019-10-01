<?php
require_once("initial.php");
require_once("functions.php");


if (!$con) {
    $add = include_template("layout.php", ["content" => "Нет соединения с базой данных", "categories" => $categories, 'title' => 'Добавление лота', "is_add" => true]);
    print $add;
    die();
}
if (empty($_SESSION)) {
    header("Location: /login.php?need_auth=true");
    exit();
}
$errors = [];
$categories = get_categories($con);
$cat_ids = array_column($categories, 'id');
$cat_names = array_column($categories, 'name');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [

        'lot-name' => function () {
            return validate_filled('lot-name', 50);
        },

        'category' => function () {
            return validate_category('category');
        },

        'message' => function () {
            return validate_filled('message', 700);
        },

        'lot-rate' => function () {
            return amount_valid('lot-rate');
        },

        'lot-step' => function () {
            return amount_valid('lot-step');
        },

        'lot-date' => function () {
            return date_valid('lot-date');
        }

    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
        $errors = array_filter($errors);
    }
    $in_category = in_array($_POST['category'], $cat_names);
    if (!$in_category) {
        $errors['category'] = "Такой категории нет";
    };
    if (!empty($_FILES['lot-img']['name'])) {
        $file_tmp = $_FILES['lot-img']['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
            $errors['lot-img'] = "Загрузите изображение в допустимом формате: jpg, jpeg, png";
        } else {
            $ext_mime_type = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            $file_name = uniqid() . '.' . $ext_mime_type[$file_type];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            move_uploaded_file($file_tmp, $file_path . $file_name);
            $_POST['lot-img'] = $file_url;
        }
    } else {
        $errors['lot-img'] = "Загрузите изображение";
    }
    if (count($errors)) {
        $page_content = include_template("add-lot.php", ["categories" => $categories, "errors" => $errors]);
        $add = include_template("layout.php", ["content" => $page_content, "categories" => $categories, 'title' => 'Добавление лота', "is_add" => true]);

        print $add;
    } else {
        $user_id_author = $_SESSION['user']['id'];
        $comb = array_combine($cat_names, $cat_ids);
        if (isset($_POST['category'])) {
            $_POST['category'] = $comb[$_POST['category']];
        };
        $sql = "INSERT INTO lots (user_id_author, date_create, NAME, category_id, description, start_price, bet_step, date_finish, img_ref) VALUES( $user_id_author , NOW(), ? , ?, ?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql, $_POST);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location:lot.php?id=" . $lot_id);
            exit();
        }
    }

} else {
    $page_content = include_template("add-lot.php", ["categories" => $categories, "errors" => []]);
    $add = include_template("layout.php", ["content" => $page_content, "categories" => $categories, 'title' => 'Добавление лота', "is_add" => true]);
    print $add;

}

?>

