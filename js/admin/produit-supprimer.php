<?php
    include_once '../dbcon/dbcon.php';
    $idproduit = $_GET['idproduit'];
    //echo $_GET['iduser'];
    $sql = "DELETE FROM panier where idproduit = $idproduit";
    $result = $conn -> query($sql);
    $sql1 = "DELETE from produit where idproduit = $idproduit";
    $result2 = $conn -> query($sql1);
    header("location:admin.php");
?>