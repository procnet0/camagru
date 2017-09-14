<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');

if(!isset($reactif) & $reactif == '1')
{
  include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}


try
{
  $pdo = new PDO("mysql:host=localhost", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $dbname = "`".str_replace("`","``",$dbname)."`";
  $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
  $pdo->query("use $dbname");
} catch (PDOException $e) {
    print "Error!: DATABASE -> " . $e->getMessage() . " FAILED TO CREATE<br/>";
    die();
}

try
{
  $table = "CREATE TABLE IF NOT EXISTS $dbname.`members`
  (
    id_user INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    login VARCHAR(64) NOT NULL,
    email VARCHAR(64) NOT NULL,
    password VARCHAR(128) NOT NULL,
    secret_answer VARCHAR(128) NOT NULL,
    activated ENUM('yes','no') DEFAULT 'no' NOT NULL,
    admin ENUM('yes','no') DEFAULT 'no' NOT NULL
   );";
  $pdo->exec($table);
} catch (PDOException $e) {
  print "Error!: TABLE users.members" . $e->getMessage() . " FAILED TO CREATE<br/>";
  die();
}

try
{
  $table = "CREATE TABLE IF NOT EXISTS $dbname.`like`
  (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `image_id` int(32) NOT NULL,
    `like` VARCHAR(5000) NOT NULL,
    `unlike` VARCHAR(5000) NOT NULL
   );";
  $pdo->exec($table);
} catch (PDOException $e) {
  print "Error!: TABLE like" . $e->getMessage() . " FAILED TO CREATE<br/>";
  die();
}


try
{
  $table = "CREATE TABLE IF NOT EXISTS $dbname.`login_attempts`
  (
    `id_log` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `login` VARCHAR(32) NOT NULL,
    `activate` VARCHAR(128) DEFAULT NULL,
    `timer` VARCHAR(32) NOT NULL
   );";
  $pdo->exec($table);
} catch (PDOException $e) {
  print "Error!: TABLE users.log" . $e->getMessage() . " FAILED TO CREATE<br/>";
  die();
}

try
{
  $table = "CREATE TABLE IF NOT EXISTS $dbname.`image`
  (
    img_id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    timer VARCHAR(32) NOT NULL,
    login VARCHAR(32) NOT NULL,
    data_url VARCHAR(1000) NOT NULL
   );";
  $pdo->exec($table);
} catch (PDOException $e) {
  print "Error!: TABLE image" . $e->getMessage() . " FAILED TO CREATE<br/>";
  die();
}

try
{
  $table = "CREATE TABLE IF NOT EXISTS $dbname.`transparent`
  (
    img_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    img_name VARCHAR(16) NOT NULL,
    data_url VARCHAR(64) NOT NULL
   );";
  $pdo->exec($table);
} catch (PDOException $e) {
  print "Error!: TABLE transparent" . $e->getMessage() . " FAILED TO CREATE<br/>";
  die();
}

try
{
  $table = "CREATE TABLE IF NOT EXISTS $dbname.`comm`
  (
    com_id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    img_id VARCHAR(32) NOT NULL,
    login VARCHAR(32) NOT NULL,
    comment VARCHAR(250) NOT NULL,
    `timer` VARCHAR(32) NOT NULL
   );";
  $pdo->exec($table);
} catch (PDOException $e) {
  print "Error!: TABLE comm" . $e->getMessage() . " FAILED TO CREATE<br/>";
  die();
}

try {
  $img = $pdo->query("SELECT * FROM transparent");
  if($img && $img->fetchColumn() == 0)
  {
  $sql = $pdo->prepare("INSERT INTO transparent VALUES
    (NULL, 'bull' , 'config/transparent/' ),
    (NULL, 'cadre', 'config/transparent/'),
    (NULL, 'cartman', 'config/transparent/'),
    (NULL, 'catmeme', 'config/transparent/'),
    (NULL, 'charly', 'config/transparent/'),
    (NULL, 'chef', 'config/transparent/'),
    (NULL, 'chinese_hat', 'config/transparent/'),
    (NULL, 'crow_front', 'config/transparent/'),
    (NULL, 'clown_noze', 'config/transparent/'),
    (NULL, 'crow_profil', 'config/transparent/'),
    (NULL, 'glass', 'config/transparent/'),
    (NULL, 'joint', 'config/transparent/'),
    (NULL, 'kakardashian', 'config/transparent/'),
    (NULL, 'kenny', 'config/transparent/'),
    (NULL, 'kungfu_panda', 'config/transparent/'),
    (NULL, 'licorn', 'config/transparent/'),
    (NULL, 'mask1', 'config/transparent/'),
    (NULL, 'mask2', 'config/transparent/'),
    (NULL, 'noel', 'config/transparent/'),
    (NULL, 'opm', 'config/transparent/'),
    (NULL, 'putin', 'config/transparent/'),
    (NULL, 'snoop', 'config/transparent/'),
    (NULL, 'sppanel', 'config/transparent/'),
    (NULL, 'sppanel2', 'config/transparent/'),
    (NULL, 'tglife', 'config/transparent/'),
    (NULL, 'trump', 'config/transparent/'),
    (NULL, 'trumphair', 'config/transparent/')
  ");
  $sql->execute();
  }
} catch (PDOException $e) {
  print "Error!: TABLE transparent" . $e->getMessage() . " FAILED TO FILL<br/>";
  die();
}

try {
  $users = $pdo->query("SELECT * FROM members");
  if($users && $users->fetchColumn() == 0)
  {
    $pdo->query("INSERT INTO members
	     VALUES (null, 'vbalart', 'vincent.balart@hotmail.fr', '2662a3c71dbf902fbec15d46139bd6d725991789c570f598743eb2d06ae02e6c79e7187487da2fd5cf69f90551110b16c46a2314960fc2386b340732bf931ad7', 'snake', 'yes','yes')");
  }
} catch (PDOException $e) {
  print "Error!:Admin account" . $e->getMessage() . " FAILED TO FILL<br/>";
  die();
}
?>
