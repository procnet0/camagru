<?php
if(!isset($reactif) && $reactif != '1' || (basename($_SERVER["PHP_SELF"], '.php') != 'index'))
{
  $_SESSION['page']= basename($_SERVER["PHP_SELF"], '.php');
  header('Location: ../index.php');
  exit;
}
?>
