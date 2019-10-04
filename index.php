<?php

require_once("initial.php");
require_once("functions.php");
require_once("getwinner.php");

if (!$con) {
    $layout_content = include_template(
        "layout.php",
        ["content" => "Нет соединения с базой данных",
        "categories" => [],
        'title' => 'Yeti Cave - Главная страница',
        "is_index" => '']
    );
    print $layout_content;
    die();
}

$categories = get_categories($con);

$lots = get_lots($con);

$page_content = include_template("main.php", ["categories" => $categories, "lots" => $lots,]);

$layout_content = include_template(
    "layout.php",
    ["content" => $page_content,
     "categories" => $categories,
      'title' => 'Yeti Cave - Главная страница',
       "is_index" => true]
);

print $layout_content;
