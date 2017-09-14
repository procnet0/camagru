<?php
if($_SESSION['blinking']== '1')
{
  echo '<div id="blink"></div>';
  $_SESSION['blinking'] = '0';
}
?>
