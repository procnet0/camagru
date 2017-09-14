<?php
session_start();

function canfind($var)
{
  if(file_exists('config/img/'.$var['data_url']))
  {
    return (TRUE);
  }
  else
  {
    return (FALSE);
  }
}

if($_POST['submit'] == 'inneReset' && isset($_POST['newpass']) && isset($_SESSION['loggued_on_user']))
{
  $pass= hash('whirlpool', trim($_POST['newpass']));

    try {
      $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      print "Error!: reset pass step 3-> " . $e->getMessage() . " <br/>";
      die();
    }

    try {
    $reset = $pdo->prepare("UPDATE members SET password = ? WHERE login = ?");
    $reset->bindValue(2, $_SESSION['loggued_on_user'], PDO::PARAM_STR);
    $reset->bindValue(1, $pass, PDO::PARAM_STR);
    $reset->execute();
    $nb = $reset->rowCount();
    } catch(PDOException $e)
    {
      print "Error!: reset pass step 4-> " . $e->getMessage() . " <br/>";
      die();
    }
    if($nb == 1)
    {
    $_SESSION['error'] = 'SUCCESS';
    }
    else {
      $_SESSION['error'] = 'FAIL WRONG PARAMETER';
    }
  $_SESSION['page'] = '';
  header('Location: ../index.php');
}

if($_POST['submit'] == 'mailReset' &&  isset($_POST['curmail']) &&isset($_POST['newmail']) && isset($_SESSION['loggued_on_user']))
{
  $new= $_POST['newmail'];
  $cur = $_POST['curmail'];
    try {
      $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      print "Error!: reset mail step 3-> " . $e->getMessage() . " <br/>";
      die();
    }

    try {
    $resetm = $pdo->prepare("UPDATE members SET email = ? WHERE login = ? AND email = ?");
    $resetm->bindValue(2, $_SESSION['loggued_on_user'], PDO::PARAM_STR);
    $resetm->bindValue(1, $new, PDO::PARAM_STR);
    $resetm->bindValue(3, $cur, PDO::PARAM_STR);
    $resetm->execute();
    $nb = $resetm->rowCount();
    } catch(PDOException $e)
    {
      print "Error!: reset mail step 4-> " . $e->getMessage() . " <br/>";
      die();
    }
    if($nb == 1)
    {
    $_SESSION['error'] = 'SUCCESS';
    }
    else {
      $_SESSION['error'] = 'FAIL WRONG PARAMETER';
    }
  $_SESSION['page'] = '';
  header('Location: ../index.php');
}
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/verifactual.php');


try {
  $sql = $pdo->prepare("SELECT img_id,timer,login,data_url FROM image WHERE login = ? ORDER BY timer DESC");
  $sql->bindValue(1, $_SESSION['loggued_on_user'], PDO::PARAM_STR);
  $sql->execute();
  $result = $sql->fetchAll();
  $result = array_filter($result, "canfind");
} catch(PDOException $e) {
  print "<br/><br/>Error!: loading img" . $e->getMessage() . "<br/>";
  die();
}

?>
<div class="accountarea">

  <div id="military">
    <span style="display: none;"></span>
  </div>

  <div id="manageacc">
    <div id="passres">
      <p>Change your operative number and get new missions</p>
      <form method="POST"  action="page/account.php">
        <input type="password" placeholder="Password" name="newpass" id="password" required>
        <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required>
        <input type="hidden" name="submit" value="inneReset">
        <button type="submit" value="reset">Confirm</button>
      </form>
      <script type="text/javascript" src="/camagru/config/java2.js"></script>
    </div>
    <div id="mailres">
      <p> OR CHANGE YOUR MOM ADRESS BRO </p>
      <form method="POST"  action="page/account.php">
        <input type="email" placeholder="Current email" name="curmail" class="mail" required>
        <input type="email" placeholder="New email" name="newmail" class="mail" required>
        <input type="hidden" name="submit" value="mailReset">
        <button type="submit" value="reset">Confirm</button>
      </form>
    </div>
    <img src="config/sources/giphy (2).gif" id="avatar">
  </div>

  <div id="managepict">
    <?php
    foreach($result as $elem)
    {
      try {
        $sql = $pdo->prepare("SELECT COUNT(*) FROM comm WHERE  img_id = ?");
        $sql->bindValue(1, $elem['img_id'], PDO::PARAM_STR);
        $sql->execute();
        $nbresult = $sql->fetchColumn();
      } catch(PDOException $e) {
        print "<br/><br/>Error!: loading img" . $e->getMessage() . "<br/>";
        die();
      }

      print '
      <div class="galerybox">
        <div class="delbox">
          <img id="img_'.$elem['img_id'].'" src="'.'config/img/'.$elem['data_url'].'">
          <p>nombre de commentaire = '. $nbresult.'</p> <p>'. date('d-m-Y',$elem['timer']).'
          <button type="button" class="deletor" id="button_'.$elem['img_id'].'" onclick="confirm(event)">X</button></p>
        </div>
      </div>';
    } ?>
  </div>
  <script type="text/javascript" src='/camagru/config/javacount.js'></script>
</div>
