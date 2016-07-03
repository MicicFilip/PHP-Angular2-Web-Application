<?php
header('Access-Control-Allow-Methods: GET, POST');
include("functions.php");

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    echo orderProduct($id);
}
?>