<?php
session_start();
header("Content-Type: text/plain");

function canfind($var)
{
  if(file_exists('img/'.$var['data_url']))
  {
    return (TRUE);
  }
  else
  {
    return (FALSE);
  }
}

if(!isset($_POST['offset']))
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}

if(isset($_POST['offset']) && $_POST['offset'] >= 0)
{

  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');

  try
  {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e)
    {
      print "Error!: transparent db" . $e->getMessage() . " <br/>";
      die();
    }

  try {
    $sql = $pdo->prepare("SELECT img_id,timer,login,data_url FROM image ORDER BY timer DESC LIMIT ?, 5");
    $sql->bindValue(1, intval($_POST['offset']), PDO::PARAM_INT);
    $sql->execute();
    $result = $sql->fetchAll();
    $result = array_filter($result, "canfind");
  } catch(PDOException $e) {
    print "<br/><br/>Error!: loading img" . $e->getMessage() . "<br/>";
    die();
  }

  try {
    $sql = $pdo->prepare("SELECT com_id, img_id, login, comment, timer FROM comm ORDER BY timer ASC");
    $sql->execute();
    $comres = $sql->fetchAll();
    $comres = array_filter($comres);
    } catch (PDOException $e) {
      print "<br/><br/> Error!: loading com" . $e->getMessage() . "<br/>";
      die();
    }

  foreach($result as $elem)
  {
    print '
    <div class="galerybox">

      <div>
        <img class="galimg" src="'.'config/img/'.$elem['data_url'].'" id="img_'.$elem['img_id'].'">
        <p>'. $elem['login'];
        try {
          $sql = $pdo->prepare("SELECT `like`, unlike , image_id FROM `like`  WHERE image_id = ? ");
          $sql->bindValue(1, $elem['img_id'], PDO::PARAM_STR);
          $sql->execute();
          $likeres= $sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          print "<br/><br/> Error!: loading like" . $e->getMessage() . "<br/>";
          die();
        }
        $like = count(array_filter(explode(';',$likeres['like'])));
        $unlike = count(array_filter(explode(';',$likeres['unlike'])));
    print ' <button type="button" onclick="managelike(\'like\',\''.$_SESSION['loggued_on_user'].'\',\''.$elem['img_id'].'\', event.target)"> Like</button> <span id="like_'.$elem['img_id'].'">'.$like.
    '</span> <button type="button" onclick="managelike(\'dislike\',\''.$_SESSION['loggued_on_user'].'\',\''.$elem['img_id'].'\', event.target)"> Dislike</button> <span id="dislike_'.$elem['img_id'].'">'.$unlike.
    '</span></p> <p>'. date('d-m-Y',$elem['timer']).'</p>
      </div>
        <div class="galerytxt">
          <p> Commentaire </p>
            <div id="chat_'.$elem['img_id'].'" class="chatbox">';
    foreach($comres as $com)
    {
      if(str_replace('com_','', $com['img_id']) == $elem['img_id'])
      {
        print '<div class="com"><span>'.$com['login'].'['.date('j-M-y h:i',$com['timer']).']</span> : <span>'.htmlspecialchars($com['comment']).'</span></div>';
      }
    }

    print '</div>';
    if($_SESSION['loggued_on_user'])
    {
    print '<div  class="comment_input">
            <input type="text" id="com_'.$elem['img_id'].'"placeholder="Have something to say?" maxlenght="250" value="" required>
            <button type="button"  id="but_'.$elem['img_id'].'"onclick="sender(event,\''.$_SESSION['loggued_on_user'].'\')">Send</button>
          </div>';
    }
    print '</div></div>';
  }
}
?>
