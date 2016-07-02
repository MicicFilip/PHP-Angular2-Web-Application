<?php
header('Access-Control-Allow-Methods: POST');
include("functions.php");

if(isset($_POST['username']) && isset($_POST['password'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    echo login($username,$password);

}
?>