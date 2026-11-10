<?php
 require_once '../dbcon/dbcon.php';
    if(isset($_POST['submit'])){
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adress = $_POST['adress'];
        $zip = $_POST['zip'];
        $pays = $_POST['pays'];
        $ville = $_POST['ville'];
        $tel = $_POST['tel'];
        $iduser = $_POST['iduser'];
        //con ver
        $sql = "SELECT * from profil where iduser = $iduser";
        $result = $conn -> query($sql);
        $row = $result -> fetch();
        if($row){
            $sql = "UPDATE profil set nom = '$nom' ,prenom = '$prenom', adresse = '$adress', pays = '$pays', ville = '$ville' ,codepostal = '$zip', telephone = '$tel' where iduser = $iduser ";
            $result = $conn -> query($sql);
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert" data-redirect="">
            <strong>Profil Modifier Avec Succee</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            include('profil.php'); 
        }
        else{
            $sql ="INSERT  into profil(iduser,nom,prenom,adresse,pays,ville,codepostal,telephone) values($iduser,'$nom','$prenom','$adress','$pays','$ville','$zip','$tel')";
            $result = $conn -> query($sql);
            header('location:../index.php'); 
        }
 
    }
 ?>