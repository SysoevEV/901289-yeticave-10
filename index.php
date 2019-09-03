<?php
    require_once("innersql.php");

    $is_auth = rand(0, 1);

    $user_name = 'Евгений Сысоев';

    $sql_list_categories="SELECT name, symbol_code FROM categories";

    $sql_list_lots="
        SELECT l.name, c.name, l.start_price, l.img_ref, l.date_finish FROM lots l
        JOIN categories c ON c.id=l.category_id
        ORDER BY l.date_create DESC";

    $categories = get_categories($sql_list_categories, $con);

    $items = get_lots($sql_list_lots, $con);

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
        $remain_minutes = floor($diff_time/60 - $remain_hours*60) ;
        $remain_hours = str_pad($remain_hours, 2, "0", STR_PAD_LEFT);
        $remain_minutes = str_pad($remain_minutes, 2, "0", STR_PAD_LEFT);
        return ["remain_hours" => $remain_hours, "remain_minutes" => $remain_minutes];
    }

    $page_content = include_template("main.php", ["categories" => $categories, "items" => $items,]);

    $layout_content = include_template("layout.php", ["content" => $page_content, "is_auth" => $is_auth, "user_name" => $user_name, "categories" => $categories, 'title' => 'Yeti Cave - Главная страница']);

    print $layout_content;
?>