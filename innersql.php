<?php
    $con = mysqli_connect("127.0.0.1", "root", "", "yeticave");

    mysqli_set_charset($con, "utf8");

    function fetch($con, $query) {
        $list = mysqli_query($con, $query);
        $result = mysqli_fetch_all($list, MYSQLI_ASSOC);
        return $result;
    }

    function get_categories($query, $con) {
        return fetch($con, $query);
    }

    function get_lots($query, $con) {
        return fetch($con, $query);
    }
?>