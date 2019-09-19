<?php
    session_start();
    require_once("innersql.php");
    require_once("functions.php");
    $lots = [];
    $pages_count=0;
    $pages=[];
    $offset=0;
    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        $form = $_GET;
        $search = $_GET['search']?? '' ;
        $search = trim($search);
        if($search){
            $sql = "SELECT lots.id, lots.name, categories.NAME, lots.date_create, lots.description, lots.date_finish, lots.img_ref,
                    lots.start_price FROM lots  JOIN categories ON categories.id=lots.category_id
                    WHERE MATCH (lots.name, description)  AGAINST(?) ORDER BY lots.date_create DESC ";

            $stmt = db_get_prepare_stmt($con, $sql, [$search]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($result){$lots = mysqli_fetch_all($result, MYSQLI_ASSOC); }
        }
        $page_items = 9;

        $items_count = isset($lots)?count($lots):0;

        $pages_count = ceil($items_count / $page_items);

        $pages = range(1, $pages_count);

        $cur_page=$_GET['page']??1;


        $offset = ($cur_page - 1)*$page_items;


    }



    $lot = include_template("search.php", ["lots"=>$lots, "pages_count" =>$pages_count, "pages" =>$pages, "offset" =>$offset, "search" =>$search, "page_items" => $page_items]);

    print $lot;


?>