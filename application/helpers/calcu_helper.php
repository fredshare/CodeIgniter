<?php
if ( ! function_exists('mixPredict')){
	/**
	 * 完全拆票（适合混投），预测最小奖金、最大奖金、注数 没有4舍5入
	 * @param  {[array]} $sp    [赔率二维数组，除去胆，#1,#2等表示玩法1，玩法2]
	 *                         如：[
	 *                         		[3.3#1,2.2#1,1.5#2],//对阵1
	 *                         		[3.3#2,2.2#3,1.5#4] //对阵2
	 *                         	 	]
	 *                         表示 对阵1：玩法1，选择聊3.3，2.2两个赔率， 玩法2选择聊1.5赔率
	 *                              对阵2：玩法2选择了3.3赔率， 玩法3选择了2.2赔率，玩法4选择聊1.5赔率
	 * @param  {[string]} $passType [过关方式(m串n)， 以,分割]
	 * @param  {[array]} $danSp    [胆的最小赔率构成的数组 ,格式与sp参数一样]
	 * @param  {[boolen]} $noSingle    [true:去除单一玩法 ]
	 * @param  {[int]} $type [1:计算注数，2：计算最大奖金，3：注数、最小奖金]
	 * @param  {[array]} $dcmap [多串映射自由过关的map]
	 * @param  {[boolen]} $is2D [true: $sp维2维  false:3维]
	 * @return {[array]}          [注数，最小奖金，最大奖金]
	 */
	function mixPredict($sp, $passType, $danSp, $noSingle, $type, $dcmap, $is2D=false){
		$target= array(0,-1,0);//0：注数，1：最小金额，2：最大金额
			//hasDan = danSp && danSp.length > 0,
		$hasDan = false;
		$isZiyou= preg_match('/x1$/', $passType);
		$n=substr($passType,0, strpos($passType,"x"))*1;//记录多串过关时，N串M中的N
		$temp=array();		
		if($isZiyou){//自由过关
			$temp[] = $sp;
		}else{//多串无胆
			//多串过关,计算出的temp每个元素间没有去重
			$temp = cnx($sp, $n);
		}
		
		if(!$isZiyou && !$is2D){//多串，且sp非二维数组
			for($i=0,$iLen=count($temp);$i<$iLen;$i++){
				$temp[$i] = getDescartes($temp[$i]);
				if($noSingle){//去掉单一玩法串关
					$temp[$i] = delSinglePass($temp[$i]);
				}
				for($j=0,$jLen=count($temp[$i]);$j<$jLen;$j++){
					$_tar = mixPredict($temp[$i][$j],$passType,array(),false,$type,$dcmap,true);
					$target[0] += $_tar[0];
					if($target[1] == -1 || $target[1]>$_tar[1]){
						$target[1] = $_tar[1];
					}					
					$target[2] += $_tar[2];
				}
			}
			return $target;
		}
		$passType = isset($dcmap[$passType])?explode(',',$dcmap[$passType]):explode(",",$passType);//将过关方式拆分成多个N串1的数组
		//var_dump($temp);
		for($i=0,$iLen=count($temp);$i<$iLen;$i++){
			//按N串1计算，累加赔率
			for($j=0,$jLen=count($passType);$j<$jLen;$j++){
				$_n=substr($passType[$j],0, strpos($passType[$j],"x"))*1;//记录N串1 中的N
				//按照nx1，将b数组进行组合排列 Cmn
				
				$temp2 = cnx($temp[$i], $_n);//计算出每一注的组合情况（忽略2W一张票的限制）
				//此时temp2是一个3维数组，
				//1维：nx1排列后数据，
				//2维：对阵数据，
				//3维：单个对阵的赔率数据，每个赔率以#1,#2等标识玩法
				//将2维、3维进行排列组合，拆成单张票, 拆分后2维：票数据，3维：票中的每个赔率
				for($k=0,$kLen=count($temp2);$k<$kLen;$k++){
					$temp2[$k] = breakTicket($temp2[$k],$noSingle,$type);
				}
				//var_dump($temp2);
				//此时temp2是一个3维数组，
				//1维：nx1排列后数据，
				//2维：票数据，
				//3维：单张票的赔率数据，每个赔率以#1,#2等标识玩法
				if($type==3 || $type==1){//计算注数
					$target[0]+=getTicketCount($temp2);
				}
				if($type==3 || $type==2){//计算理论奖金
					$prize = getTicketPrize($temp2);
					$target[2] += $prize[1];//最高奖金
					if($target[1] == -1 || $target[1]>$prize[0]){
						$target[1] = $prize[0];
					}
				}
			}
		}

		if($target[1] == -1){
			$target[1] = 0;
		}
		return $target;
	}
}


if ( ! function_exists('newRound')){
	//4舍6入5进双
	function newRound($n) {
		if (preg_match('/(\d+\.\d(\d))5/', $n+"", $match_ret)) {
			if ($match_ret[2] % 2 == 1) {
				return round($n,2);
			} else {
				return $match_ret[1];
			}
		} else {
			return round($n,2);
		}
	}
}


if ( ! function_exists('getTicketCount')){
	/**
	 * 获取票的数量
	 * @param  {[array]} $d [3维数组]
	 * @return {[int]}   [票数]
	 */
	function getTicketCount($d){
		$num=0;
		for($i=0,$iLen=count($d);$i<$iLen;$i++){
			$num+=count($d[$i]);
		}
		//var_dump($num);
		return $num;
	}
}

if ( ! function_exists('getTicketPrize')){
	/**
	 * 获取票的预测奖金
	 * @param  {[array]} $d [3维数组]
	 * @return {[array]}   [最小奖金，最大奖金]
	 */
	function getTicketPrize ($d) {
		$temp = array(-1,0);
		for($i=0,$iLen=count($d);$i<$iLen;$i++){
			for($j=0,$jLen=count($d[$i]);$j<$jLen;$j++){
				$max = array_reduce($d[$i][$j], 'getTicketPrize_mul',1);
				$temp[1]+=$max;
				if($temp[0]==-1 || $temp[0]>$max){
					$temp[0] = $max;
				}
			}
		}
		return $temp;
	}

	function getTicketPrize_mul($x,$y){		
		return (preg_replace('/#\d*$/', "", ($x+""))*10000*preg_replace('/#\d*$/', "", ($y+""))*10000)/100000000;
	}
}

if ( ! function_exists('cnx')){
	/**
	 * 数组中取出$n个的排列组合
	 * @param  [type]  $arr []
	 * @param  [type]  $n   [description]
	 * @param  integer $max [最多组合条数]
	 * @return [type]       [description]
	 */
	function cnx($arr,$n,$max=0){
		if(count($arr)==0){
			return array($arr);
		}
		$r = array();
		cnxChild($r,array(),$arr,$n,$max);
		return $r;
	}
	function cnxChild(&$r,$t,$a,$n,$max=0){
		if($n===0){
			$r[]=$t;
		}
		else{
			for ($i=0,$l=count($a)-$n;$i<=$l;$i++) {
				if($max==0 || count($r)<$max){
					$x = $t;//PHP数组赋值是深拷贝
					$x[] = $a[$i];
					cnxChild($r,$x,array_slice($a,$i+1),$n-1,$max);
				}
			}
		}
	}
}


if(!function_exists('getCmn4Dan')){
	/**
	 * 获取有胆拖的 C mn 种组合，则以d、t组合出数量为n的多种组合
	 * @param  {[array]} d 胆的最大赔率构成的数组
	 * @param  {[array]} t 最大赔率构成的数组，除去胆
	 * @param  {[int]} n [数量]
	 * @param  {[int]} z [最大数量]
	 * @return {[array]}   返回可能的所有组合
	 */
	function getCmn4Dan($d, $t, $n, $z=0) {
		$r = array();
		$dLen=count($d);	
		if ($dLen <= $n) {
			$r = cnx($t, $n - $dLen, $z);
			//var_dump($r);
			for ($i = count($r); $i--;) {
				for($j=0;$j<$dLen;$j++){
					$r[$i][] = $d[$j];
				}
			}
		}
		return $r;
	}
}


if ( ! function_exists('getDescartes')){
	/**
	 * 将二维或多维数组转为笛卡尔乘积
	 * 如果是二维数组，可以看作是 n个m维向量做笛卡尔积 nm分别对应 1、2维的元素数量
	 * 如果三维或以上的数组，则第3或以上的维数对此函数是透明的
	 * @param  {[array]} $d [二维数组或更多维]
	 * @return {[array]}    [二维数组或更多维]
	 */
	function getDescartes($d){
		$a = array();
		for($i=0,$iLen=count($d);$i<$iLen;$i++){
			$a = descartesChild($a,$d[$i]);
		}
		return $a;
	}
	function descartesChild($c1,$c2){
		$c = array();
		if(count($c1)==0){
			for($j=0,$jLen=count($c2);$j<$jLen;$j++){
				$c[] = array($c2[$j]);
			}
			return $c;
		}			
		for($j=0,$jLen=count($c1);$j<$jLen;$j++){
			for($k=0,$kLen=count($c2);$k<$kLen;$k++){
				$n = $c1[$j];//PHP数组赋值是深拷贝
				$n[] = $c2[$k];
				$c[] = $n;
			}
		}
		return $c;
	}
}

if ( ! function_exists('delSinglePass')){
	/**
	 * 去除单一玩法
	 * @param  {[array]} $data [二维数组或3维数组]
	 * @return {[array]}      [与$data参数一样维数] 
	 */
	function delSinglePass($data){
		$temp = array();
		for($k=0,$kLen=count($data);$k<$kLen;$k++){
			$c = true;
			for($j=0,$jLen=count($data[$k])-1;$j<$jLen;$j++){
				$t1 = $data[$k][$j];
				$t2 = $data[$k][$j+1];
				if(is_array($t1)){//如果是数组，则data参数是三维数组
					$t1 = $t1[0];
					$t2 = $t2[0];
				}
				$t1 = explode("#",$t1);//玩法类型
				$t2 = explode("#",$t2);//玩法类型
				//var_dump($t1[1]!=$t2[1]);
				if($t1[1]!=$t2[1]){
					//有一个类型不同，则不需要去除，直接跳出
					break;
				}
				if($j==($jLen-1)){//最后一个循环
					//此时全部相同，则需要去除
					$c = false;
				}
			}
			if($c){
				$temp[] = $data[$k];
			}
		}
		return $temp;
	}
}

if ( ! function_exists('breakTicket')){
	/**
	 * 拆票
	 * @param  {[array]} $d     [二维数组，1维：对阵 2维：单个对阵的赔率，每个赔率以#1,#2等标识玩法]
	 * @param  {[boolen]} $noSingle [true:去除单一串关玩法]
	 * @param  {[int]} $type 1:计算注数，2：计算最大奖金，3：注数、最小奖金
	 * @return {[array]}          [二维数组，拆成单张票, 拆分后1维：票数据，2维：票中的每个赔率]
	 */
	function breakTicket($d,$noSingle,$type){
		if($type==2 || $type==3){//需要计算理论奖金，则将赔率升序排列
			for($i=0,$iLen=count($d);$i<$iLen;$i++){
				usort($d[$i],'breakTicket_usort');
			}
		}
		$d = getDescartes($d);//转换为笛卡尔乘积
		if($noSingle){//去除单一玩法
			$d = delSinglePass($d);
		}
		return $d;
	}

	function breakTicket_usort($v1,$v2){
		$a = explode("#",$v1);
		$b = explode("#",$v2);
		if($a[0]*1 < $b[0]*1){
			return -1;
		}else if($a[0]*1 > $b[0]*1){
			return 1;
		}else{
			return 0;
		}
	}
}

if ( ! function_exists('yh_format_betContent')){
	/**
	 * //奖金优化，将对阵信息格式化为二维数组 1维：对阵   2维：投注内容
	 * @param  {[string]} data 投注内容
	 * @return {[object]} 格式化后的数据
	 */
	function yh_format_betContent($data){
		$matchArr = explode("^",$data);
		$matchArr = explode("/",$matchArr[1]);//对阵信息
		$bet_sp = array();//二维数组，1维：对阵   2维：投注内容
		$dan_bet_sp = array();//二维数组，1维：对阵   2维：投注内容

		//拆分对阵内容
		for($i=0,$iLen=count($matchArr);$i<$iLen;$i++){
			$c =  explode("|",$matchArr[$i]);	
			$betTypeId = explode(",",$c[1]);
			$point = explode(",",$c[4]);
			$matchId = $c[0];
			$dan = $c[3];
			$bet = explode("#",$c[2]);//投注结果ID
			$oddsArr = explode("#",$c[5]);//赔率

			//单个对阵的投注内容格式化
			for($j=0,$jL=count($bet);$j<$jL;$j++){
				$bet[$j] = explode(",",$bet[$j]);
			}
			//单个对阵的投注赔率格式化
			for($j=0,$jL=count($oddsArr);$j<$jL;$j++){
				$oddsArr[$j] = explode(",",$oddsArr[$j]);
			}

			$_bet_sp=array();
			$_dan_bet_sp = array();

			//遍历每一个玩法
			for($j=0,$jLen=count($betTypeId);$j<$jLen;$j++){
				//遍历投注内容
				for($k=0,$kLen=count($bet[$j]);$k<$kLen;$k++){
					$oddsId = implode("#",array(
						$oddsArr[$j][$k],//赔率
						$betTypeId[$j],//玩法ID
						$matchId,//对阵ID
						$point[$j],//让球
						$dan//胆
					));
					if($dan*1==1){//胆
						$_dan_bet_sp[] = $oddsId;
					}else{
						$_bet_sp[] = $oddsId;	
					}
				}		
			}

			if($dan*1==1){//胆
				$dan_bet_sp[] = $_dan_bet_sp;
			}else{
				$bet_sp[] = $_bet_sp;
			}			
		}
		return array("notDan"=>$bet_sp,"dan"=>$dan_bet_sp);
	}
}


if ( ! function_exists('yh_breakTicket')){
	/**
	 * 拆票
	 * @param  {[string]} data 投注内容
	 * @return {[type]} [description]
	 */
	function yh_breakTicket($data){
		$noSinglePass = false;//是否去除单一玩法
		$a = yh_format_betContent($data);//将对阵信息格式化为二维数组
		$ticket = array();
		$passType = explode("^", $data);
		$passType = explode("$", $passType[0]);
		$passType = $passType[0];//过关方式
		if(preg_match("/#1/", $passType)){//去除单一玩法
			$noSinglePass = true;
		}
		$passType =  preg_replace("/#(\d*)/", "", $passType);
		$passType = explode("|", $passType);//过关方式
		for($j=0,$jLen=count($passType);$j<$jLen;$j++){
			$b=$passType[$j];
			$_n=substr($b, 0, strpos($b,"x"))*1;//记录N串1 中的N
			$temp2=array();
			//按照nx1，将b数组进行组合排列 Cmn
			if(count($a["dan"])>0){//有胆				
				$temp2 = getCmn4Dan($a["dan"], $a["notDan"], $_n);//计算出每一注的组合情况（忽略2W一张票的限制）
			}else{//无胆
				$temp2 = cnx($a["notDan"], $_n);//计算出每一注的组合情况（忽略2W一张票的限制）
			}
			
			
			//此时temp2是一个3维数组，
			//1维：nx1排列后数据，
			//2维：对阵数据，
			//3维：单个对阵的赔率数据，
			//将2维、3维进行排列组合，拆成单张票, 拆分后2维：票数据，3维：票中的每个赔率
			for($k=0,$kLen=count($temp2);$k<$kLen;$k++){
				$ticket = array_merge($ticket,breakTicket($temp2[$k],$noSinglePass,1));//拆票，且不进行排列
			}
		}

		//ticket为一个二维数组 , 1维：票数据   2维：票中每个赔率
		//将票按照赔率升序排列
		/*
		ticket.sort(function(v1,v2){
			var sp1 = 1,sp2 = 1;
			for(var i=0,iL=v1.length;i<iL;i++){
				sp1 *= v1.split("#")[0];
			}
			for(var i=0,iL=v2.length;i<iL;i++){
				sp2 *= v2.split("#")[0];
			}
			return sp1-sp2;
		});*/

		//格式化
		$target = array();		
		for($i=0,$iL=count($ticket);$i<$iL;$i++){
			$target[$i] = array(
				"product" => 1 ,//赔率乘积
				"prize" => 0,//奖金
				"betUnits" => 0,//注数
				"match" => array()//对阵数据
			);			
			$product = 1;
			for($j=0,$jL=count($ticket[$i]);$j<$jL;$j++){
				$a = explode("#", $ticket[$i][$j]);
				$product*=$a[0];//赔率乘积
				$target[$i]["match"][] = array(
					"odds" => $a[0],//赔率
					"betTypeId" => $a[1],//玩法ID
					"matchId" => $a[2],//对阵ID					
					"point" => $a[3],//让球
					"dan" => $a[4]//胆
				);
			}
			$target[$i]["product"] = $product;
		}
		return $target;
	}
}

if ( ! function_exists('calHt')){
	/**
	 * [混投计算注数和最大最小奖金]
	 * @param  [string] $data     [投注内容]
	 * @param  [int] $multiple [倍数]
	 * @param  [bool] $noSingle true:去除单一玩法  false:不去除
	 * @param  [array] $dcmap 多串映射成自由过关的数组
	 * @param  [bool] $isBd true:北单 false:竞彩足球或篮球
	 * @return [type]           [description]
	 */
	function calHt($data,$multiple,$noSingle,$dcmap, $isBd=false){
		list($passType,$matchArr) = explode("^",$data);
		$matchArr = explode("/", $matchArr);
		$passType = str_replace("|",",",$passType);
		$passType = preg_replace("/\\$\d*/", "",$passType);//去掉奖金优化的注数标识
		$passType = preg_replace("/#(\d*)/","",$passType);//去掉标识
		$min_sp=array();//所有赔率
		$max_sp=array();//最大赔率数组
		$isZiyou=preg_match('/x1$/', $passType);//判断是否自由过关
		
		//拆分对阵内容
		for($i=0,$iLen=count($matchArr);$i<$iLen;$i++){
			$c =  explode("|",$matchArr[$i]);	
			$betTypeId = explode(",",$c[1]);
			$point = explode(",",$c[4]);
			$matchId = $c[0];
			$dan = $c[3];
			$bet = explode("#",$c[2]);//投注结果ID
			$oddsArr = explode("#",$c[5]);//赔率

			//单个对阵的投注内容格式化
			for($j=0,$jL=count($bet);$j<$jL;$j++){
				$bet[$j] = explode(",",$bet[$j]);
			}
			//单个对阵的投注赔率格式化
			for($j=0,$jL=count($oddsArr);$j<$jL;$j++){
				$oddsArr[$j] = explode(",",$oddsArr[$j]);
			}

			$_min_sp=array();
			$_max_sp = array();

			//遍历每一个玩法
			for($j=0,$jLen=count($betTypeId);$j<$jLen;$j++){
				//遍历投注内容
				$temp = array();
				for($k=0,$kLen=count($bet[$j]);$k<$kLen;$k++){
					$oddsId = implode("#",array(
						$oddsArr[$j][$k],//赔率
						$betTypeId[$j],//玩法ID
						$matchId,//对阵ID
						$point[$j],//让球
						$dan//胆
					));
					$temp[] = $oddsId;			
				}
				//赔率升序排列
				usort($temp,'breakTicket_usort');
				$temp2 = $temp;//数组复制是深度拷贝
				if(!$isZiyou){//多串非胆
					$_min_sp[] = $temp2;
					$_max_sp[] = array($temp[count($temp)-1]);
				}else{// 非多串非胆
					$_min_sp = array_merge($_min_sp,$temp2);
					$_max_sp[] = $temp[count($temp)-1];
				}		
			}

			$min_sp[] = $_min_sp;
			$max_sp[] = $_max_sp;					
		}		
		
		$maxPrize = mixPredict($max_sp,$passType,array(),$noSingle,2,$dcmap);//最大奖金			
		$target = mixPredict($min_sp,$passType,array(),$noSingle,3,$dcmap);//计算注数和最小奖金	

		//计算最小和最大奖金
		if($isBd === true){//北单
			$maxPrize = newRound(newRound(max(2,$maxPrize[2] *2*0.65))*$multiple);
			$minPrize = newRound(newRound(max(2,$target[1]*2*0.65))*$multiple);
		}else{//足球篮球
			$maxPrize = newRound(newRound($maxPrize[2] *2)*$multiple);
			$minPrize = newRound(newRound($target[1]*2)*$multiple);
		}
		
		return array("minPrize"=>$minPrize, "maxPrize"=>$maxPrize, "betUnits"=>$target[0]);
	}
}

if ( ! function_exists('calYh')){
	/**
	 * 奖金优化
	 * @param  [string] $data [投注内容]
	 * @param  [int] $type [1:搏冷  2：搏热  3:平均]
	 * @param  [int] $betUnits [总注数]
	 * @param  [int] $totalAmount [方案总金额]
	 * @param  [bool] $isDgjs [true : 单关决胜 false:非单关决胜]
	 * @return [type]       [description]
	 */
	function calYh($data,$type,$betUnits,$totalAmount, $isDgjs){
		$t = yh_breakTicket($data);
		$ticketLen = count(t);
		if($ticketLen == $betUnits){//对阵数=总注数，就全部设为1;
			for($i=0;$i<$ticketLen;$i++){
				$t[$i]["betUnits"]=1;
				$t[$i]["prize"] = newRound($t[$i]["product"]*2);
			}
		}else if($type*1==1){//搏冷
			$t = getBoleng($t,$betUnits,1,$totalAmount);
		}else if($type*1==2){//搏热
			$t = getBoleng($t,$betUnits,2,$totalAmount);
		}else if($type*1==3){//平均
			$t =  shareSurplusBet($t, $betUnits, 3, $totalAmount);//分配剩余注数
		}


		//计算预测奖金
		$minPrize = 0;
		$maxPrize = 0;
		usort($t,"yh_orderByPrize_usort");//按奖金升序
		//var_dump($t);
		$minPrize = $t[0]["prize"];
		if($isDgjs){//单关决胜			
			$match = array();//记录每个对阵的最高奖金
			for($i=0,$iL=count($t);$i<$iL;$i++){
				//查找每一张票中，非胆对阵的ID，并记录该对阵各张票中最大的奖金
				for($j=0,$jl=count($t[$i]["match"]);$j<$jl;$j++){
					$a = $t[$i]["match"][$j];
					if($a["dan"]*1==1){//如果是胆
						continue;
					}
					if(!array_key_exists($a["matchId"],$match) || $match[$a["matchId"]]<$t[$i]["prize"]*1){
						//没有该对阵的奖金记录，或 原来记录的奖金较小则更新
						$match[$a["matchId"]] = $t[$i]["prize"]*1;
					}
				}
			}
			foreach ($match as $v){
				$maxPrize += $v*1;
			}
			$maxPrize = newRound($maxPrize);
		}else{//非单关决胜
			for($i=0,$iL=count($t);$i<$iL;$i++){
				$maxPrize += $t[$i]["prize"]*1;
			}
			$maxPrize = newRound($maxPrize);
		}

		return array("max"=>$maxPrize,"min"=>$minPrize);
	}
}

if ( ! function_exists('getBoleng')){
	/**
	 * 先做保本计算，然后将多余注数，按指定方式投入
	 * @param  {[array]} ticket [拆票后的数据]
	 * @param  {[int]} bs        [总注数]
	 * @param  {[int]} type        [1:搏冷 2：搏热]
	 * @param  {[int]} totalAmount        [方案总金额]
	 * @return {[array]}           [平均优化后的拆票数据]
	 */
	function getBoleng($ticket, $bs, $type, $totalAmount){// 多余倍数的处理方式		
		//先分1注
		for($i=0,$iL=count($ticket);$i<$iL;$i++){			
			$ticket[$i]["betUnits"]=1;
			$ticket[$i]["prize"] = newRound($ticket[$i]["product"]*2);
		}
		$bs -= count($ticket);
		return shareSurplusBet($ticket, $bs, $type, $totalAmount);//分配剩余注数
	}
}

if ( ! function_exists('shareSurplusBet')){
	/**
	 * 分配剩余注数
	 * @param  {[array]} ticket [拆票后的数据]
	 * @param  {[int]} bs     [需要分配的剩余注数]
	 * @param  {[int]} type     [1:搏冷 2：搏热  3：平均]
	 * @param  {[int]} totalAmount     [方案总金额]
	 * @return {[type]}        [平均优化后的拆票数据]
	 */
	function shareSurplusBet($ticket, $bs, $type, $totalAmount){
		//剩余注数已经分配完，则返回
		if($bs==0){			
			return $ticket;
		}
		//按每张票数据升序
		//var_dump($ticket);
		usort($ticket,"yh_orderByPrize_usort");
		//var_dump($ticket);
		//var_dump(222);
		
		if($type*1==3 || $ticket[0]["prize"]*1<$totalAmount*1){
			//平均优化，或者最低奖金的票，奖金<本金  则给最小奖金的票分一注
			$bs--;//剩余注数-1
			$ticket[0]["betUnits"] += 1;
			$ticket[0]["prize"] = newRound($ticket[0]["betUnits"]*$ticket[0]["product"]*2);//注数+1，然后 奖金=注数*赔率*2
		}else{
			usort($ticket,"yh_orderBySp_usort");//按SP乘积升序
			//最低奖金的票，奖金>=本金，将全部注数加在最冷或最热的票里
			if($type*1==1){//搏冷优化
				$index = count($ticket)-1; 
				$ticket[$index]["betUnits"] += bs;
				$ticket[$index]["prize"] = newRound($ticket[$index]["betUnits"]*$ticket[$index]["product"]*2);
				$bs = 0;
			}else if(type*1==2){//搏热优化
				$ticket[0]["betUnits"] += bs;
				$ticket[0]["prize"] = newRound($ticket[0]["betUnits"]*$ticket[0]["product"]*2);
				$bs = 0;
			}
		}
		return shareSurplusBet($ticket,$bs,$type,$totalAmount);//继续 分配剩余注数
	}

	/**
	 * 按奖金升序
	 */
	function yh_orderByPrize_usort($v1,$v2){
		if($v1["prize"]*1 < $v2["prize"]*1){
			return -1;
		}else if($v1["prize"]*1 > $v2["prize"]*1){
			return 1;
		}else{
			return 0;
		}
	}

	/**
	 * 按SP乘积升序
	 */
	function yh_orderBySp_usort($v1,$v2){
		if($v1["product"]*1 < $v2["product"]*1){
			return -1;
		}else if($v1["product"]*1 > $v2["product"]*1){
			return 1;
		}else{
			return 0;
		}
	}
}


?>