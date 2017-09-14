<?php

if( basename($_SERVER["REQUEST_URI"], '.php') == "functions")
{
  $_SESSION['error'] = 'No way you see this';
  header('Location: ../index.php');
  exit;
}

function checkbrute($user_id, $pdo)
{
    $now = time();

    $valid_attempts = $now - ( 60 * 60);

    if ($stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE login = ? AND timer > '$valid_attempts'"))
    {
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        if ($result > 5)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

function multiple_space($nb)
{
  $i = 0;

  while($i < $nb)
  {
    echo '&nbsp;';
    $i++;
  }
}

function signing_in($pseudo, $email, $answer, $password, $pdo)
{
  try {
    $sql = $pdo->prepare("SELECT login FROM members WHERE login=?");
    $sql->bindValue(1, $pseudo, PDO::PARAM_STR);
    $sql->execute();
  } catch(PDOException $e) {
    print "Error!: researching login" . $e->getMessage() . "<br/>";
    die();
  }
  $result=$sql->fetchColumn();
  if($result == NULL)
  {

    try {
      $sql = $pdo->prepare("SELECT email FROM members WHERE email=?");
      $sql->bindValue(1, $email, PDO::PARAM_STR);
      $sql->execute();
      } catch(PDOException $e) {
        print "Error!: researching mail" . $e->getMessage() . " <br/>";
        die();
      }
      $result=$sql->fetchColumn();
    if($result == NULL)
    {
      try {
          $sql = $pdo->prepare("INSERT INTO members
              VALUES (null, ?, ?, ?, ?, 'no','no')");
              $sql->bindValue(1, $pseudo, PDO::PARAM_STR);
              $sql->bindValue(2, $email, PDO::PARAM_STR);
              $sql->bindValue(3, $password, PDO::PARAM_STR);
              $sql->bindValue(4, $answer, PDO::PARAM_STR);
              $sql->execute();
              return "Account created";
      } catch(PDOException $e) {
        print "Error!: creating account" . $e->getMessage() . " <br/>";
        die();
      }
    }
    else
    {
      return "email already used";
    }
  }
  else
  {
    return "name already used";
  }
}
?>
