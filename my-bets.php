<?php
require_once("initial.php");
require_once("functions.php");

if (!$con) {
    $my_bets = include_template(
        "layout.php",
        ["content" => "Ошибка соединения с БД",
        "categories" => [],
        'title' => 'Мои ставки']
    );
    print $my_bets;
    die();
}
$categories = get_categories($con);
$bets = [];

if (isset($_GET['id'])) {
    $bets = get_bets_user($_GET['id'], $con);
};

$page_content = include_template("my-bets.php", ["categories" => $categories, "bets" => $bets]);
$my_bets = include_template(
    "layout.php",
    ["content" => $page_content,
    "categories" => $categories,
    'title' => 'Мои ставки']
);

print $my_bets;
