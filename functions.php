<?php

    function include_template($name, $data) {  //формирует шаблон
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

    function over_date($over_date) {      //возвращает оставшееся время до over_date
        $diff_time = strtotime($over_date) - strtotime("now");
        $remain_hours = floor($diff_time / 3600);
        $remain_minutes = floor($diff_time/60 - $remain_hours*60) ;
        $remain_hours = str_pad($remain_hours, 2, "0", STR_PAD_LEFT);
        $remain_minutes = str_pad($remain_minutes, 2, "0", STR_PAD_LEFT);
        return ["remain_hours" => $remain_hours, "remain_minutes" => $remain_minutes];
    }

    function format_price($num) {  //добавляет ведущий 0 к числу
        $num = ceil($num);
        return number_format($num, 0, " ", " ") . " ₽";
    }

    function fetch($con, $query) {  //получает ассоциативный массив из sql запроса
        $list = mysqli_query($con, $query);
        $result = mysqli_fetch_all($list, MYSQLI_ASSOC);
        return $result;
    }

    function get_categories($query, $con) { // получает список категорий
        return fetch($con, $query);
    }

    function get_lots($query, $con) { // получает список лотов
        return fetch($con, $query);
    }

    function get_lot_data($query, $con) { // получает данные лота
        $list = mysqli_query($con, $query);
        $result = mysqli_fetch_assoc($list);
        return $result;
    }


?>