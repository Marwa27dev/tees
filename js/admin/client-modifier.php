<?php
    include_once '../dbcon/dbcon.php';
    $iduser = $_GET['iduser'];
    $sql = "SELECT * from users where iduser= $iduser";
    $result = $conn -> query($sql);
    $row = $result -> fetch();
    if(isset($_POST['modifier'])){
        $email = $_POST['email'];
        $username = $_POST['username'];
        $anniv = $_POST['anniv'];
        $sql = "UPDATE users set email = '$email' , username = '$username', birthday = '$anniv' where iduser = $iduser";
        $result = $conn -> query($sql);
        header("location:admin.php");
    }
?>
<!DOCTYPE html>
<!--=== Coding by CodingLab | www.codinglabweb.com === -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="style.css">
     
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Admin Dashboard Panel</title> 
</head>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Form</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    .form-floating-sm .form-control {
      height: 40px;
      padding: 5px 10px;
    }
    .form-floating-sm label {
      font-size: 0.9rem;
    }
    .btn-sm {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
    }
    .gy-2 > .col-12 {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h1 style='text-align:center'>Modification du client</h1>
    <form action="" method="post">
      <span><?php if (isset($message)) { echo $message; } ?></span>
      <div class="row gy-2 overflow-hidden">
        <div class="col-12">
          <div class="form-floating form-floating-sm mb-3">
            <input type="email" class="form-control" name="email" id="" value='<?php echo $row['email']; ?>' required>
            <label for="nom" class="form-label">email</label>
          </div>
        </div>
        <div class="col-12">
          <div class="form-floating form-floating-sm mb-3">
            <input type="text" class="form-control" name="username" id="" value='<?php echo $row['username']; ?>'  required>
            <label for="quantite" class="form-label">username</label>
          </div>
        </div>
        <div class="col-12">
          <div class="form-floating form-floating-sm mb-3">
          <input type="text" class="form-control" name="old" id="" value='<?php echo $row['birthday']; ?>'  required>
          <input type="date" name='anniv' class="form-control">
          <label for="description" class="form-label">Birthday</label>
          </div>
        </div>
        <div class="col-12">
          <div class="d-grid">
            <button name="modifier" class="btn btn-dark btn-lg btn-sm" type="submit">Modifier</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
