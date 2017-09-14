<?php
session_start();


$reset = '0';
if(isset($_GET['log']) && $_GET['cle'] && $reset == '0')
{
  if($_GET['cle'] == $_SESSION['Reset_key'] && $_GET['log'] == $_SESSION['Reset_log'])
  {
    $reset = '1';
  }
  else {
    $reset = '0';
  }
}
else if ($_POST['submit'] == 'resetpw' && isset($_POST['password']))
{
  $pass= hash('whirlpool', trim($_POST['password']));

    try {
      $pdo = new PDO("mysql:host=localhost;dbname=camagru_db;", 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      print "Error!: reset pass step 3-> " . $e->getMessage() . " <br/>";
      die();
    }

    try {
    $reset = $pdo->prepare("UPDATE members SET password = ? WHERE login = ?");
    $reset->bindValue(2, $_SESSION['Reset_log'], PDO::PARAM_STR);
    $reset->bindValue(1, $pass, PDO::PARAM_STR);
    $reset->execute();
    } catch(PDOException $e)
    {
      print "Error!: reset pass step 4-> " . $e->getMessage() . " <br/>";
      die();
    }
  $_SESSION['Reset_key'] = '';
  $_SESSION['Reset_log'] = '';
  $_SESSION['error'] = 'SUCCESS';
  $_SESSION['page'] = '';
  header('Location:../index.php');
}
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/verifactual.php');

 ?>
<div id="resetarea">
  <div id="military">
    <span style="display: none;"></span>
  </div>

  <?php

  if($reset == '0')
  {
    print '
  <form method="POST" style="margin: 0 auto;" action="post.php">
  <p> Did you forgot your name too ? <input class="resetfield" type="text" name="login" placeholder="What is your login?" value=""></p></br>
  <p> You better remember your last Word ! <input class="resetfield" type="text" name="answer" placeholder="What is your secret answer?" value=""></p></br>
  <p> Are you really really sure?<input type="checkbox" name="verif" value="Are you sure"></p></br>
  <input type="hidden" name="submit" value="Reset_password">
  <input type="submit" value="Reset">
  </form>';
  }
  else if($reset == '1')
  {
    print '
    <form method="POST" style="margin: 0 auto;" action="page/reset.php">
    <input type="password" placeholder="Password" id="password" required>
    <input type="password" placeholder="Confirm Password" id="confirm_password" required>
    <input type="hidden" name="submit" value="resetpw">
    <button type="submit" value="reset">Confirm</button>
    </form>
    <script type="text/javascript" src="/camagru/config/java2.js"></script>';
  }

  ?>
</div>
