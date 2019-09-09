<?php
    require_once("innersql.php");
    require_once("functions.php");

    $categories=get_categories($con);
    $cat_ids=array_column($categories, 'id');
    $cat_names=array_column($categories, 'name');

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $errors=[];
        $requires_fields=['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date', 'lot-img'];

        $rules= [

            'lot-name' =>function(){
                return validate_filled('lot-name');
            },

            'category' => function(){
                return validate_category('category');
            },

            'message' =>function(){
                return validate_filled('message');
            },

            'lot-rate' => function(){
                return start_price_valid('lot-rate');
            },

            'lot-step' => function(){
                return lot_step_valid('lot-step');
            },

            'lot-date' => function(){
                return date_valid('lot-date');
            }



        ];

        foreach($_POST as $key => $value){
            if(isset($rules[$key])){
                $rule = $rules[$key];
                $errors[$key]= $rule();
            }
        $errors=array_filter($errors);
        }

        if(!empty($_FILES['lot-img']['name'])){

            $file_name = $_FILES['lot-img']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            $file_tmp=$_FILES['lot-img']['tmp_name'];
            $file_type=mime_content_type($file_tmp);
            if ($file_type!=="image/png" && $file_type!=="image/jpeg") {
                $errors['lot-img']="Загрузите изображение в допустимом формате: jpg, jpeg, png" ;
            }else{
                move_uploaded_file($file_tmp, $file_path . $file_name);
                $_POST['lot-img']=$file_url;
            }
        }else{
            $errors['lot-img']="Загрузите изображение";
        }
        if(count($errors)){
            $add = include_template("add-lot.php", ["categories" => $categories, "errors"=>$errors, "_POST" => $_POST ]);
            print $add;
        }else{

            $comb=array_combine($cat_names, $cat_ids);
            $_POST['category']=$comb[$_POST['category']];
            $sql="INSERT INTO lots (user_id_author, user_id_winner, date_create, NAME, category_id, description, start_price, bet_step, date_finish, img_ref) VALUES(1,2, NOW(), ? , ?, ?, ?, ?, ?, ?)";
            $stmt=db_get_prepare_stmt($con,$sql,$_POST);
            $res=mysqli_stmt_execute($stmt);
            if($res){
                    $lot_id=mysqli_insert_id($con);
                    header("Location:lot.php?id=".$lot_id);
                }
        }

    }else{
        $add = include_template("add-lot.php", ["categories" => $categories, "_POST" => $_POST ]);
        print $add;

    }
?>

