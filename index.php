<?php
    require_once("innersql.php");
    require_once("functions.php");

    $is_auth = rand(0, 1);

    $user_name = 'Евгений Сысоев';

    $sql_list_categories="SELECT name, symbol_code FROM categories";

    $sql_list_lots="
        SELECT l.id, l.name, c.name, l.start_price, l.img_ref, l.date_finish FROM lots l
        JOIN categories c ON c.id=l.category_id
        ORDER BY l.date_create DESC";

    $categories = get_categories($sql_list_categories, $con);

    $items = get_lots($sql_list_lots, $con);

    $page_content = include_template("main.php", ["categories" => $categories, "items" => $items,]);

    $layout_content = include_template("layout.php", ["content" => $page_content, "is_auth" => $is_auth, "user_name" => $user_name, "categories" => $categories, 'title' => 'Yeti Cave - Главная страница']);

    print $layout_content;
?>