<?php
$is_auth = rand(0, 1);
$user_name = 'Евгений Сысоев'; // укажите здесь ваше имя
$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"]; //массив категорий товаров
$items = [ // массив данных объявлений товаров
["name" => "2014 Rossignol District Snowboard", "category" => "Доски и лыжи", "price" => "10999", "url" => "img/lot-1.jpg"], ["name" => "DC Ply Mens 2016/2017 Snowboard", "category" => "Доски и лыжи", "price" => "159999", "url" => "img/lot-2.jpg"], ["name" => "Крепления Union Contact Pro 2015 года размер L/XL", "category" => "Крепления", "price" => "8000", "url" => "img/lot-3.jpg"], ["name" => "Ботинки для сноуборда DC Mutiny Charocal", "category" => "Ботинки", "price" => "10999", "url" => "img/lot-4.jpg"], ["name" => "Куртка для сноуборда DC Mutiny Charocal", "category" => "Одежда", "price" => "7500", "url" => "img/lot-5.jpg"], ["name" => "Маска Oakley Canopy", "category" => "Разное", "price" => "5400", "url" => "img/lot-6.jpg"],];

function format_price($num) {
    $num = ceil($num);
    return number_format($num, 0, " ", " ") . " ₽";
}

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

$page_content=include_template("main.php", [
"categories" => $categories,
"items" => $items,


]);

$layout_content=include_template("layout.php", [
"content" => $page_content,
"is_auth" => $is_auth,
"user_name" => $user_name,
"categories" => $categories,
'title' => 'Yeti Cave - Главная страница'

]);

print $layout_content;
?>