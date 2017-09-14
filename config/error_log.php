<?php
session_start();

if(!isset($reactif))
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}

if(!empty($_SESSION['error']))
{
  print '<div id="error_node">'.$_SESSION['error'].'</div>';
  $_SESSION['error'] = NULL;
}
 ?>
