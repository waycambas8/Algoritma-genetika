<?php
include'functions.php';
if(empty($_SESSION['login']))
    header("location:login.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="favicon.ico"/>

    <title>Source Code AG TSP</title>
    <link href="assets/css/cerulean-bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/select2.min.css" rel="stylesheet"/>
    <link href="assets/css/general.css" rel="stylesheet"/>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/select2.min.js"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuhiP0ANeCVyQMGYh6oBDk7Ww83n1Ydu0&callback=initMap&libraries=places&v=weekly" ></script>
    
    <script>
        $(document).ready(function() {
            $('.s2').select2();
        });
    </script>
  </head>
  <body>
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="?">TSP</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">            
            <li><a href="?m=kelompok"><span class="glyphicon glyphicon-th-large"></span> Kelompok</a></li>
            <li><a href="?m=titik"><span class="glyphicon glyphicon-th-list"></span> Titik</a></li>
            <li><a href="?m=bobot"><span class="glyphicon glyphicon-star"></span> Bobot</a></li>
            <li><a href="?m=hitung"><span class="glyphicon glyphicon-signal"></span> AG</a></li>   
            <li><a href="?m=password"><span class="glyphicon glyphicon-lock"></span> Password</a></li>
            <li><a href="?m=pengaturan"><span class="glyphicon glyphicon-cog"></span> Pengaturan</a></li>
            <li><a href="aksi.php?act=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>          
          </ul>          
        </div>
    </nav>

    <div class="container">
    <?php
        if(file_exists($mod.'.php'))
            include $mod.'.php';
        else
            include 'home.php';
    ?>
    </div>
    <footer class="footer bg-primary">
      <div class="container">
        <p>Copyright &copy; <?=date('Y')?> waycambas8</p>
      </div>
    </footer>
</html>