<?php
session_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/functions.php');

if(empty($_SESSION['loggued_on_user']))
{
  $log = trim($_POST['login']);
  $pass = hash('whirlpool', trim($_POST['passwd']));
  try
  {
    $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $pdo->prepare("SELECT login , activated , password FROM members WHERE login=?");
    $sql->bindValue(1, $log, PDO::PARAM_STR);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
        print "Error!: DATABASE account-> " . $e->getMessage() . " <br/>";
        die();
  }
  if(checkbrute($log, $pdo) == FALSE)
  {
    if($result['login'] && $result['activated'] == "yes" && $result['password'] === $pass)
    {
      $_SESSION['error'] = "Connexion succeed";
      $_SESSION['page'] = "";
      $_SESSION['loggued_on_user'] = $log;
      $_SESSION['blinking'] = '1';
      header('Location: index.php');
    }
    else if($result['login'] && $result['activated'] != 'yes' && $result['password'] === $pass)
    {
      $_SESSION['error'] = "Account is not activated";
      $_SESSION['page'] = "";
      $_SESSION['loggued_on_user'] = FALSE;
      $_SESSION['blinking'] = '0';
      header('Location: index.php');
    }
    else if($result['login'] && $result['password'] != $pass)
    {
      $_SESSION['error'] = "Wrong password";
      $_SESSION['page'] = "";
      $_SESSION['loggued_on_user'] = FALSE;
      $_SESSION['blinking'] = '0';
      try {
        $try = $pdo->prepare("INSERT INTO login_attempts VALUES(NULL, ?, NULL, ?)");
        $try->bindValue(1, $log, PDO::PARAM_STR);
        $try->bindValue(2, time(), PDO::PARAM_STR);
        $try->execute();
      } catch (PDOException $e) {
            print "Error!: DATABASE account  2-> " . $e->getMessage() . " <br/>";
            die();
      }
      header('Location: index.php');
    }
    else
    {
      $_SESSION['error'] = "Wrong identifier";
      $_SESSION['page'] = "";
      $_SESSION['loggued_on_user'] = FALSE;
      $_SESSION['blinking'] = '0';
      header('Location: index.php');
    }
  }
  else
  {
    $_SESSION['error'] = "Account is blocked try again later";
    $_SESSION['page'] = "";
    $_SESSION['loggued_on_user'] = FALSE;
    $_SESSION['blinking'] = '0';
    header('Location: index.php');
  }
}
else {
  $_SESSION['error'] = "You are already loggued";
  $_SESSION['page'] = "";
  header('Location: index.php');
}
?>
