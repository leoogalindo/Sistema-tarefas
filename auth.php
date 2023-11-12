<?php

session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0 && (stristr($_SERVER['PHP_SELF'],'login.php') ||stristr($_SERVER['PHP_SELF'],'registration.php'))){
    
    header("Location:./");
    exit;
}else if((!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['user_id'] <= 0)) && (!stristr($_SERVER['PHP_SELF'],'login.php') && !stristr($_SERVER['PHP_SELF'],'registration.php'))){
    
    header("Location:./login.php");
    exit;
}