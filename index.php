<?php
    session_start();
    require_once("innersql.php");
    require_once("functions.php");

    $categories = get_categories($con);

    $items = get_lots($con);

    $page_content = include_template("main.php", ["categories" => $categories, "items" => $items,]);

    $layout_content = include_template("layout.php", ["content" => $page_content, "categories" => $categories, 'title' => 'Yeti Cave - Главная страница']);

    print $layout_content;

?>