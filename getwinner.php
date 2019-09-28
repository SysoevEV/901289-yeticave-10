<?php

require_once('vendor/autoload.php');

$lots_expired = get_lots_with_expired_time($con);

if ($lots_expired) {

    foreach ($lots_expired as $lot) {

        $winner = get_winner_from_bet($lot['id'], $con);

        if ($winner) {
            $sql = "UPDATE lots SET user_id_winner = " . $winner['ID'] . " WHERE id = " . $lot['id'];
            $result = mysqli_query($con, $sql);
            if ($result) {
                send_mail($winner);
            }
        }


    }

}

?>
