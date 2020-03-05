<?php
	function convertMoney($price)
	{
		if(mb_strlen($price) == 3)
			{
				$pisahprice = substr($price, 0,1);
				$pisahpriceDesimal = substr($price, 1);
				$pisahpriceFix = $pisahprice.','.$pisahpriceDesimal.'';
			}
			else if(mb_strlen($price) == 4)
			{
				$pisahprice = substr($price,0,1);
				$pisahpriceDesimal = substr($price,1);
				$pisahpriceFix = $pisahprice.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 5)
			{
				$pisahprice = substr($price,0,2);
				$pisahpriceDesimal = substr($price,2);
				$pisahpriceFix = $pisahprice.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 6)
			{
				$pisahprice = substr($price,0,3);
				$pisahpriceDesimal = substr($price, 3);
				$pisahpriceFix = $pisahprice.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 7)
			{
				$pisahprice = substr($price,0,1);
				$pisahpriceMid = substr($price, 1, 3);
				$pisahpriceDesimal = substr($price, 4);
				$pisahpriceFix = $pisahprice.','.$pisahpriceMid.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 8)
			{
				$pisahprice = substr($price, 0, 2);
				$pisahpriceMid = substr($price, 2, 3);
				$pisahpriceDesimal = substr($price, 5);
				$pisahpriceFix = $pisahprice.','.$pisahpriceMid.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 9)
			{
				$pisahprice = substr($price, 0, 2);
				$pisahpriceMid = substr($price, 2,4);
				$pisahpriceMid1 = substr($price, 4,6);
				$pisahpriceDesimal = substr($price, 7);
				$pisahpriceFix = $pisahprice.','.$pisahpriceMid.','.$pisahpriceMid1.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 10)
			{
				$pisahprice = substr($price, 0, 1);
				$pisahpriceMid = substr($price, 1,3);
				$pisahpriceMid1 = substr($price, 3,5);
				$pisahpriceDesimal = substr($price, 6);
				$pisahpriceFix = $pisahprice.','.$pisahpriceMid.','.$pisahpriceMid1.','.$pisahpriceDesimal.'.00';
			}
			else if(mb_strlen($price) == 11)
			{
				$pisahprice = substr($price, 0, 3);
				$pisahpriceMid = substr($price, 3,3);
				$pisahpriceMid1 = substr($price, 6,3);
				$pisahpriceDesimal = substr($price, 9);
				$pisahpriceFix = $pisahprice.','.$pisahpriceMid.','.$pisahpriceMid1.'.'.$pisahpriceDesimal.'';
			}
			else if(mb_strlen($price) == 12)
			{
				$pisahprice = substr($price, 0, 1);
				$pisahpriceMid = substr($price, 1,3);
				$pisahpriceMid1 = substr($price, 4,3);
				$pisahpriceMid2 = substr($price, 7, 3);
				$pisahpriceDesimal = substr($price, 10);
				$pisahpriceFix = $pisahprice.','.$pisahpriceMid.','.$pisahpriceMid1.','.$pisahpriceMid2.'.'.$pisahpriceDesimal.'';
			}
		return $pisahpriceFix;
	}

	function convertTime($waktu){
		$waktupisah = substr($waktu, 0, 2);
		$waktupisah1 = substr($waktu, 2, 4);
		$waktuFix = $waktupisah.':'.$waktupisah1;
		return $waktuFix; 
	}
?>