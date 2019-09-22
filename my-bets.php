<?php
    session_start();
    require_once("innersql.php");
    require_once("functions.php");
    if(isset($_GET['id'])){
        $bets=get_bets_user($_GET['id'],$con);
        
    };



    $my_bets = include_template("my-bets.php", ["bets" =>$bets]);

    print $my_bets;



?>