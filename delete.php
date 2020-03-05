<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "transportfee";
	// Create connection
	$connsql = new mysqli($servername, $username, $password, $dbname);
	$bookingCode = $_GET['id'];
	$sql = "DELETE FROM cetak WHERE bonCode = '$bookingCode'";
    if($connsql -> query($sql) === TRUE)
    {
    	header("location: viewBon.php");
    }
?>