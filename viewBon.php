<?php
	session_start();
	include("module/function.php");
	include ("connect.php");
	if (!$dbh)
	{
		exit("Connection Failed: " . $dbh);
	}

	if(empty($_SESSION['name']) OR $_SESSION['akses'] == 2)
    {
        header("location:index.php");
    }
?>

<html>
	<head><title>Transport Fee</title></head>
	<body>
		<div style="width: 1024px; height: 728px; margin: 0px auto;">
			<div>
				<div style="float: left;">
					<a href="index.php"><img src="images/logo.png"/></a><br /><p style="text-align: justify;font-size: 12px;">PT. DESTINASI TIRTA NUSANTARA<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JAKARTA</p>
					<p>Welcome, <?php echo $_SESSION['name']; ?></p>
				</div>
				<div style="float: right;"><a href="logout.php">logout</a></div>
			</div>
				<div style="padding-top: 150px;padding-left: 100px;">
					<div><a href="sorting.php">Add Payment Bill</a></div>
					<table border="1px" width="900px">
						<thead>
							<tr>
								<th>No. Bill</th>
								<th>Name of payment bill</th>
								<th>Acct Journal No.</th>
								<th>Created By</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								include('connectLocal.php');
								$sql = $pdo->prepare("SELECT DISTINCT bonCode, bonName, journalNo, created, status FROM cetak");
							    $sql->execute();
							    while($data = $sql->fetch())
							    {
							    	echo '<tr>';
							    	echo '<td style="text-align:center">BK '.$data['bonCode'].'</td>';
							    	echo '<td style="text-align:center">'.$data['bonName'].'</td>';
							    	echo '<td style="text-align:center">'.$data['journalNo'].'</td>';
							    	echo '<td style="text-align:center">'.$data['created'].'</td>';
							    	echo '<td style="text-align:center">'.$data['status'].'</td>';
							    ?>
							    	<td style="padding-left: 50px;">
							    		<a href="prosesCetak.php?id=<?php echo $data['bonCode']; ?>">Print</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="delete.php?id=<?php echo $data['bonCode']; ?>">Delete</a>
							    	</td>
							    	</tr>
							<?php
							    }
							?>
							<tr>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div></div>
		</div>
	</body>
</html>