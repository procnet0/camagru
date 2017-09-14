<?php
session_start();
?>
  <div id='hcontainer'>
      <div id='logo' class='box ' >
        <form method="post" action="post.php">
          <input type="hidden" name="page"  value="index"/>
        <input type="image"  src="config/sources/iris.gif" id='logimg' title="iris" name="iris">
      </form>
      </div>
      <div id='menu' class='autoalign box'>
        <?php
        if($_SESSION['loggued_on_user'] !== False && !empty($_SESSION['loggued_on_user']))
        { ?>
          <form method="post" action="post.php">
            <input type="hidden" name="page"  value="photo"/>
          <input type="image" src="https://media.giphy.com/media/djcKOCoiVITC0/giphy.gif" height="100%" width="150px" title="webcam" class='border'>
          </form>
        <?php } ?>
        <form method="post" action="post.php">
          <input type="hidden" name="page"  value="galery"/>
          <input type="image" src="http://i.imgur.com/X539kPF.gif" height="100%" width="150px" title="galery" class='border'>
        </form>

        <form method="post" action="post.php">
          <input type="hidden" name="page"  value="account"/>
          <input type="image" src="https://media.giphy.com/media/ODzsDTQJOtenu/giphy.gif" height="100%" width="150px" title="account" class='border'>
        </form>

      </div>
      <div id='acczone' class='box'>
        <?php
        if($_SESSION['loggued_on_user'] === False || $_SESSION['loggued_on_user'] == "")
        { ?>
        <p>Identify yourself</p>
        <form class="formhead" method="post" action="login.php">
          <p>Marines <input class="field" type="text" name="login" placeholder="username" required="true" /></p>
          <p>Number <input class="field" type="password" name="passwd" placeholder="password" required="true" /></p>
          <input type="submit" value="Check-in"><br />
        </form>
        <form class="formhead" method="post" action="post.php">
          <input type="hidden" name="page" value="reset">
          <input type="submit" value="Forgot your number?">
        </form>
        <form class="formhead" method="post" action="post.php">
          <input type="hidden" name="page"  value="sign_in"/>
          <input type="submit" value="Join the Armada">
        </form>
  <?php }
        else
        { ?>
          <p>Welcome back</p>
          <p>  <?php echo $_SESSION['loggued_on_user'] ?></p>
          <a href="log_out.php">Log_out</a>
  <?php } ?>
      </div>
      <hr id='headligne'>
  </div>
