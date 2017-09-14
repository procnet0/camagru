<?php
session_start();
 $reactif ='1';
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/setup.php');


?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="config/css/head.css">
  <link rel="icon" type="image/png" href="favicon.png"/>
</head>
<body style="background-color: black;">
  <?php include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/context/header.php'); ?>
  <?php include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/error_log.php'); ?>
    <div id='content'>
      <?php
      include_once('page/blink.php');
      ?>
      <div id='contenu'>
        <?php

          if($_SESSION['page'] == 'sign_in')
            include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/page/'.$_SESSION['page'].'.php');
          else if($_SESSION['page'] == 'galery')
            include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/page/'.$_SESSION['page'].'.php');
          else if($_SESSION['page'] == 'photo')
            include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/page/'.$_SESSION['page'].'.php');
          else if(!empty($_SESSION['loggued_on_user']) && $_SESSION['page'] == 'account')
            include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/page/'.$_SESSION['page'].'.php');
          else if(empty($_SESSION['loggued_on_user']) && $_SESSION['page'] == 'reset')
           include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/page/'.$_SESSION['page'].'.php');
          else
          {
            print '<img id="acceuil" src="./config/sources/giphy55.gif"></img>';
          }
        ?>
      </div>
    </div>
  <?php include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/context/footer.php'); ?>
</body>
</html>
