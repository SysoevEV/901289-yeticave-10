<?php
    session_start();
    require_once("innersql.php");
    require_once("functions.php");

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST;

        $req_fields = ['email', 'password'];

        $rules= [

            'email' =>function(){
                return validate_email('email');
            },

            'password' => function(){
                return validate_filled('password');
            }

        ];

        foreach($form as $key => $value){
            if(isset($rules[$key])){
                $rule = $rules[$key];
                $errors[$key]= $rule();
            }

        }
        $errors=array_filter($errors);

        if (empty($errors)) {
            $email = mysqli_real_escape_string($con, $form['email']);
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);
            $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

            if ($user) {
                if (password_verify($form['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /index.php");
                    exit();
                }
                else {
                $errors['password'] = 'Неверный пароль';
                }
            }
            else {
            $errors['email'] = 'Такой пользователь не найден';
            }
        }
        $login = include_template('login.php', ["errors"=>$errors]);

    }else{
        $login = include_template('login.php', []);
            if (isset($_SESSION['user'])) {
            header("Location: /index.php");
            exit();
        }
        }

    print($login);
?>