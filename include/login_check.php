<?php

if(empty($_SESSION['user']))
{
	header("Location: login.php");
	die("Redirecting to login.php");
}
