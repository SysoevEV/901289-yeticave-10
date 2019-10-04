<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
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
/**
 * Возвращает оставшееся время от данного момента до over_date
 *
 * @param string $over_date Строка с данными о времени
 *
 * @return array Массив вида [часы, минуты]
 */
function over_date($over_date)
{
    $diff_time = strtotime($over_date) - strtotime("now");
    $remain_hours = floor($diff_time / 3600);
    $remain_minutes = floor($diff_time / 60 - $remain_hours * 60);
    $remain_hours = str_pad($remain_hours, 2, "0", STR_PAD_LEFT);
    $remain_minutes = str_pad($remain_minutes, 2, "0", STR_PAD_LEFT);
    return ["remain_hours" => $remain_hours, "remain_minutes" => $remain_minutes];
}

/**
 * Возвращает отформатированную сумму
 * с символом ₽ в конце.
 *
 * @param int $num сумма
 *
 * @return string вида 1 000 ₽
 */
function format_price($num)
{
    $num = ceil($num);
    return number_format($num, 0, " ", " ") . " ₽";
}
/**
 * Принимает ресурс соединения и SQL-запрос
 * Возвращает двумерный массив из запроса
 *
 * @param resource $con
 * @param string $query
 *
 * @return array $result
 */
function fetch_all($con, $query)
{
    $list = mysqli_query($con, $query);
    $result = mysqli_fetch_all($list, MYSQLI_ASSOC);
    return $result;
}
/**
 * Принимает ресурс соединения и SQL-запрос
 * Возвращает ассоциативный массив из запроса
 *
 * @param resource $con
 * @param string $query
 *
 * @return array $result
 */
function fetch($con, $query)
{
    $list = mysqli_query($con, $query);
    $result = mysqli_fetch_assoc($list);
    return $result;
}

/**
 * Принимает ресурс соединения и возвращает список категорий в виде двумерного массива
 *
 * @param resource $con
 *
 * @return array
 */
function get_categories($con):array
{
    $query = "SELECT id, name , symbol_code FROM categories";
    return fetch_all($con, $query);
}
/**
 * Принимает ресурс соединения и возвращает список лотов в виде двумерного массива
 *
 * @param resource $con
 *
 * @return array
 */
function get_lots($con)
{
    $query = "SELECT l.id, l.NAME, c.name, l.start_price, l.img_ref, l.date_finish FROM lots l
        JOIN categories c ON c.id=l.category_id WHERE l.date_finish > NOW()
        ORDER BY l.date_create DESC";
    return fetch_all($con, $query);
}
/**
 * Принимает ресурс соединения и id лота.
 * Возвращает список ставок для лота в виде двумерного массива
 *
 * @param resource $con
 * @param int $lot_id
 *
 * @return array
 */
function get_bets($lot_id, $con)
{
    $id = mysqli_real_escape_string($con, $lot_id);
    $query = "SELECT b.id, b.price, b.date_create, b.user_id, u.username
              FROM bets b JOIN users u ON u.id=b.user_id
              WHERE b.lot_id=". $id . " ORDER BY b.date_create DESC";
    return fetch_all($con, $query);
}

/**
 * Принимает ресурс соединения и id юзера.
 * Возвращает список ставок для юзера в виде двумерного массива
 *
 * @param resource $con
 * @param int $user_id
 *
 * @return array
 */
function get_bets_user($user_id, $con)
{
    $id = mysqli_real_escape_string($con, $user_id);
    $query = "SELECT
              lots.id, lots.date_create, bets.DATE_CREATE, lots.date_finish, lots.name,
              lots.img_ref, bets.price, lots.user_id_winner, categories.NAME, users.contacts
              FROM lots JOIN bets ON bets.lot_id=lots.id JOIN categories ON lots.category_id=categories.id
              JOIN users ON lots.user_id_author=users.id
              WHERE bets.user_id=" . $id . " ORDER BY bets.date_create DESC " ;
    return fetch_all($con, $query);
}
/**
 * Обновляет данные о цене лота и формирует новую ставку на него
 * Принимает ресурс соединения, массив данных для ставки, новую цену и id лота.
 * Возвращает true в случае успешной процедуры обновления цены и формирования ставки.
 *
 * @param resource $con
 * @param array $bets_data_prepare
 * @param int $lot_id
 * @param int $price

 * @return bool
 */
function bets_update($bets_data_prepare, $price, $lot_id, $con)
{
    $sql = "INSERT INTO bets (user_id, lot_id, price, date_create) VALUES( ?, ?, ?, NOW())";
    $stmt = db_get_prepare_stmt($con, $sql, $bets_data_prepare);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $sql = "UPDATE lots SET start_price=" . $price . " WHERE id=" . $lot_id;
        $res = mysqli_query($con, $sql);
        return $res;
    }
}

/**
 * Принимает ресурс соединения и id лота.
 * Возвращает список ставок для юзера в виде ассоциативного массива
 *
 * @param resource $con
 * @param int $id
 *
 * @return array
 */
function get_lot_data($id, $con)
{
    $id = mysqli_real_escape_string($con, $id);
    $query = "SELECT l.id, l.name, l.bet_step, c.NAME, l.start_price, l.img_ref, l.date_finish, l.description,
              l.user_id_author
              FROM lots l
              JOIN categories c ON c.id=l.category_id WHERE l.id=%s LIMIT 1";
    $query = sprintf($query, $id);
    return fetch($con, $query);
}

/**
 * Принимает ресурс соединения.
 * Возвращает список лотов с истёкшим сроком в виде двумерного массива
 *
 * @param resource $con
 *
 * @return array
 */
function get_lots_with_expired_time($con)
{
    $query = "SELECT id from lots WHERE user_id_winner IS NULL AND date_finish <= NOW()";

    return fetch_all($con, $query);
}

/**
 * Проверяет свободен ли email для регистрации нового пользователя
 * Принимает ресурс соединения, массив $_POST и массив $errors
 * Возвращает true если email не занят, в проитивном случае false
 *
 * @param resource $con
 * @param array $form
 * @param array $con
 *
 * @return bool
 */
function check_free_email($con, $form, &$errors)
{
    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
            echo $errors['email'];
            return false;
        }
        if ($res) {
            return true;
        }
    }
    return false;
}
/**
 * Принимает данные из поля формы.
 * Возвращает значение из поля с данными.
 *
 * @param string $name
 *
 * @return string
 */
function get_post_val($name)
{
    return $_POST[$name] ?? "";
}
/**
 * Проверяет поле формы на заполненность и допустимую длину.
 * При наличии ошибок возвращает строку с текстом ошибки.
 *
 * @param string $name
 * @param int $max_length
 *
 * @return string
 */
function validate_filled($name, $max_length = 1000)
{
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }

    if (strlen($_POST[$name]) > $max_length) {
        return "Превышено количество символов";
    }
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Проверяет поле для ставок и
 * возвращает текст ошибки, либо ничего
 *
 *
 * @param string $name Имя поля
 * @param int $min_bet минимально допустимая ставка
 *
 * @return string при обнаружении ошибки
 */
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

/**
 * Проверяет поле с датой, производит валидацию и возвращает текст
 * ошибки либо ничего.
 *
 * @param string $name Имя поля
 *
 * @return string при обнаружении ошибки
 */
function date_valid($name)
{
    if (empty($_POST[$name])) {
        return "Выберите дату окончания торгов для лота";
    };
    if (!is_date_valid($_POST[$name])) {
        return "Неверный формат даты";
    }
    $str_today = strtotime('today');
    $str_date = strtotime($_POST[$name]);
    $diff_time = $str_date - $str_today;
    if ($diff_time < 86400) {
        return "Дата завершения должна быть больше текущей даты хотя бы на один день";
    }
}

/**
 * Проверяет выбрана ли категория
 * и возвращает текст ошибки либо ничего.
 *
 * @param string $name Имя поля
 *
 * @return string при обнаружении ошибки
 */
function validate_category($name)
{
    if ($_POST[$name] == "Выберите категорию") {
        return "Необходимо выбрать категорию";
    }
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
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

/**
 * Проверяет поле с email, производит валидацию и возвращает текст
 * ошибки либо ничего.
 *
 * @param string $name Имя поля
 *
 * @return string при обнаружении ошибки
 */
function validate_email($email)
{
    if (validate_filled($email)) {
        return "Это поле должно быть заполнено";
    }
    if (!filter_var($_POST[$email], FILTER_VALIDATE_EMAIL)) {
        return "Неверный формат поля";
    }
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
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

/**
 * Рассчитывает время, прошедшее с момента размещения ставки
 * и возвращает строку вида: Х часов Х минут назад правильно склоняя её
 *
 * @param string $time Время формата ГГГГ-ММ-ДД ЧЧ:ММ:СС
 *
 * @return string
 */
function get_passed_time($time)
{
    $now = strtotime("NOW");
    $dtcr = strtotime($time);
    $diff_seconds = $now - $dtcr;
    $diff_hours = floor(($diff_seconds) / 3600);
    $diff_minutes =floor(($diff_seconds%3600)/60);
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

/**
 * Определяет победителя для лота
 * и возвращает массив с данными о победителе.
 *
 * @param  resource $con
 * @param  int $id
 *
 * @return array
 */
function get_winner_from_bet($id, $con)
{
    $id = mysqli_real_escape_string($con, $id);
    $id = mysqli_real_escape_string($con, $id);
    $query = "SELECT users.ID, users.email, users.username, lots.user_id_winner, lots.NAME, lots.id
              FROM bets JOIN users ON bets.user_id=users.id JOIN lots ON lots.user_id_author=users.id
              WHERE bets.lot_id=" . $id . " ORDER BY bets.date_create DESC LIMIT 1";
    return fetch($con, $query);
}

/**
 * Отправляет email победителю
 *
 * @param  array $winData
 *
 * @return
 */
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
