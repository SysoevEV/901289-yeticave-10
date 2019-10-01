<?php
require_once("initial.php");
require_once("functions.php");

$pages_count = 0;
$pages = [];
$offset = 0;
$lots = [];

if (!$con) {
    $page_content = include_template("search.php", ["categories" => [], "lots" => [], "pages_count" => 0, "pages" => [], "offset" => 0, "search" => '', "page_items" => 0]);
    $search = include_template("layout.php", ["content" => "Нет соединения с базой данных", "categories" => [], "title" => 'Поиск лота']);
    print $search;
    die();
}

$categories = get_categories($con);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $search = $_GET['search'] ?? '';
    $search = trim($search);
    $search = strip_tags($search);
    if ($search) {
        $sql = "SELECT lots.id, lots.name, categories.NAME, lots.date_create, lots.description, lots.date_finish, lots.img_ref,
                        lots.start_price FROM lots  JOIN categories ON categories.id=lots.category_id
                        WHERE MATCH (lots.name, description)  AGAINST(?) ORDER BY lots.date_create DESC ";

        $stmt = db_get_prepare_stmt($con, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }

    if (isset($_GET['cat_id'])) {
        $sql = "SELECT lots.id, lots.name, categories.NAME, lots.date_create, lots.description, lots.date_finish, lots.img_ref,
                        lots.start_price FROM lots  JOIN categories ON categories.id=lots.category_id
                        WHERE category_id=" . $_GET['cat_id'] . " ORDER BY lots.date_create DESC ";

        $result = mysqli_query($con, $sql);
        if ($result) {
            $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

    }

    $page_items = 9;

    $items_count = isset($lots) ? count($lots) : 0;

    $pages_count = ceil($items_count / $page_items);

    $pages = range(1, $pages_count);

    $cur_page = $_GET['page'] ?? 1;


    $offset = ($cur_page - 1) * $page_items;


    $page_content = include_template("search.php", ["categories" => $categories, "cur_page" => $cur_page, "lots" => $lots, "pages_count" => $pages_count, "pages" => $pages, "offset" => $offset, "search" => $search, "page_items" => $page_items]);
    $search = include_template("layout.php", ["content" => $page_content, "categories" => $categories, "title" => 'Поиск лота']);

    print $search;
}


?>
