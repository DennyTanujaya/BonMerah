<?php
	session_start();
	error_reporting(0);
	ob_start();
	include("module/function.php");
	include('connectLocal.php');
	$supplierCode= $_POST['supplierCode'];
	$payment = $_POST['payment'];
	$paymentType = $_POST['paymentType'];
	$journalNo = $_POST['journalNo'];
	$branch = $_POST['branch'];
	$transactionPeriod = $_POST['TransactionPeriod'];
	$jumMK = $_POST['jumMK'];
	date_default_timezone_set('Asia/Jakarta');
	$today = date('Y-m-d');
	for($j = 0; $j < $jumMK; $j++){
		$service = $_POST['ck'.$j];
        if(!empty($service))
        {
			$sql = $pdo->prepare("SELECT * FROM transaction WHERE transactionID = '$service'");
            $sql->execute();
            $row = $sql->fetch();

            $sqlCheck = $pdo->prepare("SELECT * FROM cetak WHERE trlID = '$row[trlID]'");
	        $sqlCheck->execute();
	        $data = $sqlCheck->fetch();

	        if($row['trlID'] == $data['trlID'])
	        {
	        	header("location: error.php");
	        }
		    else{

			$dataTerakhir = $pdo->prepare("SELECT * FROM cetak ORDER BY bonCode DESC LIMIT 1");
			$dataTerakhir->execute();
			$dataTerakhirFix = $dataTerakhir->fetch();
			if(!empty($dataTerakhirFix['bonCode'])){
			    $terakhir = $dataTerakhirFix['bonCode'];
			    $bonCode = $terakhir + 1;
			}
			else{
				$bonCode = "80000";
			}


			$namaBon = 'BK '.$bonCode.'_'.$supplierCode;

			if($paymentType == 'cheque')
			{
				$paymentNo = 'Cheque-'.$_POST['chequeNo'];
			}
			else if($paymentType == 'BG'){
				$paymentNo = 'BG-'.$_POST['bankNo'];
			}
			
			if($row['currency'] == "IDR"){
			$currencyJournal = "I";
			}else if($row['currency'] == "USD"){
			$currencyJournal = "U";
			}else if($row['currency'] == "SDG"){
			$currencyJournal = "S";
			}else if($row['currency'] == "Australian Dollar"){
			$currencyJournal = "A";
			}else if($row['currency'] == "EMO"){
			$currencyJournal = "E";
			}

			$acctJournal = $branch.''.$payment.''.$currencyJournal.'-'.$journalNo;
			
			for($j = 0; $j < $jumMK; $j++){
				$service = $_POST['ck'.$j];
		        if(!empty($service))
		        {
					$sql = $pdo->prepare("SELECT * FROM transaction WHERE transactionID = '$service'");
		            $sql->execute();
		            $row = $sql->fetch();

					$sqlinsert = "INSERT INTO cetak (trhID, trlID, transactionDate, transactionItem, reference, supplier, supplierName, currency, value, created, createdDate, status, bonCode, bonName, bankMethod, branch, paymentType, paymentNo, journalNo) VALUES ('$row[trhID]', '$row[trlID]', '$row[transactionDate]','$row[transactionItem]','$row[reference]', '$row[supplier]', '$row[supplierName]', '$row[currency]', '$row[value]','$_SESSION[name]', '$today', 'Pending', '$bonCode', '$namaBon', '$payment', '$branch', '$paymentType', '$paymentNo', '$acctJournal')";
					$pdo -> query($sqlinsert);
				}
			}
		}
	}
}
?>

<html>
	<head><title>Transport Fee</title></head>
	<body>
		<div style="width: 595px; height: 842px; margin: 0px auto;">
			<div>
				<div style="float: left;"><img src="images/logo.png"/><br /><p style="text-align: justify;font-size: 12px;">PT. DESTINASI TIRTA NUSANTARA<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JAKARTA</p></div>
				<div style="float: right;width: 400px; margin-top: -60px; padding-left: 450px;">
					<div style="padding-top: -40px;">
						<h4>
							<?php
								$sql = $pdo->prepare("SELECT * FROM transaction WHERE supplier = '$supplierCode'");
						    	$sql->execute();
						    	$row = $sql->fetch();
								echo $supplierCode.':'.$row['supplierName'];
							?>
						</h4>
					</div>
						<table style="border: 1px solid #a6a6a6; padding: .5em;text-align: justify;">
							<tr>
								<td>Payment No.</td>
								<td>:</td>
								<td style="width: 100px"><?php echo 'BK '.$bonCode; ?></td>
							</tr>
							<tr>
								<td>Branch</td>
								<td>:</td>
								<td style="width: 100px"><?php echo $branch; ?></td>
							</tr>
							<tr>
								<td>Currency</td>
								<td>:</td>
								<td style="width: 100px">
								<?php
									$sql = $pdo->prepare("SELECT * FROM transaction WHERE supplier = '$supplierCode'");
							    	$sql->execute();
							    	$row = $sql->fetch();
									echo $row['currency'];
								?>
								</td>
							</tr>
							<?php
								if($payment == 'C')
								{
							?>
							<tr>
								<td>Payment Type</td>
								<td>:</td>
								<td style="width: 100px"><?php echo "Cash"; ?></td>
							</tr>
							<?php }else if($payment == 'B'){ ?>
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
									<td style="width: 100px"><?php echo 'Cheque-'.$_POST['chequeNo']; ?></td>
								</tr>
								<?php
									}else if($paymentType == 'BG'){
								?>
								<tr>
									<td>Bank No.</td>
									<td>:</td>
									<td style="width: 100px"><?php echo 'BG-'.$_POST['bankNo']; ?></td>
								</tr>
							<?php
									}
								}
							?>
							<tr>
								<td>Tgl.</td>
								<td>:</td>
								<td style="width: 100px"><?php echo date("d F Y"); ?></td>
							</tr>
							<tr>
								<td>Acct Journal No.</td>
								<td>:</td>
								<td style="width: 100px"><?php echo $acctJournal; ?></td>
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
								for($i = 0; $i < $jumMK; $i++){
									$service = $_POST['ck'.$i];
									if(!empty($service))
                					{
										$sql = $pdo->prepare("SELECT * FROM transaction WHERE transactionID = '$service'");
									    $sql->execute();
									    $row = $sql->fetch();
										$nameSupplier[] = $row['supplierName'];
									}
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
									for($i = 0; $i < $jumMK; $i++){
										$service = $_POST['ck'.$i];
										$sql = $pdo->prepare("SELECT * FROM transaction WHERE transactionID = '$service'");
								        $sql->execute();
								        $row = $sql->fetch();
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
							for($j = 0; $j < $jumMK; $j++){
								$service = $_POST['ck'.$j];
                				if(!empty($service))
                				{
								$sql = $pdo->prepare("SELECT * FROM transaction WHERE transactionID = '$service'");
                				$sql->execute();
                				$row = $sql->fetch();
									echo '<tr>';
									echo '<td style="padding-top: 10px;width:170px">'.date('d/m/y', strtotime($row['transactionDate'])).'</td>';
									echo '<td style="padding-top: 10px;width:170px">'.$row['transactionItem'].'</td>';
									echo '<td style="padding-top: 10px;width:170px">'.$row['reference'].'</td>';
									echo '<td style="height:15px; padding-top: 10px;width:170px">'.convertMoney($row['value']).'</td>';
									echo '</tr>';
								}
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
					<div style="width: 350px; margin-top: -17px; padding-left: 550px;padding-top: 30px;">Jakarta, <?php echo date("d F Y"); ?></div>
					<?php
						if($payment == "C"){
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
						}else if($payment == "B"){ 
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
$pdf->Output('BK'.$bonCode.'_'.$supplierCode.'.pdf', 'F');


include('PHPMailer/class.phpmailer.php');
include('PHPMailer/class.smtp.php');
$mail = new PHPMailer(true);
$mail->IsSMTP();
                    
try {
    $mail->Host       = "smtp.office365.com"; //isi dengan host email server
    $mail->SMTPDebug  = 0;     
    $mail->SMTPSecure = "tls";    
    $mail->SMTPAuth   = true;                                
    $mail->Port       = 587;   //port yang digunakan 25, 465, 587                 
    $mail->Username   = "denni.tanudjaja@panorama-destination.com"; // email pengirim
    $mail->Password   = "Pas5w0rd"; // password email pwngirim        
    $mail->AddAddress('tanujayadenny@yahoo.com','Bill Payment Application');
    $mail->AddAttachment('BK'.$bonCode.'_'.$supplierCode.'.pdf'); // attachment
    $message = "Bill Payment Information";
    $mail->SetFrom('denni.tanudjaja@panorama-destination.com','Bill Payment Application'); // email pengirim
    $mail->Subject = 'Bill Payment Information';                       
    $mail->MsgHTML('<p>'.$message);
    $mail->Send();
    header("location: sorting.php");   
} catch (phpmailerException $e) {
    echo $e->errorMessage(); 
}
?>