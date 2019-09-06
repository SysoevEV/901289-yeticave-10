<?php
    require_once("innersql.php");
    require_once("functions.php");

    if(isset($_GET['id'])){

        $lot_data=get_lot_data($_GET['id'], $con);

        if(is_null($lot_data)){
            http_response_code(404);
            return;
        }

    }else {
        http_response_code(404);
        return;
    };

    $lot = include_template("lot.php", ["lot_data" => $lot_data]);

    print $lot;


?>