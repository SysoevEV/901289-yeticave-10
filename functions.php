<?php
//формирует шаблон
function include_template($name, array $data = [])
{
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

//возвращает оставшееся время до over_date
function over_date($over_date)
{
    $diff_time = strtotime($over_date) - strtotime("now");
    $remain_hours = floor($diff_time / 3600);
    $remain_minutes = floor($diff_time / 60 - $remain_hours * 60);
    $remain_hours = str_pad($remain_hours, 2, "0", STR_PAD_LEFT);
    $remain_minutes = str_pad($remain_minutes, 2, "0", STR_PAD_LEFT);
    return ["remain_hours" => $remain_hours, "remain_minutes" => $remain_minutes];
}

//добавляет ведущий 0 к числу
function format_price($num)
{
    $num = ceil($num);
    return number_format($num, 0, " ", " ") . " ₽";
}

//получает ассоциативный массив из sql запроса
function fetch_all($con, $query)
{
    $list = mysqli_query($con, $query);
    $result = mysqli_fetch_all($list, MYSQLI_ASSOC);
    return $result;
}

//получает ассоциативный массив из sql запроса
function fetch($con, $query)
{
    $list = mysqli_query($con, $query);
    $result = mysqli_fetch_assoc($list);
    return $result;
}

// получает список категорий
function get_categories($con)
{
    $query = "SELECT id, name , symbol_code FROM categories";
    return fetch_all($con, $query);
}

// получает список лотов
function get_lots($con)
{
    $query = "SELECT l.id, l.NAME, c.name, l.start_price, l.img_ref, l.date_finish FROM lots l
        JOIN categories c ON c.id=l.category_id WHERE l.date_finish > NOW()
        ORDER BY l.date_create DESC";
    return fetch_all($con, $query);
}

// получает список ставок для лота
function get_bets($lot_id, $con)
{
    $id = mysqli_real_escape_string($con, $lot_id);
    $query = "SELECT b.id, b.price, b.date_create, u.username
              FROM bets b JOIN users u ON u.id=b.user_id
              WHERE b.lot_id=". $id . " ORDER BY b.date_create DESC";
    return fetch_all($con, $query);
}

// получает список ставок, сделанных пользователем
function get_bets_user($user_id, $con)
{
    $id = mysqli_real_escape_string($con, $user_id);
    $query = "SELECT
              lots.id, lots.date_create, bets.DATE_CREATE, lots.date_finish, lots.name,
              lots.img_ref, bets.price, lots.user_id_winner, categories.NAME
              FROM lots JOIN bets ON bets.lot_id=lots.id JOIN categories ON lots.category_id=categories.id
              WHERE bets.user_id=" . $id;
    return fetch_all($con, $query);
}

// получает данные лота
function get_lot_data($id, $con)
{
    $id = mysqli_real_escape_string($con, $id);
    $query = "SELECT l.id, l.name, l.bet_step, c.NAME, l.start_price, l.img_ref, l.date_finish, l.description
              FROM lots l
              JOIN categories c ON c.id=l.category_id WHERE l.id=%s LIMIT 1";
    $query = sprintf($query, $id);
    return fetch($con, $query);
}

//находит лоты с истёкшим сроком
function get_lots_with_expired_time($con)
{
    $query = "SELECT id from lots WHERE user_id_winner IS NULL AND date_finish <= NOW()";

    return fetch_all($con, $query);
}

// сохраняет данных полей формы при отправке
function get_post_val($name)
{

    return $_POST[$name] ?? "";
}

// проверяет заполненность поля и его допустимую длину
function validate_filled($name, $maxlength = 1000)
{
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }

    if (strlen($_POST[$name]) > $maxlength) {
        return "Превышено количество символов";
    }
}

//валидирует формат даты
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

//валидирует суммы для ставок
function amount_valid($name, &$min_bet = 0)
{

    if (empty($_POST[$name]) && $_POST[$name] !== "0") {
        return "Это поле должно быть заполнено";
    }

    if (!is_numeric($_POST[$name])) {
        return "Введите числовое значение";
    }

    if ($_POST[$name] == 0) {
        return "Значение должно быть больше 0";
    }

    if ($_POST[$name] < $min_bet) {
        return "Значение должно быть больше " . $min_bet;
    }
}

//валидирует поле с датой
function date_valid($name)
{
    if (empty($_POST[$name])) {
        return "Выберите дату окончания торгов для лота";
    };
    if (is_date_valid($_POST[$name])) {
        $str_today = strtotime('today');
        $str_date = strtotime($_POST[$name]);
        $diff_time = $str_date - $str_today;
        if ($diff_time < 86400) {
            return "Дата завершения должна быть больше текущей даты хотя бы на один день";
        }
    } else {
        return "Неверный формат даты";
    }
}

//валидирует поле категорий
function validate_category($name)
{
    if ($_POST[$name] == "Выберите категорию") {
        return "Необходимо выбрать категорию";
    }
}

//подгатавливает sql запрос для отправки
function db_get_prepare_stmt($link, $sql, $data = [])
{
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
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
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

//валидирует email
function validate_email($email)
{
    if (validate_filled($email)) {
        return "Это поле должно быть заполнено";
    }
    if (!filter_var($_POST[$email], FILTER_VALIDATE_EMAIL)) {
        return "Неверный формат поля";
    }
}

//приводит в корректный вид запись о прошедшем времени с момента
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
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

//производит запись прошедшего времени
function get_passed_time($time)
{
    $now = strtotime("NOW");
    $dtcr = strtotime($time);
    $diff_seconds = $now - $dtcr;
    $diff_hours = round(($diff_seconds) / 3600);
    $diff_minutes = round(($diff_seconds - $diff_hours * 3600) / 60);
    $hours = get_noun_plural_form(
        $diff_hours,
        'час',
        'часа',
        'часов'
    );

    $minutes = get_noun_plural_form(
        $diff_minutes,
        'минута',
        'минуты',
        'минут'
    );

    if ($diff_hours < 1) {
        return $diff_minutes . " " . $minutes . " назад";
    };
    return $diff_hours . "  " . $hours . " " . $diff_minutes . " " . $minutes . " назад";
}

//находит победителя для лота среди сделанных ставок
function get_winner_from_bet($id, $con)
{
    $id = mysqli_real_escape_string($con, $id);
    $id = mysqli_real_escape_string($con, $id);
    $query = "SELECT users.ID, users.email, users.username, lots.user_id_winner, lots.NAME, lots.id
              FROM bets JOIN users ON bets.user_id=users.id JOIN lots ON lots.user_id_author=users.id
              WHERE bets.lot_id=" . $id . " ORDER BY bets.date_create DESC LIMIT 1";
    return fetch($con, $query);
}

//отправляет email
function send_mail($winData)
{

    $userName = $winData['username'];
    $email = $winData['email'];
    $lot = $winData['id'];
    $title = $winData['NAME'];

    $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");

    $mailer = new Swift_Mailer($transport);
    $message = new Swift_Message("Ваша ставка победила");
    $message->setFrom(['keks@phpdemo.ru' => 'Sysoev']);
    $message->setTo([$email => $userName]);
    $emailBody = include_template('email.php', ['userName' => $userName, 'lot_id' => $lot, 'title' => $title]);
    $message->setBody($emailBody, 'text/html');
    $result = $mailer->send($message);

    return;
}
