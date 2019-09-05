<?php
    require_once("innersql.php");
    require_once("functions.php");

    $is_auth = rand(0, 1);

    $user_name = 'Евгений Сысоев';

    $categories = get_categories($con);

    $items = get_lots($con);

    $page_content = include_template("main.php", ["categories" => $categories, "items" => $items,]);

    $layout_content = include_template("layout.php", ["content" => $page_content, "is_auth" => $is_auth, "user_name" => $user_name, "categories" => $categories, 'title' => 'Yeti Cave - Главная страница']);

    print $layout_content;
?>