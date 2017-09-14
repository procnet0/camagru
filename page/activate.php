<?php
session_start();

if(!empty($_GET['log']) && !empty($_GET['cle']))
{
  try
  {
    $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      print "Error!: DATABASE -> " . $e->getMessage() . " FAILED TO Connect<br/>";
      die();
  }
  try {
      $sql = $pdo->prepare("SELECT timer FROM login_attempts WHERE login=? AND activate=?");
      $sql->bindValue(1, $_GET['log'], PDO::PARAM_STR);
      $sql->bindValue(2, $_GET['cle'], PDO::PARAM_STR);
      $sql->execute();
      $result= $sql->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $e) {
    print "Error!: activate account 1" . $e->getMessage() . " <br/>";
    die();
  }
  try {
    $sql = $pdo->prepare("SELECT activated , email FROM members WHERE login=?");
    $sql->bindValue(1, $_GET['log'], PDO::PARAM_STR);
    $sql->execute();
    $res = $sql->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
      print "Error!: activate account 2" . $e->getMessage() . " <br/>";
      die();
    }
  if($res['activated'] == 'no')
  {
    if(intval($result['timer']) > (time() - ( 5 * 60 * 60)))
    {
      try {
        $sql = $pdo->prepare("UPDATE `members` SET `activated` = 'yes' WHERE `members`.`login` = ?");
        $sql->bindValue(1, $_GET['log'], PDO::PARAM_STR);
        $sql->execute();
        } catch(PDOException $e) {
          print "Error!: activate account 3" . $e->getMessage() . " <br/>";
          die();
        }
        $_SESSION['error'] = 'Account is now active';
        $_SESSION['loggued_on_user'] = "";
        $_SESSION['page'] = "";
        header('Location: ../index.php');
    }
    else {
      $_SESSION['error'] = 'This activation link is no more valid, new link sent';
      $_SESSION['loggued_on_user'] = "";
      $_SESSION['page'] = "";
      $mail = $res['email'];
      $login = $_GET['log'];
      try
      {
        $cle = md5(microtime(TRUE)*100000);
        $act = $pdo->prepare("INSERT INTO login_attempts VALUES(NULL, ?, ?, ?)");
        $act->bindValue(1, $login, PDO::PARAM_STR);
        $act->bindValue(2, $cle, PDO::PARAM_STR);
        $act->bindValue(3, time(), PDO::PARAM_STR);
        $act->execute();
      } catch(PDOException $e)
      {
        print "Error!: creating account step 1-> " . $e->getMessage() . " <br/>";
        die();
      }
      include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/mail.php');
      header('Location: ../index.php');
    }
  }
  else {
    $_SESSION['error'] = 'Account already activated';
    $_SESSION['loggued_on_user'] = "";
    $_SESSION['page'] = "";
    header('Location: ../index.php');
  }
}
else {
  $_SESSION['error'] = 'Error in your URL';
  $_SESSION['loggued_on_user'] = "";
  $_SESSION['page'] = "";
  header('Location: ../index.php');
}
?>
