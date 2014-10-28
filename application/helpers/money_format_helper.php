<?php
if ( ! function_exists('moneyFormat')){
	/**
	 * @param type为1表示三个数字一分隔的样式，
	 * @param type为2表示按汉字分隔，比如3.5万，4.6千万，5.9亿 
	 * 对money数字进行格式化
	 * @return {[string]} 
	 */
	function moneyFormat($money='',$type=1){
	if($type == 1){
		$format_money = '';
		$tmp_money = strrev($money);
	    for($i = 3;$i<strlen($money);$i+=3){
	        $format_money .= substr($tmp_money,0,3).",";
	         $tmp_money = substr($tmp_money,3);
	     }
	    $format_money .=$tmp_money;
	    $format_money = strrev($format_money); 
	    return $format_money;
	}else if($type == 2){
		$len = strlen($money);
		if($len <= 4){
			return $money;
		}else if($len>4 && $len<=8){
			return sprintf("%.1f",($money/10000)).'万';
		}else if($len>8){
			return sprintf("%.1f",($money/100000000)).'亿';
		}
	}	
}
}