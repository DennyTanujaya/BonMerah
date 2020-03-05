<?php
	session_start();
	include("module/function.php");
	include ("connect.php");
	$name=$_SESSION['name'];
	if (!$dbh)
	{
		exit("Connection Failed: " . $dbh);
	}

	if(empty($_SESSION['name']))
    {
        header("location:login.php");
    }
    else
    {
		if($_SERVER['REQUEST_METHOD'] == "POST")
	    {
			include('connectLocal.php');

			$supplierCode = $_POST['supplierCode'];
			$TransactionPeriod = $_POST['TransactionPeriod'];

			$sql = $pdo->prepare("SELECT * FROM transaction WHERE supplier = '$supplierCode' AND transactionPeriod = '$TransactionPeriod'");
		    $sql->execute();
		    $data = $sql->fetch();

			$result = $dbh->query("SELECT TRH.TRH_ID, TRL.TRL_ID, TRH.TRANSACTION_PERIOD AS transactionPeriod, OPT.SUPPLIER AS supplier, OPT.INVOICE_TEXT1 AS supplierName, OPT.INVOICE_TEXT2 AS serviceName, TRH.TRANSACTION_DATE AS transactionDate, TRL.TRANSACTION_ITEM AS transactionItem, TRL.APPLY_REFERENCE AS reference, TRL.APPLY_REFERENCE AS applyReference, TRL.PAYMENT_DUE AS dueDate, TRL.TRANSACTION_CURRENCY AS currency, TRL.TRANSACTION_VALUE AS value
  					FROM TRH
  					JOIN TRL TRL ON TRL.TRH_ID = TRH.TRH_ID
  					JOIN BSL BSL ON BSL.BSL_ID = TRL.BSL_ID
  					JOIN OPT OPT ON OPT.OPT_ID = BSL.OPT_ID
  					WHERE TRH.CODE = '$supplierCode' AND TRH.TRANSACTION_PERIOD = '$TransactionPeriod'
					");

				while($row = $result->fetch())
				{
					$item[] = array(
						"trhID" => $row['TRH_ID'],
						"trlID" => $row['TRL_ID'],
						"transactionPeriod" => $row['transactionPeriod'],
						"supplier" => $row['supplier'],
						"supplierName" => $row['supplierName'],
						"serviceName" => $row['serviceName'],
						"transactionDate" => $row['transactionDate'],
						"transactionItem" => $row['transactionItem'],
						"reference" => $row['reference'],
						"applyReference" => $row['applyReference'],
						"dueDate" => $row['dueDate'],
						"currency" => $row['currency'],
						"value" => $row['value']
					);
				}


				foreach ($item as $bill) {
					if(!$data['transactionPeriod'] == $bill['transactionItem']){
						$sqlinsert = "INSERT INTO transaction (trhID, trlID, transactionPeriod, supplier, supplierName, serviceName, transactionDate, transactionItem, reference, applyReference, dueDate, currency, value) VALUES ('$bill[trhID]', '$bill[trlID]', '$bill[transactionPeriod]', '$bill[supplier]', '$bill[supplierName]','$bill[serviceName]', '$bill[transactionDate]', '$bill[transactionItem]','$bill[reference]','$bill[applyReference]', '$bill[dueDate]', '$bill[currency]', '$bill[value]')";
						$pdo -> query($sqlinsert);
					}
				}
			}
		}
?>

<html>
	<head><title>Transport Fee</title></head>
	<body>
		<div style="width: 1024px; height: 728px; margin: 0px auto;">
			<div>
				<div style="float: left;">
					<a href="index.php"><img src="images/logo.png"/></a>
					<p style="text-align: justify;font-size: 12px;">PT. DESTINASI TIRTA NUSANTARA<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JAKARTA</p>
					<p>Selamat Datang, <?php echo $name; ?></p>
					<a href="viewBon.php">View Bon</a>
					<a href="logout.php">logout</a>
				</div>
				<div style="float: right;width: 500px; margin-top: -180px; padding-left: 550px;">
					<div style="padding-top: -40px;">
						<h4>
							<?php
								if(!empty($supplierCode))
								{
									$sql = $pdo->prepare("SELECT * FROM transaction WHERE supplier = '$supplierCode' AND transactionPeriod = '$TransactionPeriod'");
						    		$sql->execute();
						    		$row = $sql->fetch();
									echo 'Supplier : '.$row['supplier'].' - '.$row['supplierName'];
								}else{
									echo 'data not available.';
								}
							?>
						</h4>
					</div>
						<table style="border: 1px solid #a6a6a6; padding: .5em;text-align: justify;">
							<tr>
								<td>Payment No.</td>
								<td>:</td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>Date</td>
								<td>:</td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>Acct Journal No.</td>
								<td>:</td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
				</div>
			</div>
			<div>
				<div style="padding-left: 525px;padding-top: 150px;">
					<form method="POST">
						<span>Input Supplier Code :</span>
						<input type="text" name="supplierCode"><br />
						<span>Choose Transaction Period : </span>
						<select name="TransactionPeriod">
							<option value="201701">201701</option>
							<option value="201702">201702</option>
							<option value="201703">201703</option>
							<option value="201704">201704</option>
							<option value="201705">201705</option>
							<option value="201706">201706</option>
							<option value="201707">201707</option>
							<option value="201708">201708</option>
							<option value="201709">201709</option>
							<option value="201710">201710</option>
							<option value="201711">201711</option>
							<option value="201712">201712</option>
						</select><br />
						<input type="submit" name="submit" value="submit">
					</form>
				</div>
				<div>
					<h1 style="text-align: center;"><u>Payment Bill</u></h1>
					<table>
						<tr>
							<td style="font-size: 20px;">Paid To</td>
							<td>:</td>
							<td colspan="3">...........................................................................</td>
						</tr>
						<tr>
							<td>Total Amount</td>
							<td>:</td>
							<td>
								<?php
									echo '0.00';
								?>
							</td>
							<td>(...................................................................)</td>
						</tr>
					</table>
				</div>
				<div style="margin-top: 10px;">
					<form method="POST" action="printPDF.php">
						<table border="1px">
							<thead>
								<tr>
									<th>Choose</th>
									<th style="padding-left: 10px;">Supplier</th>
									<th style="padding-left: 10px;">Supplier Name</th>
									<th style="padding-left: 10px;">Transaction Date</th>
									<th style="padding-left: 10px;">Transaction Item</th>
									<th style="padding-left: 10px;">Reference</th>
									<th style="padding-left: 10px;">Apply Reference</th>
									<th style="padding-left: 10px;">Due Date</th>
									<th style="padding-left: 10px;">Currency</th>
									<th style="padding-left: 10px;">Value</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if(!empty($supplierCode))
								{
									$sql = $pdo->prepare("SELECT * FROM transaction WHERE supplier = '$supplierCode' AND transactionPeriod = '$TransactionPeriod'");
	                				$sql->execute();
	                				$i=0;
	                				while( $row = $sql->fetch())
	            					{
										$harga=str_replace(".0000", "", $row['value']);
										if(!empty($harga))
										{
											$sqlCheck = $pdo->prepare("SELECT * FROM cetak WHERE trlID = '$row[trlID]'");
	                						$sqlCheck->execute();
	                						$data = $sqlCheck->fetch();
											if($row['trlID'] == $data['trlID'])
											{
												echo '<tr>';
												echo '<td style="text-align: center;"><input type="checkbox" name="ck'.$i.'" value="'.$row['transactionID'].'" disabled readonly></td>';
												echo '<td style="text-align: center;">'.$row['supplier'].'</td>';
												echo '<td style="text-align: center;width: 300px;">'.$row['supplierName'].'</td>';
												echo '<td style="text-align: center;">'.date('d/m/y', strtotime($row['transactionDate'])).'</td>';
												echo '<td style="text-align: center;">'.$row['transactionItem'].'</td>';
												echo '<td style="text-align: center;">'.$row['reference'].'</td>';
												echo '<td style="text-align: center;">'.$row['applyReference'].'</td>';
												echo '<td style="text-align: center;">'.date('d/m/y', strtotime($row['dueDate'])).'</td>';
												echo '<td style="text-align: center;">'.$row['currency'].'</td>';
												echo '<td style="text-align: center; height:30px;">'.convertMoney($harga).'</td>';
												echo '</tr>';
											}
											else
											{
												echo '<tr>';
												echo '<td style="text-align: center;"><input type="checkbox" name="ck'.$i.'" value="'.$row['transactionID'].'"></td>';
												echo '<td style="text-align: center;">'.$row['supplier'].'</td>';
												echo '<td style="text-align: center;width: 200px;">'.$row['supplierName'].'</td>';
												echo '<td style="text-align: center;">'.date('d/m/y', strtotime($row['transactionDate'])).'</td>';
												echo '<td style="text-align: center;">'.$row['transactionItem'].'</td>';
												echo '<td style="text-align: center;width: 120px;">'.$row['reference'].'</td>';
												echo '<td style="text-align: center;width: 130px;">'.$row['applyReference'].'</td>';
												echo '<td style="text-align: center;">'.date('d/m/y', strtotime($row['dueDate'])).'</td>';
												echo '<td style="text-align: center;">'.$row['currency'].'</td>';
												echo '<td style="text-align: center;width: 150px;">'.convertMoney($harga).'</td>';
												echo '</tr>';
											}
											$totalHarga[] = $harga;
										}
										$i++;
									}
								}else{
									echo '<tr>';
									echo '<td style="text-align: center;"><input type="checkbox" name="ck" value=""></td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center;">Data not available</td>';
									echo '<td style="text-align: center; height:30px;">Data not available</td>';
									echo '</tr>';
								}
							?>
							</tbody>
							<tfoot>
								<tr>
								<td style="padding-left: 10px;" colspan="9">Total</td>
								<td style="width:100px; text-align: center; height:30px;">
									<?php
										if(!empty($supplierCode))
										{
											$totalHargaFix = array_sum($totalHarga);
											echo convertMoney($totalHargaFix);
										}else{
											echo 'Data not available';
										}
									?>
								</td>
							</tr>
							</tfoot>
						</table>
						<div style="width: 350px;padding-left: 950px; padding-top: 15px"><input type="text" name="supplierCode" value="<?php echo $supplierCode; ?>" hidden><input type="hidden" name="jumMK" value="<?php echo $i; ?>" /><input type="text" name="transactionPeriod" value="<?php echo $TransactionPeriod; ?>" hidden><input type="submit" name="submit" value="submit"></div>
					<div>
						<span>Input Branch :</span>
						<select name="branch">
							<option value="JK">Jakarta</option>
							<option value="BL">Bali</option>
						</select>
					</div>
					<div>
						Payment Type: <input type="radio" name="payment" value="C" class="payment">Cash <input type="radio" name="payment" value="B" class="payment">Bank<div style="width: 350px; margin-top: -17px; padding-left: 800px;">Jakarta, <?php echo date('d F Y'); ?></div>
					</div>
					<div id="form1" style="display:none">
						<input type="radio" name="paymentType" value="cheque" class="paymentType">Cheque <input type="radio" name="paymentType" value="BG" class="paymentType">Bank
					</div>
					<div id="chequeForm" style="display:none">
						Cheque Number: <input type="text" name="chequeNo" placeholder="Cheque Number">
					</div>
					<div id="bankForm" style="display:none">
						Bank Number: <input type="text" name="bankNo" placeholder="Bank Number">
					</div>
					<div>
						Acct Journal No.: <input type="text" name="journalNo" placeholder="only for number of journal" required>
					</div>
					</form>
					<table style="padding-top: 30px;">
						<tr>
							<td style="width: 800px;padding-left: 100px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued By,</td>
							<td style="width: 800px;padding-left: 100px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Checked By,</td>
							<td style="width: 800px;padding-left: 100px;">&nbsp;&nbsp;&nbsp;&nbsp;Approved By,</td>
						</tr>
					</table>
					<table style="padding-top: 70px;">
						<tr>
							<td style="width: 450px;padding-left: 100px;">( ............................... )</td>
							<td style="width: 450px;padding-left: 100px;">( ............................... )</td>
							<td style="width: 450px;padding-left: 100px;">( ............................... )</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="footer"></div>
		</div>
		<!-- tambahkan jquery-->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$(":radio.payment").click(function(){
					$("#form1").hide()
					if($(this).val() == "B"){
						$("#form1").show();
					}
				});
			});
		</script>
		<script type="text/javascript">
			$(function(){
				$(":radio.paymentType").click(function(){
					$("#chequeForm").hide()
					$("#bankForm").hide()
					if($(this).val() == "cheque"){
						$("#chequeForm").show();
					}else if($(this).val() == "BG"){
						$("#bankForm").show();
					}
				});
			});
		</script>

	</body>
</html>