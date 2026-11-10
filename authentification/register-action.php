<?php
require_once '../dbcon/dbcon.php';
    if(isset($_POST['register'])){
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $birthday = $_POST['birthday'];
        $role = 'client';
        $sql = "SELECT * from users where username = '$username'";
        $result = $conn ->query($sql);
        $row = $result -> fetch();
        if($row){
            $message = 'Username deja utuliser';
            include_once('register.php');
        }
        elseif(strlen($username) < 8 ){
            $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" data-redirect="">
            <strong>username doit etre au moins 8 charachtere</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            include_once('register.php');
        }
        elseif(strlen($password)< 8){
            $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" data-redirect="">
            <strong>password doit etre au moins 8 charachtere</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            include_once('register.php');
        }
        else{
            $sql = "INSERT into users (email,username,password,role,birthday) values('$email','$username','$password','$role','$birthday')";
            $result = $conn ->query($sql);
            header('location:login.php');
        }
    }
?>