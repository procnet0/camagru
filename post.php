<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/functions.php');

if(!empty($_POST['page']))
{
  if($_SESSION['page'] != $_POST['page'])
    $_SESSION['blinking'] = '1';
  $_SESSION['page']= $_POST['page'];
  header('Location:index.php');
}

if(!empty($_POST['sign_in'] && $_POST['sign_in'] == 'send'))
{
  if($_POST['passwl'] && $_POST['login'] && $_POST['answer'] && $_POST['mail'])
  {
    if(strlen($_POST['login']) <= 12)
    {
      if(strlen($_POST['passwl']) >= 8)
      {
        $pass = hash('whirlpool', trim($_POST['passwl']));
        $login = $_POST['login'];
        $answer = $_POST['answer'];
        $mail = $_POST['mail'];
        try
        {
      $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e)
        {
        print "Error!: creating account step 0-> " . $e->getMessage() . " <br/>";
        die();
      }
        $_SESSION['error'] = signing_in($login, $mail,$answer, $pass, $pdo);
        if($_SESSION['error'] == "Account created")
        {
        $_SESSION['page'] = "";
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
          $_SESSION['error'] = "Error!: creating account step 1-> " . $e->getMessage() . " <br/>";
          die();
        }

        $page = $_SERVER['HTTP_HOST'] . '/camagru/page/activate.php?log='. urlencode($login) . '&cle=' . urlencode($cle);

        $message_txt = 'Bienvenue sur Camagru,
        Pour activer votre compte, veuillez cliquer sur le lien ci dessous
        ou copier/coller dans votre navigateur internet.
        http://'. $page .'
        ---------------
        Ceci est un mail automatique, Merci de ne pas y répondre.';

        $message_html = '<html><head></head>
        <body>
        <b>Bienvenue sur Camagru</b><BR />
        <p>Pour activer votre compte, veuillez cliquer sur le lien ci dessous ou copier/coller dans votre navigateur internet.<BR />
        <a href="http://' . $page . '"> Activate your account</a></p><BR />
        ---------------<BR />
        Ceci est un mail automatique, Merci de ne pas y repondre.<BR />
        </body></html>';

        $sujet = "Camagru :Your Account Activation!";
        include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/mail.php');
      }
        header('Location:index.php');
      }
      else {
      $_SESSION['error']='Password to short min 8 characters';
      header('Location:index.php');
      }
    }
    else {
      $_SESSION['error']='Login to long max 12 characters';
      header('Location:index.php');
    }
  }
  else
  {
    $_SESSION['error']='Error with your form';
    header('Location:index.php');
  }
}


if(!empty($_POST['submit']) && !empty($_POST['login']) && !empty($_POST['answer']) && $_POST['submit'] == "Reset_password")
{
   $log = $_POST['login'];
   $answer = $_POST['answer'];

   try {
     $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
     print "Error!: reset pass step 0-> " . $e->getMessage() . " <br/>";
     die();
   }

   try {
   $reset = $pdo->prepare("SELECT login, email FROM members WHERE login = ? AND secret_answer = ?");
   $reset->bindValue(1, $log, PDO::PARAM_STR);
   $reset->bindValue(2, $answer, PDO::PARAM_STR);
   $reset->execute();
   $result = $reset->fetchAll();
   } catch(PDOException $e)
   {
     print "Error!: reset pass step 1-> " . $e->getMessage() . " <br/>";
     die();
   }
   if(count($result) == 1)
   {
     $cle = md5(microtime(TRUE)*5000);
     $_SESSION['Reset_key'] = $cle;
     $_SESSION['Reset_log'] = $log;
     $mail = $result[0]['email'];
     $page = $_SERVER['HTTP_HOST'] . '/camagru/index.php?log='. urlencode($log) . '&cle=' . urlencode($cle);

     $message_txt = 'Bienvenue sur Camagru,
     Pour changer votre mot de passe, veuillez cliquer sur le lien ci dessous
     ou copier/coller dans votre navigateur internet.
     http://'. $page .'
     ---------------
     Ceci est un mail automatique, Merci de ne pas y répondre.';

     $message_html = '<html><head></head>
     <body>
     <b>Bienvenue sur Camagru</b><BR />
     <p>Pour changer votre mot de passe, veuillez cliquer sur le lien ci dessous ou copier/coller dans votre navigateur internet.<BR />
     <a href="http://' . $page . '"> Reset your password</a></p><BR />
     ---------------<BR />
     Ceci est un mail automatique, Merci de ne pas y repondre.<BR />
     </body></html>';

     $sujet = "Camagru :Reset your password!";
     include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/mail.php');
     $_SESSION['error'] = 'Reset mail Sent';
   }
   $_SESSION['page'] = 'reset';
   header('Location:index.php');
}
?>
