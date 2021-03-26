<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW"/>
    <title>LOGIN</title>
    <link href="assets/css/cerulean-bootstrap.min.css" rel="stylesheet"/>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>      
  </head>

  <body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
              <form class="form-signin" action="?act=login" method="post">        
                <h2 class="form-signin-heading">Silahkan masuk</h2>
                <?php            
                    if($_POST) {
                        include 'aksi.php';  
                    }
                ?>
                <div class="form-group">
                    <label for="inputEmail" class="sr-only">Usernames</label>
                    <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="user" autofocus />
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass" /> 
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Masuk</button>        
              </form>      
            </div>
        </div>
    </div>
</body>
</html>
