<?php
header('Access-Control-Allow-Methods: GET, POST');
include("functions.php");

if(isset($_POST['kategorija_proizvoda_id']) && isset($_POST['ime']) && isset($_POST['cena']) && isset($_POST['opis'])){


    $kategorija_proizvoda_id = $_POST['kategorija_proizvoda_id'];
    $ime = $_POST['ime'];
    $cena = $_POST['cena'];
    $opis = $_POST['opis'];

    echo addProduct($kategorija_proizvoda_id, $ime, $cena, $opis);
}
?>