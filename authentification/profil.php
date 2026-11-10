<!-- Login 8 - Bootstrap Brain Component -->
<?php

 require_once '../dbcon/dbcon.php';
    session_start();
    require_once '../verification-authentification.php';
    $iduser = $_SESSION['iduser'];
    $username =  $_SESSION['username'];
    $sql = "SELECT * from profil where iduser = $iduser";
    $result = $conn -> query($sql);
    $row = $result -> fetch();
    if($row){
      $button = 'Modifier';
    }
    else{
      $button = 'Enregistrer';
    }
 ?>
<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<section class="bg-light p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-xxl-11">
        <div class="card border-light-subtle shadow-sm">
          <div class="row g-0">
            <div class="col-12 col-md-6">
              <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="../images/post-item2.jpg" alt="Welcome back you've been missed!">
            </div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
              <div class="col-12 col-lg-11 col-xl-10">
                <div class="card-body p-3 p-md-4 p-xl-5">
                  <div class="row">
                    <div class="col-12">
                      <div class="mb-5">
                        <div class="text-center mb-4">
                          <a href="../index.php">
                            <img src="../images/dark-logo.png" alt="BootstrapBrain Logo" width="175" height="57">
                          </a>
                        </div>
                        <h4 class="text-center">Welcome <?php echo $username ?> Setup Your Profil</h4>
                      </div>
                    </div>
                  </div>
                  <form action="profil-action.php" method ='post'>
                    <?php if(isset($message)){
                          echo $message;
                    } ?>
                    <div class="row gy-3 overflow-hidden">
                      <div class="col-12">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="nom" id="" placeholder="nom" value='<?php if($row){ echo $row['nom'];}?>' required>
                          <label for="nom" class="form-label">nom</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="prenom" id="" placeholder="prenom" value='<?php if($row){ echo $row['prenom'];}?>' required>
                          <label for="prenom" class="form-label">prenom</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="adress" id="password"  placeholder="adress" value='<?php if($row){ echo $row['adresse']; }?>' required>
                          <label for="adress" class="form-label">adress</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="zip" id=""  placeholder="zip" value='<?php if($row){ echo $row['codepostal'];}?>' required>
                            <label for="zip" class="form-label">zip</label>
                          </div>
                      </div>
                      <input type="hidden" name='iduser' value='<?php  echo $iduser;?>' >
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            <select name="pays" id="" class="form-select">
                                <option value="Maroc" selected>Maroc</option>
                            </select>
                            <label for="pays" class="form-label">pays</label>
                          </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            <select name="ville" id="" class="form-select">
                                <option value="Casablanca">Casablanca</option>
                                <option value="Rabat">Rabat</option>
                                <option value="Marrakech">Marrakech</option>
                                <option value="Agadir">Agadir</option>
                                <option value="Fes">Fes</option>
                                <option value="Tanger">Tanger</option>
                                <option value="Settat">Settat</option>
                                <option value="Nador">Nador</option>
                            </select>
                            <label for="ville" class="form-label">ville</label>
                          </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control" name="tel" id=""  placeholder="telephone" value='<?php if($row){ echo $row['telephone'];}?>' required>
                            <label for="tel" class="form-label">telephone</label>
                          </div>
                      </div>
                      <div class="col-12">
                        <div class="d-grid">
                          <button name= 'submit' class="btn btn-dark btn-lg" type="submit"><?php echo $button ?></button>
                          
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="row">
                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>