<?php
require_once("initial.php");
require_once("functions.php");

if ($con) {
    $errors = [];
    $categories = get_categories($con);

    if (isset($_GET['id'])) {

        $lot_data = get_lot_data($_GET['id'], $con);
        $bets = get_bets($_GET['id'], $con);


        if (is_null($lot_data)) {
            http_response_code(404);
            return;
        }

    } else {
        http_response_code(404);
        return;


    };

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];
        $min_bet = $lot_data['start_price'] + $lot_data['bet_step'];
        $errors['cost'] = bet_valid('cost', $min_bet);
        if (!$errors['cost']) {
            $user_id = $_SESSION['user']['id'];
            $lot_id = $_GET['id'];
            $price = $_POST['cost'];
            $sql = "INSERT INTO bets (user_id, lot_id, date_create, price) VALUES( $user_id, $lot_id, NOW(), $price)";
            $stmt = db_get_prepare_stmt($con, $sql, []);
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                $sql = "UPDATE lots SET start_price=" . $price . " WHERE id=" . $lot_id;

                $res = mysqli_query($con, $sql);
                if ($res) {

                    $bets = get_bets($_GET['id'], $con);
                    $lot_data = get_lot_data($_GET['id'], $con);
                }
            }

        }


    }
    $page_content = include_template("lot.php", ["categories" => $categories, "lot_data" => $lot_data, "bets" => $bets, "errors" => $errors]);
    $lot = include_template("layout.php", ["content" => $page_content, "categories" => $categories, 'title' => 'Просмотр лота']);
    print $lot;
} else {

    $lot = include_template("layout.php", ["content" => "Ошибка соединения с БД", "categories" => [], 'title' => 'Просмотр лота']);
    print $lot;
}


?>
