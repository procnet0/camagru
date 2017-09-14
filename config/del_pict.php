<?php
session_start();

if(!isset($_POST['action']) || !isset($_POST['id']))
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}


include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');

 if($_POST['action'] == 'suprim' && isset($_POST['id']) && isset($_SESSION['loggued_on_user']) && $_SESSION['page'] == 'account')
 {
   $id =  str_replace("yes_button_", "" , $_POST['id']);
   try {
     $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     $sql = $pdo->prepare("DELETE FROM comm WHERE comm.img_id = ?; DELETE FROM image  WHERE image.img_id = ? AND image.login = ?; DELETE FROM `like` WHERE `like`.image_id = ?;");
     $sql->bindValue(1, $id, PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->bindValue(3, $_SESSION['loggued_on_user'], PDO::PARAM_STR);
     $sql->bindValue(4, $id, PDO::PARAM_STR);
     $sql->execute();
   } catch (PDOException $e) {
     print "Error!: del pict " . $e->getMessage() . " FAILED TO ERASE<br/>";
     die();
   }
   print 'ok=img_'.$id;
}
 ?>
