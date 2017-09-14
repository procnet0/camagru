<?php
	session_start();
	if ($_SESSION['loggued_on_user'] !== false)
	{
		$_SESSION['page'] = "";
		$_SESSION['loggued_on_user'] = "";
		$_SESSION['error'] = 'You have been disconnected';
	}
	header('location: index.php');
?>
