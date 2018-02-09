<?php

if (!isset($auth))
{
	if (!isset($_SESSION['Auth']['id'])){
		header('Location: login.php');
		die();
	}
}
?>
