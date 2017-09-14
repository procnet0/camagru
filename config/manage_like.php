<?php
session_start();
header("Content-Type: text/plain");

if(!isset($_POST['status']) || !isset($_POST['log']) || !isset($_POST['img_id']))
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}


if(isset($_POST['status']) && isset($_POST['log']) && isset($_POST['img_id']))
{
  $log = $_POST['log'];
  $id = $_POST['img_id'];
    include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');
  try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    print "Error!: like 0 " . $e->getMessage() . " FAILED TO SAVE<br/>";
    die();
  }


  try {
    $sql = $pdo->prepare("SELECT `like`, unlike FROM `like` WHERE image_id = ? LIMIT 1");
    $sql->bindValue(1, $id, PDO::PARAM_STR);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    $tabl = $result['like'];
    $tabu = $result['unlike'];
  } catch (PDOException $e) {
    print "Error!: like " . $e->getMessage() . " READ<br/>";
    die();
  }

  if($_POST['status'] == 'like')
  {
    $count = '';
    try {
   if(substr_count($tabl,$log) == 1)
   {
     $sql = $pdo->prepare("UPDATE `like` SET `like` = ? WHERE `like`.`image_id` = ?");
     $sql->bindValue(1, str_replace($log.';','',$tabl), PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->execute();
     $count .= '1';
   }
   if(substr_count($tabu,$log) == 1)
   {
     $sql = $pdo->prepare("UPDATE `like` SET `unlike` = ? WHERE `like`.`image_id` = ?");
     $sql->bindValue(1, str_replace($log.';','',$tabu), PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->execute();
     $count .= '2';
   }
   if(substr_count($tabl,$log) == 0)
   {
     $sql = $pdo->prepare("UPDATE `like` SET `like` = ? WHERE `like`.`image_id` = ?");
     $sql->bindValue(1,$tabl.$log.';', PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->execute();
     $count .= '3';
   }
    } catch (PDOException $e) {
      print "Error!: like " . $e->getMessage() . " update<br/>";
      die();
    }
    print $count;
  }

  else if ($_POST['status'] == 'dislike')
  {
    $count = '';
    try {
   if(substr_count($tabl,$log) == 1)
   {
     $sql = $pdo->prepare("UPDATE `like` SET `like` = ? WHERE `like`.`image_id` = ?");
     $sql->bindValue(1, str_replace($log.';','',$tabl), PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->execute();
     $count .= '2';
   }
   if(substr_count($tabu,$log) == 1)
   {
     $sql = $pdo->prepare("UPDATE `like` SET `unlike` = ? WHERE `like`.`image_id` = ?");
     $sql->bindValue(1, str_replace($log.';','',$tabu), PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->execute();
     $count .= '1';
   }
   if(substr_count($tabu,$log) == 0)
   {
     $sql = $pdo->prepare("UPDATE `like` SET `unlike` = ? WHERE `like`.`image_id` = ?");
     $sql->bindValue(1,$tabu.$log.';', PDO::PARAM_STR);
     $sql->bindValue(2, $id, PDO::PARAM_STR);
     $sql->execute();
     $count .= '3';
   }

  } catch (PDOException $e) {
   print "Error!: like " . $e->getMessage() . " update<br/>";
   die();
  }
    print $count;
  }
}

 ?>
