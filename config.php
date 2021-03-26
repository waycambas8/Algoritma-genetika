<?php
    error_reporting(~E_NOTICE);
    session_start();
    $config["server"]='localhost';
    $config["username"]='root';
    $config["password"]='';
    $config["database_name"]='ayowarung';
    
    include'includes/db.php';
    $db = new DB($config['server'], $config['username'], $config['password'], $config['database_name']);
    include'includes/general.php';
    include'includes/paging.php';
        
    $mod = $_GET['m'];
    $act = $_GET['act'];                                             
?>