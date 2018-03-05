<?php
	include('config.php');
	session_start();
	
	$user_check = $_SESSION['login_user'];
	$statement = $db->prepare("SELECT SID FROM Login WHERE SID = :myusername");
	$statement->execute(array(':myusername' => $user_check));	
	$row = $statement->fetch();
	$login_session = $row['SID'];
	
	if(!isset($_SESSION['login_user'])){
		header("location:login.php");
	}
?>
