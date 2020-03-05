<?php
	error_reporting(0);
	ob_start();
	include("module/function.php");
	include('connectLocal.php');
	
	$id = $_GET['id'];
	$sql = $pdo->prepare("SELECT * FROM cetak WHERE bonCode = '$id'");
    $sql->execute();
    $row = $sql->fetch();
?>

<html>
	<head>
		<title>Transport Fee</title>
		<meta http-equiv="refresh" content="3;url=viewBon.php">
	</head>
	<body>
		<div style="width: 595px; height: 1000px; background: url(images/duplicate1.png); background-repeat: no-repeat; background-size: cover; background-position: left;">
			<div><!-- Header -->
				<div style="float: left;"><img src="images/logo.png"/><br /><p style="text-align: justify;font-size: 12px;">PT. DESTINASI TIRTA NUSANTARA<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JAKARTA</p></div>
				<div style="float: right;width: 400px; margin-top: -60px; padding-left: 450px;">
					<div style="padding-top: -40px;">
						<h4>
							<?php
								echo $row['supplier'].':'.$row['supplierName'];
							?>
						</h4>
					</div>
						<table style="border: 1px solid #a6a6a6; padding: .5em;text-align: justify;">
							<tr>
								<td>Payment No.</td>
								<td>:</td>
								<td style="width: 100px">
									<?php
										echo 'BK '.$row['bonCode'];
									?>
								</td>
							</tr>
							<tr>
								<td>Branch</td>
								<td>:</td>
								<td style="width: 100px">
									<?php
										echo $row['branch']; ?>
								</td>
							</tr>
							<tr>
								<td>Currency</td>
								<td>:</td>
								<td style="width: 100px">
								<?php
									echo $row['currency'];
								?>
								</td>
							</tr>
							<?php
								if($row['bankMethod'] == 'C')
								{
							?>
							<tr>
								<td>Payment Type</td>
								<td>:</td>
								<td style="width: 100px"><?php echo "Cash"; ?></td>
							</tr>
							<?php 
								}
								if($row['bankMethod'] == 'B')
								{
							?>
							<tr>
								<td>Payment Type</td>
								<td>:</td>
								<td style="width: 100px"><?php echo "Bank"; ?></td>
							</tr>
							<?php } 
								if($payment == "B")
								{
									if($paymentType == 'cheque')
									{
								?>
								<tr>
									<td>Cheque No.</td>
									<td>:</td>
									<td style="width: 100px">
										<?php
										echo $row['paymentNo']; ?>
									</td>
								</tr>
								<?php
									}else if($paymentType == 'BG'){
								?>
								<tr>
									<td>Bank No.</td>
									<td>:</td>
									<td style="width: 100px">
										<?php
										echo $row['paymentNo']; ?>
									</td>
								</tr>
							<?php
									}
								}
							?>
							<tr>
								<td>Tgl.</td>
								<td>:</td>
								<td style="width: 100px">
										<?php
										echo date('d F Y', strtotime($row['createdDate'])); ?>
									</td>
							</tr>
							<tr>
								<td>Acct Journal No.</td>
								<td>:</td>
								<td style="width: 100px">
										<?php
										echo $row['journalNo']; ?>
									</td>
							</tr>
						</table>
				</div>
			</div>
			<div>
				<div>
					<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Payment Bill</u></h1>
					<table>
						<tr>
							<td style="font-size: 20px;">Paid To</td>
							<td style="font-size: 20px;">:</td>
							<td colspan="3" style="font-size: 20px;">
								<?php
								for($i = 0; $i < count($row['bonCode']); $i++){
										
										$nameSupplier[] = $row['supplierName'];
									}
								echo $nameSupplier[0];
								?>
							</td>
						</tr>
						<tr>
							<td>Total Amount</td>
							<td>:</td>
							<td>
								<?php
								    for($i=0; $i<count($row['bonCode']); $i++){
								    	$totalHarga[] = $row['value'];
								    }
									$totalHargaFix = array_sum($totalHarga);	
									echo convertMoney($totalHargaFix);
								?>
							</td>
						</tr>
						<tr>
							<td>Remark</td>
							<td>:</td>
							<td>...................................................................</td>
						</tr>
					</table>
				</div>
				<div style="margin-top: 10px;">
					<table border="1px">
						<thead>
							<tr>
								<th>Transaction Date</th>
								<th>Transaction Item</th>
								<th>Reference</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody>
						<?php
							for($j = 0; $j < count($row['bonCode']); $j++){
									echo '<tr>';
									echo '<td style="padding-top: 10px;width:170px">'.date('d/m/y', strtotime($row['transactionDate'])).'</td>';
									echo '<td style="padding-top: 10px;width:170px">'.$row['transactionItem'].'</td>';
									echo '<td style="padding-top: 10px;width:170px">'.$row['reference'].'</td>';
									echo '<td style="height:15px; padding-top: 10px;width:170px">'.convertMoney($row['value']).'</td>';
									echo '</tr>';
								}
						?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3" style="padding-top: 10px;"><b>Total Amount</b></td>
								<td style="width:115px;height:15px; padding-top: 10px;">
									<?php
										echo '<b>'.convertMoney($totalHargaFix).'</b>';
									?>
								</td>
							</tr>
						</tfoot>
					</table>
					<div style="width: 350px; margin-top: -17px; padding-left: 550px;padding-top: 30px;">Jakarta, <?php echo date("d F Y", strtotime($row['createdDate'])); ?></div>
					<?php
						if($row['bankMethod'] == "C"){
					?>
					<table style="padding-top: 35px;">
						<tr>
							<td style="width: 180px;">&nbsp;&nbsp;&nbsp;&nbsp;Issued By,</td>
							<td style="width: 180px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Checked By,</td>
							<td style="width: 180px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Approved By,</td>
							<td style="width: 190px;">&nbsp;&nbsp;Received By,</td>
						</tr>
					</table>
					<table style="padding-top: 90px;">
						<tr>
							<td style="width: 180px;">( ............................... )</td>
							<td style="width: 180px;">( ............................... )</td>
							<td style="width: 180px;">( ............................... )</td>
							<td style="width: 180px;">( ............................... )</td>
						</tr>
					</table>
					<?php
						}else if($row['bankMethod'] == "B"){ 
					?>
					<table style="padding-top: 35px;">
						<tr>
							<td style="width: 270px;">&nbsp;&nbsp;&nbsp;&nbsp;Issued By,</td>
							<td style="width: 270px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Checked By,</td>
							<td style="width: 270px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Approved By,</td>
						</tr>
					</table>
					<table style="padding-top: 90px;">
						<tr>
							<td style="width: 270px;">( ............................... )</td>
							<td style="width: 270px;">( ............................... )</td>
							<td style="width: 270px;">( ............................... )</td>
						</tr>
					</table>
					<?php } ?>
				</div>
			</div>
			<div id="footer"></div>
		</div>
	</body>
</html>
<?php
$html = ob_get_contents();
ob_end_clean();

require_once('html2pdf/html2pdf.class.php');
$pdf = new HTML2PDF('P','A4');
$pdf->WriteHTML($html);
$pdf->Output('BK'.$row['bonCode'].'_'.$row['supplier'].'.pdf', 'D');
?>