<?php
    $is_auth = rand(0, 1);

    $user_name = 'Евгений Сысоев';

    $categories = [
        "Доски и лыжи",
        "Крепления",
        "Ботинки",
        "Одежда",
        "Инструменты",
        "Разное"
    ];

    $items = [
        ["name" => "2014 Rossignol District Snowboard", "category" => "Доски и лыжи", "price" => "10999", "url" => "img/lot-1.jpg", "end_time" => "2019-08-31"],
        ["name" => "DC Ply Mens 2016/2017 Snowboard", "category" => "Доски и лыжи", "price" => "159999", "url" => "img/lot-2.jpg", "end_time" => "2019-09-01"],
        ["name" => "Крепления Union Contact Pro 2015 года размер L/XL", "category" => "Крепления", "price" => "8000", "url" => "img/lot-3.jpg", "end_time" => "2019-08-30"],
        ["name" => "Ботинки для сноуборда DC Mutiny Charocal", "category" => "Ботинки", "price" => "10999", "url" => "img/lot-4.jpg", "end_time" => "2019-09-05"],
        ["name" => "Куртка для сноуборда DC Mutiny Charocal", "category" => "Одежда", "price" => "7500", "url" => "img/lot-5.jpg", "end_time" => "2019-10-11"],
        ["name" => "Маска Oakley Canopy", "category" => "Разное", "price" => "5400", "url" => "img/lot-6.jpg", "end_time" => "2019-09-09"],
    ];

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

    function over_date($over_date) {
        $diff_time = strtotime($over_date) - strtotime("now");
        $remain_hours = floor($diff_time / 3600);
        if ($remain_hours < 10) {
            $remain_hours = str_pad($remain_hours, 2, "0", STR_PAD_LEFT);
        }
        $remain_minutes = 59 - date("i");
        if ($remain_minutes < 10) {
            $remain_minutes = str_pad($remain_minutes, 2, "0", STR_PAD_LEFT);
        }
        return [$remain_hours, $remain_minutes];
    }

    $page_content = include_template("main.php", ["categories" => $categories, "items" => $items,]);

    $layout_content = include_template("layout.php", ["content" => $page_content, "is_auth" => $is_auth, "user_name" => $user_name, "categories" => $categories, 'title' => 'Yeti Cave - Главная страница']);

    print $layout_content;
?>