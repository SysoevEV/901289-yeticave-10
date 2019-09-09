<?php
$con = mysqli_connect("127.0.0.1", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");

$query="SELECT name , symbol_code FROM categories where id=1";

$list = mysqli_query($con, $query);

$result = mysqli_fetch_all($list);

var_dump($result);


?>