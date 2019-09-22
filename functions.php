<?php

    function include_template($name, array $data = []) {
        $name = 'templates/' . $name;
        $result = '';

        if (!is_readable($name)) {
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

    function fetch_all($con, $query) {  //получает ассоциативный массив из sql запроса
        $list = mysqli_query($con, $query);
        $result = mysqli_fetch_all($list, MYSQLI_ASSOC);
        return $result;
    }
    function fetch($con, $query) {  //получает ассоциативный массив из sql запроса
        $list = mysqli_query($con, $query);
        $result = mysqli_fetch_assoc($list);
        return $result;
    }


    function get_categories($con) { // получает список категорий
        $query="SELECT id, name , symbol_code FROM categories";
        return fetch_all($con, $query);
     }

    function get_lots($con) { // получает список лотов
        $query="SELECT l.id, l.name, c.name, l.start_price, l.img_ref, l.date_finish FROM lots l
        JOIN categories c ON c.id=l.category_id
        ORDER BY l.date_create DESC";
        return fetch_all($con, $query);
    }

    function get_bets($lot_id, $con) { // получает список ставок для лота
        $id = mysqli_real_escape_string($con, $lot_id);
        $query="SELECT b.id, b.price, b.date_create, u.username FROM bets b JOIN users u ON u.id=b.user_id WHERE b.lot_id=".$id." ORDER BY b.date_create DESC";
        return fetch_all($con, $query);
    }

    function get_bets_user($user_id, $con) { // получает список ставок для лота
        $id = mysqli_real_escape_string($con, $user_id);
        $query="SELECT lots.date_create, bets.DATE_CREATE, lots.date_finish, lots.name, lots.img_ref, bets.price, categories.NAME  FROM lots JOIN bets ON bets.lot_id=lots.id JOIN categories ON lots.category_id=categories.id  WHERE bets.user_id=".$id;
        return fetch_all($con, $query);
    }

    function get_lot_data($id, $con) { // получает данные лота
                $id = mysqli_real_escape_string($con, $id);
                $query="SELECT l.id, l.name, l.bet_step, c.NAME, l.start_price, l.img_ref, l.date_finish, l.description FROM lots l
                JOIN categories c ON c.id=l.category_id WHERE l.id=%s LIMIT 1";
                $query=sprintf($query,$id);
        return fetch($con, $query);

    }



    function get_post_val($name) { // для сохранения данных полей формы при отправке

        return $_POST[$name] ?? "";

    }

    function validate_filled($name) { // для сохранения данных полей формы при отправке
        if(empty($_POST[$name])){
            return "Это поле должно быть заполнено";
        }

    }

    function start_price_valid($name) {
         if(empty($_POST[$name]) &&  $_POST[$name]!=="0" ){
            return "Это поле должно быть заполнено";
        }
        if(!is_numeric($_POST[$name])) {return "Введите числовое значение";}
        if($_POST[$name]<=0){return "Цена должна быть больше 0";}
    }

    function lot_step_valid($name) {
        if(empty($_POST[$name]) &&  $_POST[$name]!=="0"){
            return "Это поле должно быть заполнено";
        }
        if(!is_numeric($_POST[$name])) {return "Введите числовое значение";}
        if($_POST[$name]<=0){return "Значение должно быть больше 0";}



    }

    function is_date_valid(string $date) : bool {
        $format_to_check = 'Y-m-d';
        $dateTimeObj = date_create_from_format($format_to_check, $date);

        return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;

    }

    function bet_valid($name, &$min_bet) {

         if(empty($_POST[$name]) &&  $_POST[$name]!=="0" ){
            return "Это поле должно быть заполнено";
        }

        if(!is_numeric($_POST[$name])) {return "Введите числовое значение";}
        if($_POST[$name]<=$min_bet){return "Цена должна быть больше " . $min_bet;}
    }

    function date_valid($name){
        if(empty($_POST[$name])){return "Выберите дату окончания торгов для лота"; };
        if(is_date_valid($_POST[$name])){
            $str_today=strtotime('today');
            $str_date=strtotime($_POST[$name]);
            $diff_time=$str_date-$str_today;
            if($diff_time < 86400){return "Дата завершения должна быть больше текущей даты хотя бы на один день";}
        }else{
            return "Неверный формат даты";
        }
    }


    function validate_category($name) {
         if($_POST[$name]=="Выберите категорию"){return "Необходимо выбрать категорию";}

}

    function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

    function validate_email($email){
       if( validate_filled($email)){ return "Это поле должно быть заполнено";}
       if(!filter_var($_POST[$email], FILTER_VALIDATE_EMAIL)){return "Неверный формат поля";}

    }

function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

function get_passed_time($time) {
    $now=strtotime("NOW");
    $dtcr=strtotime($time);
    $diff_seconds=$now-$dtcr;
    $diff_hours=round(($diff_seconds)/3600);
    $diff_minutes=round(($diff_seconds-$diff_hours*3600)/60);
    $hours= get_noun_plural_form(
           $diff_hours,
           'час',
           'часа',
           'часов'
     );

    $minutes= get_noun_plural_form(
           $diff_minutes,
           'минута',
           'минуты',
           'минут'
     );

    if($diff_hours<1){
        return  $diff_minutes . " " . $minutes . " назад";
    };
    return $diff_hours . "  " . $hours . " " . $diff_minutes . " " . $minutes . " назад";
}


?>