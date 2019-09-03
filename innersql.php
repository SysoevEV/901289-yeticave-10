<?php


    function getSql($query) {

        $con=mysqli_connect("127.0.0.1", "root", "", "yeticave");

        mysqli_set_charset($con, "utf8");

        $list = mysqli_query($con, $query);

        $result = mysqli_fetch_all($list, MYSQLI_ASSOC);

        return $result;

    }


?>