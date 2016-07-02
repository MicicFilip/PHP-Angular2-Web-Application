<?php
header('Access-Control-Allow-Methods: GET, POST');
include("functions.php");

if(isset($_POST['ime'])){

    $ime = $_POST['ime'];
    echo addCategory($ime);
}
?>