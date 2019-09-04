<?php
    require_once("innersql.php");
    require_once("functions.php");

    if(isset($_GET['id'])){

        $sql_lots_items="SELECT l.id, l.name, c.NAME, l.start_price, l.img_ref, l.date_finish, l.description FROM lots l
                         JOIN categories c ON c.id=l.category_id WHERE l.id=" .$_GET['id'];

        $lot_data=get_lot_data($sql_lots_items, $con);

        if(is_null($lot_data)){ http_response_code(404);}

    }else {
        http_response_code(404);
    };

    $lot = include_template("lot.php", ["lot_data" => $lot_data]);

    print $lot;


?>