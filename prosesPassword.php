<?php
	include ('connectLocal.php');
	$user = $_POST["email"];
	$oldPass = $_POST["oldPassword"];
	$newPass = $_POST['newPassword'];

	$sql = $pdo->prepare("UPDATE user SET password = '$newPass' WHERE email = '$user'");
	
	if($sql->execute() == TRUE){
		header("location: index.php");
	}
	else{
		header("location: editPassword.php");
	}
?>