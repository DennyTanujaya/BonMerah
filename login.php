<?php
	include ('connectLocal.php');
	$user = $_POST["email"];
	$pass = $_POST["password"];

	$sql = $pdo->prepare("SELECT * FROM user WHERE email = '$user' AND password = '$pass'");
	$sql->execute();
	$data = $sql->fetch();

	if(empty($data['namaLengkap']))
	{
		header("location:index.php");
	}
	else
	{
		session_start();
		$_SESSION['name'] = $data['namaLengkap'];
		$_SESSION['akses'] = $data['akses'];
		if($data['akses'] == 2){
			header("location:sorting.php");
		}
		else if($data['akses'] == 1)
		{
			header("location:viewBon.php");
		}
	}
?>