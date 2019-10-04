<?php
require_once("initial.php");
require_once("functions.php");

if (!$con) {
    $lot = include_template(
        "layout.php",
        ["content" => "Ошибка соединения с БД",
        "categories" => [],
        'title' => 'Просмотр лота',
        'lot_active' =>false,
        'show_bet_block'=> false ]
    );
    print $lot;
    die();
}
$errors = [];
$categories = get_categories($con);
$lot_data = [];
$bets = [];
$lot_active=true;
$show_bet_block=false;
if (isset($_GET['id'])) {
    $lot_data = get_lot_data($_GET['id'], $con);
    $bets = get_bets($_GET['id'], $con);
    if ($lot_data) {
        $lot_active=(strtotime("now")-strtotime($lot_data['date_finish']))<0?true:false;
    }
    if (isset($_SESSION['user'])) {
        $show_bet_block=($lot_data['user_id_author']!==$_SESSION['user']['id']) && $lot_active;
        foreach ($bets as $bet => $val) {
            if ($_SESSION['user']['id']===$val['user_id']) {
                $show_bet_block=false;
            }
        }
    }

    if (is_null($lot_data)) {
        $lot = include_template(
            "layout.php",
            ["content" => "Такого лота не существует",
            "categories" => $categories,
            'title' => 'Просмотр лота',
            'lot_active' =>$lot_active,
            'show_bet_block' => $show_bet_block ]
        );
        print $lot;
        return;
    }
} else {
    header("Location: /index.php");
    exit();
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $min_bet = $lot_data['start_price'] + $lot_data['bet_step'];
    $errors['cost'] = amount_valid('cost', $min_bet);
    if (!$errors['cost']) {
        $user_id = $_SESSION['user']['id'];
        $lot_id = $_GET['id'];
        $price = $_POST['cost'];
        $bets_data_prepare = [$user_id, $lot_id, $price];
        if (bets_update($bets_data_prepare, $price, $lot_id, $con)) {
            $bets = get_bets($_GET['id'], $con);
            $lot_data = get_lot_data($_GET['id'], $con);
        }
    }
}
$page_content = include_template(
    "lot.php",
    ["categories" => $categories,
    "lot_data" => $lot_data,
    "bets" => $bets,
    "errors" => $errors,
    'lot_active' =>$lot_active,
    'show_bet_block' => $show_bet_block ]
);
$lot = include_template(
    "layout.php",
    ["content" => $page_content,
    "categories" => $categories,
    'title' => 'Просмотр лота']
);

print $lot;
