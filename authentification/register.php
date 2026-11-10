<!-- Login 8 - Bootstrap Brain Component -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<section class="bg-light p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-xxl-11">
        <div class="card border-light-subtle shadow-sm">
          <div class="row g-0">
            <div class="col-12 col-md-6">
              <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="../images/post-item8.jpg" alt="Welcome back you've been missed!">
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
                       
                      </div>
                    </div>
                  </div>
                  <form action="register-action.php" method='post'>
                    <span><?php if(isset($message)){
                        echo $message;
                    }?></span>
                    <div class="row gy-3 overflow-hidden">
                      <div class="col-12">
                        <div class="form-floating mb-3">
                          <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                          <label for="email" class="form-label">Email</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="username" id="" placeholder="username123" required>
                          <label for="username" class="form-label">username</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                          <label for="password" class="form-label">Password</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="birthday" id="" value="" placeholder="birthday" required>
                            <label for="birthday" class="form-label">birthday</label>
                          </div>
                      </div>
                      <div class="col-12">
                        <div class="d-grid">
                          <button name = 'register' class="btn btn-dark btn-lg" type="submit">Register now</button>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="row">
                    <div class="col-12">
                      <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-5">
                        <a href="login.php" class="link-secondary text-decoration-none">You have already an account ?</a>
                        
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
  </div>
</section>