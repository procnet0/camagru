<?php
session_start();
header("Content-Type: text/plain");

$variable1 = (isset($_POST["img1"])) ? $_POST["img1"] : NULL;
$variable2 = (isset($_POST["img2"])) ? $_POST["img2"] : NULL;
$top = (isset($_POST["top"])) ? $_POST["top"] : NULL;
$left = (isset($_POST["left"])) ? $_POST["left"] : NULL;
$width = (isset($_POST["width"])) ? $_POST["width"] : NULL;
$height = (isset($_POST["height"])) ? $_POST["height"] : NULL;

if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/camagru/config/img/'))
{
  mkdir($_SERVER['DOCUMENT_ROOT'].'/camagru/config/img/', '0744');
}

if(!$variable1 || !$variable2 || !$top || !$left || !$width || !$height)
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}

if($variable1 && $variable2 && $top != NULL && $left != NULL && $width != NULL && $height != NULL)
{
  $tab1 = array_filter(explode(',',$variable1), 'strlen');
  $tab2 = $variable2;

  if(count($tab1) == '2' && $tab1[0] == 'data:image/png;base64' && isset($_SESSION['loggued_on_user']))
  {
    $log = $_SESSION['loggued_on_user'];
    $data = imagecreatefromstring(base64_decode($tab1[1]));
    $tab20 = imagecreatefrompng($tab2);
    $size = getimagesize($tab2);
    if(!stristr($_SERVER['HTTP_USER_AGENT'], 'Linux') && !stristr($_SERVER['HTTP_USER_AGENT'], 'Windows') && !stristr($_SERVER['HTTP_USER_AGENT'], 'Macintosh'))
    {
      imageflip($data, IMG_FLIP_VERTICAL);
      imageflip($data, IMG_FLIP_VERTICAL);
    }
    imagecopyresampled($data, $tab20,$left,$top-50,0,0,$width,$height,$size[0], $size[1]);
    $time = date('z-Y_H-i-s' , time());
    $name = $log . '_' . $time . '.png';
    $url = $_SERVER['DOCUMENT_ROOT'].'/camagru/config/img/' . $name ;
    if(imagepng($data,$url))
    {
      include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');
      try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $pdo->prepare("INSERT INTO image
            VALUES (null, ?, ?, ?)");
        $sql->bindValue(1, time(), PDO::PARAM_STR);
        $sql->bindValue(2, $log, PDO::PARAM_STR);
        $sql->bindValue(3, $name, PDO::PARAM_STR);
        $sql->execute();

        $id = $pdo->lastInsertId();

        $sql = $pdo->prepare("INSERT INTO `like` VALUES (null, ? ,'', '')");
        $sql->bindValue(1,$id, PDO::PARAM_STR);
        $sql->execute();

      } catch (PDOException $e) {
        print "Error!: IMAGE " . $e->getMessage() . " FAILED TO CREATE<br/>";
        die();
      }
      echo "OK";
    }
     else
     {
       echo "FAIL1";
     }
  }
  else
  {
    echo "FAIL2";
  }
}
else
{
	echo "FAIL3";
}

 ?>
