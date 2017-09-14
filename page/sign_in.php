<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/verifactual.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/functions.php');
if(empty($_SESSION['loggued_on_user']))
{
  $validate = 'no';
}
else
{
    $validate = 'yes';
}
?>
<div id="recruit_area">
  <p>INSCRIPTION</p>
  <?php if(!($validate == 'yes'))
  { ?>
    <div>
  <form method="post" action='post.php'>
    <p> New marines name :
      <input class="field" type="text" name="login" placeholder="username" required="true" maxlenght="12"/>
    </p><BR />
    <p>&nbsp;&nbsp;&nbsp; Operativ number :
    <input class="field" type="password" name="passwl" placeholder="password" required="true" maxlenght="32" />
  </p><BR />
  <p>  <?php multiple_space(14); ?> Last word :
    <input class="field" type="text" name="answer" placeholder="Secret answer" required="true" maxlenght="32"/>
  </p><BR />
  <p> <?php multiple_space(18); ?> Contact :
    <input class="field" type="email" name="mail" placeholder="exemple@domaine.com" required="true" />
  </p><BR />
    <input type="submit" name="sign_in" value="send"/>
  </form>
    </div>
    <?php } ?>
</div>
