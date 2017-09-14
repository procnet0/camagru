<?php
session_start();
header("Content-Type: text/plain");

if(!isset($_POST['id']) || !isset($_POST['msg']) || !isset($_POST['log']))
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}

if(isset($_POST['id']) && isset($_POST['msg']) && isset($_POST['log']))
{
  $log = $_POST['log'];
  $msg = $_POST['msg'];
  $id = str_replace("com_","",$_POST['id']);
  $time = time();
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');
  try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = $pdo->prepare("INSERT INTO comm
        VALUES (null, ?, ?, ?, ?)");
    $sql->bindValue(1, $id, PDO::PARAM_STR);
    $sql->bindValue(2, $log, PDO::PARAM_STR);
    $sql->bindValue(3, $msg, PDO::PARAM_STR);
    $sql->bindValue(4, $time, PDO::PARAM_STR);
    $sql->execute();
  } catch (PDOException $e) {
    print "Error!: Commentaire " . $e->getMessage() . " FAILED TO SAVE<br/>";
    die();
  }

  echo($log . ' ; ' . $id . ' ; ' . date('j-M-y h:i',$time));

  $page = $_SERVER['HTTP_HOST'] . '/camagru/index.php';

  try {
    $add = $pdo->prepare("SELECT members.email  FROM members, image  WHERE ? = image.img_id AND image.login = members.login AND members.login = ?");
    $add->bindValue(1, $id, PDO::PARAM_STR);
    $add->bindValue(2, $log, PDO::PARAM_STR);
    $add->execute();
    $email = $add->fetchAll();
  } catch (PDOException $e) {
    print "Error!: Commentaire " . $e->getMessage() . " FAILED TO Email<br/>";
    die();
  }
  $mail = $email[0]['email'];
  $message_txt = 'Bienvenue sur Camagru Space marines,
  Quelqun a commenter votre photo souvenir!.
  http://'. $page .'
  ---------------
  Ceci est un mail automatique, Merci de ne pas y répondre.';

  $message_html = '<html><head></head>
  <body>
  <b>Bienvenue sur Camagru Space marines</b><BR />
  <p>Quelqun a commenter votre photo souvenir!<BR />
  <a href="http://' . $page . '"> Camagru </a></p><BR />
  ---------------<BR />
  Ceci est un mail automatique, Merci de ne pas y repondre.<BR />
  </body></html>';

  $sujet = "Camagru :New com on pict!";

  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/mail.php');
}
?>
