<?php
    include_once '../dbcon/dbcon.php';
    $iduser = $_GET['iduser'];
    //echo $_GET['iduser'];
    $sql2 = "SELECT * from profil where iduser = $iduser";
    $result2 = $conn -> query($sql2);
    $row = $result2 -> fetch();
    $idprofil = $row['idprofil'];
    $sql = "DELETE FROM panier where idprofil = $idprofil";
    $result = $conn -> query($sql);
    $sql1 = "DELETE from profil where idprofil = $idprofil";
    $result1 = $conn -> query($sql1);
    $sql3 = "DELETE from users where iduser = $iduser";
    $result3 = $conn -> query($sql3);
    header("location:admin.php");
?>
