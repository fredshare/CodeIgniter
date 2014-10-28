<?php
namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.PresentWithSignResp.java
if (!class_exists('lottery\fpresent\po\PresentWithSignRespPo', false)) {

class PresentWithSignRespPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $betContent;	//<std::string> 投注内容(版本>=0)
	private $errMsg;	//<std::string> 失败内容(版本>=0)
	private $drawTime;	//<uint32_t> 开奖时间(版本>=0)
	private $issueNo;	//<std::string> 期号(版本>=0)

	function __construct(){
		$this->version = 20130625;	//<uint32_t>
		$this->betContent = "";	//<std::string>
		$this->errMsg = "";	//<std::string>
		$this->drawTime = 0;	//<uint32_t>
		$this->issueNo = "";	//<std::string>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\PresentWithSignRespPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\PresentWithSignRespPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushString($this->betContent);	//<std::string> 投注内容
		$bs->pushString($this->errMsg);	//<std::string> 失败内容
		$bs->pushUint32_t($this->drawTime);	//<uint32_t> 开奖时间
		$bs->pushString($this->issueNo);	//<std::string> 期号
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->_arr_value['betContent'] = $bs->popString();	//<std::string> 投注内容
		$this->_arr_value['errMsg'] = $bs->popString();	//<std::string> 失败内容
		$this->_arr_value['drawTime'] = $bs->popUint32_t();	//<uint32_t> 开奖时间
		$this->_arr_value['issueNo'] = $bs->popString();	//<std::string> 期号

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.PresentWithSignReq.java
if (!class_exists('lottery\fpresent\po\PresentWithSignReqPo', false)) {

class PresentWithSignReqPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $source;	//<std::string> 来源(版本>=0)
	private $uid;	//<uint64_t> 赠送人userId(版本>=0)
	private $coopDealId;	//<std::string> 合作方重入Id(版本>=0)
	private $fuin;	//<uint64_t> 被赠送人uin(版本>=0)
	private $mobile;	//<std::string> 被赠送人手机号(版本>=0)
	private $nick;	//<std::string> 被赠送人昵称(版本>=0)
	private $betUnits;	//<uint32_t> 注数(版本>=0)
	private $lotteryId;	//<std::string> 彩种Id(版本>=0)
	private $vb2ctag;	//<std::string> 下单来源标识(版本>=0)
	private $sign;	//<std::string> 签名(版本>=0)
	private $betContent;	//<std::string> 投注内容，此值为空侧机选(版本>=0)
	private $issueNo;	//<std::string> 期号(版本>=20130906)
	private $bonusId;	//<std::string> 兑奖中心奖品ID(版本>=20130916)
	private $bonusName;	//<std::string> 奖品名称(版本>=20130916)
	private $extInfo;	//<std::string> 扩展信息(版本>=20130926)

	function __construct(){
		$this->version = 20130926;	//<uint32_t>
		$this->source = "";	//<std::string>
		$this->uid = 0;	//<uint64_t>
		$this->coopDealId = "";	//<std::string>
		$this->fuin = 0;	//<uint64_t>
		$this->mobile = "";	//<std::string>
		$this->nick = "";	//<std::string>
		$this->betUnits = 0;	//<uint32_t>
		$this->lotteryId = "";	//<std::string>
		$this->vb2ctag = "";	//<std::string>
		$this->sign = "";	//<std::string>
		$this->betContent = "";	//<std::string>
		$this->issueNo = "";	//<std::string>
		$this->bonusId = "";	//<std::string>
		$this->bonusName = "";	//<std::string>
		$this->extInfo = "";	//<std::string>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\PresentWithSignReqPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\PresentWithSignReqPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushString($this->source);	//<std::string> 来源
		$bs->pushUint64_t($this->uid);	//<uint64_t> 赠送人userId
		$bs->pushString($this->coopDealId);	//<std::string> 合作方重入Id
		$bs->pushUint64_t($this->fuin);	//<uint64_t> 被赠送人uin
		$bs->pushString($this->mobile);	//<std::string> 被赠送人手机号
		$bs->pushString($this->nick);	//<std::string> 被赠送人昵称
		$bs->pushUint32_t($this->betUnits);	//<uint32_t> 注数
		$bs->pushString($this->lotteryId);	//<std::string> 彩种Id
		$bs->pushString($this->vb2ctag);	//<std::string> 下单来源标识
		$bs->pushString($this->sign);	//<std::string> 签名
		$bs->pushString($this->betContent);	//<std::string> 投注内容，此值为空侧机选
		if($this->version >= 20130906){
			$bs->pushString($this->issueNo);	//<std::string> 期号
		}
		if($this->version >= 20130916){
			$bs->pushString($this->bonusId);	//<std::string> 兑奖中心奖品ID
		}
		if($this->version >= 20130916){
			$bs->pushString($this->bonusName);	//<std::string> 奖品名称
		}
		if($this->version >= 20130926){
			$bs->pushString($this->extInfo);	//<std::string> 扩展信息
		}
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->version = $this->_arr_value['version'];
		$this->_arr_value['source'] = $bs->popString();	//<std::string> 来源
		$this->_arr_value['uid'] = $bs->popUint64_t();	//<uint64_t> 赠送人userId
		$this->_arr_value['coopDealId'] = $bs->popString();	//<std::string> 合作方重入Id
		$this->_arr_value['fuin'] = $bs->popUint64_t();	//<uint64_t> 被赠送人uin
		$this->_arr_value['mobile'] = $bs->popString();	//<std::string> 被赠送人手机号
		$this->_arr_value['nick'] = $bs->popString();	//<std::string> 被赠送人昵称
		$this->_arr_value['betUnits'] = $bs->popUint32_t();	//<uint32_t> 注数
		$this->_arr_value['lotteryId'] = $bs->popString();	//<std::string> 彩种Id
		$this->_arr_value['vb2ctag'] = $bs->popString();	//<std::string> 下单来源标识
		$this->_arr_value['sign'] = $bs->popString();	//<std::string> 签名
		$this->_arr_value['betContent'] = $bs->popString();	//<std::string> 投注内容，此值为空侧机选
		if($this->version >= 20130906){
			$this->_arr_value['issueNo'] = $bs->popString();	//<std::string> 期号
		}
		if($this->version >= 20130916){
			$this->_arr_value['bonusId'] = $bs->popString();	//<std::string> 兑奖中心奖品ID
		}
		if($this->version >= 20130916){
			$this->_arr_value['bonusName'] = $bs->popString();	//<std::string> 奖品名称
		}
		if($this->version >= 20130926){
			$this->_arr_value['extInfo'] = $bs->popString();	//<std::string> 扩展信息
		}

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.FpresentAo.java
if (!class_exists('lottery\fpresent\po\PresentLotteryPo', false)) {

class PresentLotteryPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $fuin;	//<uint64_t> 被赠送人uin(版本>=0)
	private $activityName;	//<std::string> 活动名称（中文）(版本>=0)
	private $lotName;	//<std::string> 彩种名称（中文）(版本>=0)
	private $betUnits;	//<uint32_t> 注数(版本>=0)

	function __construct(){
		$this->version = 20130926;	//<uint32_t>
		$this->fuin = 0;	//<uint64_t>
		$this->activityName = "";	//<std::string>
		$this->lotName = "";	//<std::string>
		$this->betUnits = 0;	//<uint32_t>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\PresentLotteryPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\PresentLotteryPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushUint64_t($this->fuin);	//<uint64_t> 被赠送人uin
		$bs->pushString($this->activityName);	//<std::string> 活动名称（中文）
		$bs->pushString($this->lotName);	//<std::string> 彩种名称（中文）
		$bs->pushUint32_t($this->betUnits);	//<uint32_t> 注数
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->_arr_value['fuin'] = $bs->popUint64_t();	//<uint64_t> 被赠送人uin
		$this->_arr_value['activityName'] = $bs->popString();	//<std::string> 活动名称（中文）
		$this->_arr_value['lotName'] = $bs->popString();	//<std::string> 彩种名称（中文）
		$this->_arr_value['betUnits'] = $bs->popUint32_t();	//<uint32_t> 注数

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.PresentFriendResp.java
if (!class_exists('lottery\fpresent\po\PresentFriendRespPo', false)) {

class PresentFriendRespPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $betContent;	//<std::string> 投注内容(版本>=0)
	private $errMsg;	//<std::string> 失败内容(版本>=0)
	private $drawTime;	//<uint32_t> 开奖时间(版本>=0)
	private $issueNo;	//<std::string> 期号(版本>=0)

	function __construct(){
		$this->version = 20130625;	//<uint32_t>
		$this->betContent = "";	//<std::string>
		$this->errMsg = "";	//<std::string>
		$this->drawTime = 0;	//<uint32_t>
		$this->issueNo = "";	//<std::string>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\PresentFriendRespPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\PresentFriendRespPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushString($this->betContent);	//<std::string> 投注内容
		$bs->pushString($this->errMsg);	//<std::string> 失败内容
		$bs->pushUint32_t($this->drawTime);	//<uint32_t> 开奖时间
		$bs->pushString($this->issueNo);	//<std::string> 期号
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->_arr_value['betContent'] = $bs->popString();	//<std::string> 投注内容
		$this->_arr_value['errMsg'] = $bs->popString();	//<std::string> 失败内容
		$this->_arr_value['drawTime'] = $bs->popUint32_t();	//<uint32_t> 开奖时间
		$this->_arr_value['issueNo'] = $bs->popString();	//<std::string> 期号

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.PresentFriendReq.java
if (!class_exists('lottery\fpresent\po\PresentFriendReqPo', false)) {

class PresentFriendReqPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $source;	//<std::string> 来源(版本>=0)
	private $uid;	//<uint64_t> 赠送人userId(版本>=0)
	private $coopDealId;	//<std::string> 合作方重入Id(版本>=0)
	private $fuin;	//<uint64_t> 被赠送人uin(版本>=0)
	private $mobile;	//<std::string> 被赠送人手机号(版本>=0)
	private $nick;	//<std::string> 被赠送人昵称(版本>=0)
	private $betUnits;	//<uint32_t> 注数(版本>=0)
	private $lotteryId;	//<std::string> 彩种Id(版本>=0)
	private $vb2ctag;	//<std::string> 下单来源标识(版本>=0)
	private $betContent;	//<std::string> 投注内容，此值为空侧机选(版本>=0)
	private $type;	//<uint32_t> 0企业赠送，1好友赠送(版本>=0)
	private $issueNo;	//<std::string> 期号(版本>=20130906)

	function __construct(){
		$this->version = 20130906;	//<uint32_t>
		$this->source = "";	//<std::string>
		$this->uid = 0;	//<uint64_t>
		$this->coopDealId = "";	//<std::string>
		$this->fuin = 0;	//<uint64_t>
		$this->mobile = "";	//<std::string>
		$this->nick = "";	//<std::string>
		$this->betUnits = 0;	//<uint32_t>
		$this->lotteryId = "";	//<std::string>
		$this->vb2ctag = "";	//<std::string>
		$this->betContent = "";	//<std::string>
		$this->type = 0;	//<uint32_t>
		$this->issueNo = "";	//<std::string>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\PresentFriendReqPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\PresentFriendReqPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushString($this->source);	//<std::string> 来源
		$bs->pushUint64_t($this->uid);	//<uint64_t> 赠送人userId
		$bs->pushString($this->coopDealId);	//<std::string> 合作方重入Id
		$bs->pushUint64_t($this->fuin);	//<uint64_t> 被赠送人uin
		$bs->pushString($this->mobile);	//<std::string> 被赠送人手机号
		$bs->pushString($this->nick);	//<std::string> 被赠送人昵称
		$bs->pushUint32_t($this->betUnits);	//<uint32_t> 注数
		$bs->pushString($this->lotteryId);	//<std::string> 彩种Id
		$bs->pushString($this->vb2ctag);	//<std::string> 下单来源标识
		$bs->pushString($this->betContent);	//<std::string> 投注内容，此值为空侧机选
		$bs->pushUint32_t($this->type);	//<uint32_t> 0企业赠送，1好友赠送
		if($this->version >= 20130906){
			$bs->pushString($this->issueNo);	//<std::string> 期号
		}
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->version = $this->_arr_value['version'];
		$this->_arr_value['source'] = $bs->popString();	//<std::string> 来源
		$this->_arr_value['uid'] = $bs->popUint64_t();	//<uint64_t> 赠送人userId
		$this->_arr_value['coopDealId'] = $bs->popString();	//<std::string> 合作方重入Id
		$this->_arr_value['fuin'] = $bs->popUint64_t();	//<uint64_t> 被赠送人uin
		$this->_arr_value['mobile'] = $bs->popString();	//<std::string> 被赠送人手机号
		$this->_arr_value['nick'] = $bs->popString();	//<std::string> 被赠送人昵称
		$this->_arr_value['betUnits'] = $bs->popUint32_t();	//<uint32_t> 注数
		$this->_arr_value['lotteryId'] = $bs->popString();	//<std::string> 彩种Id
		$this->_arr_value['vb2ctag'] = $bs->popString();	//<std::string> 下单来源标识
		$this->_arr_value['betContent'] = $bs->popString();	//<std::string> 投注内容，此值为空侧机选
		$this->_arr_value['type'] = $bs->popUint32_t();	//<uint32_t> 0企业赠送，1好友赠送
		if($this->version >= 20130906){
			$this->_arr_value['issueNo'] = $bs->popString();	//<std::string> 期号
		}

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.IsUserRegistResp.java
if (!class_exists('lottery\fpresent\po\IsUserRegistRespPo', false)) {

class IsUserRegistRespPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $errMsg;	//<std::string> 失败内容(版本>=0)

	function __construct(){
		$this->version = 20130625;	//<uint32_t>
		$this->errMsg = "";	//<std::string>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\IsUserRegistRespPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\IsUserRegistRespPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushString($this->errMsg);	//<std::string> 失败内容
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->_arr_value['errMsg'] = $bs->popString();	//<std::string> 失败内容

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

namespace lottery\fpresent\po;	//source idl: com.paipai.lottery.fpresent.IsUserRegistReq.java
if (!class_exists('lottery\fpresent\po\IsUserRegistReqPo', false)) {

class IsUserRegistReqPo{
	private $_arr_value=array();	//数组形式的类
	private $version;	//<uint32_t> 版本控制(版本>=0)
	private $source;	//<std::string> 来源(版本>=0)
	private $uid;	//<uint64_t> 赠送人userId(版本>=0)
	private $fuin;	//<uint64_t> 被赠送人uin(版本>=0)
	private $sign;	//<std::string> 签名(版本>=0)

	function __construct(){
		$this->version = 20130625;	//<uint32_t>
		$this->source = "";	//<std::string>
		$this->uid = 0;	//<uint64_t>
		$this->fuin = 0;	//<uint64_t>
		$this->sign = "";	//<std::string>
	}

	function __set($name,$val){
		if(isset($this->$name)){
			if(is_object($this->$name)){
				$this->initClass($name,$val,$this->$name);
			}else{
				if($name=="version" && ($val < 0 || $val > $this->version)){
					exit("Version error.It must be > 0 and < {$this->version} (default version).Now value is {$val}.");
				}
				$this->$name=$val;
			}
			if(isset($this->{$name.'_u'})){
				$this->{$name.'_u'}=1;
			}
		}else{
			exit("\lottery\fpresent\po\IsUserRegistReqPo\\{$name}：不存在此变量，请查询xxoo。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("\lottery\fpresent\po\IsUserRegistReqPo\\{$name}：请直接赋值为数组，无需new ***。");
		$base = array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){
			$class=$obj->element_type;
			$arr = array();
			if(!in_array($class, $base) || get_class($obj) == "stl_bitset2"){
				$arr=$val;
			}else if(strpos($class,'stl_')===0){
				$cls=explode("<", $class);
				$cls="\\".trim($cls[0])."2";
				$start=strpos($obj->element_type,'<')+1;
				$end= strrpos($obj->element_type,'>');
				$parm= trim(substr($obj->element_type, $start,$end-$start));
				foreach($val as $k => $v){
					$arr[$k]=new $cls($parm);
					$this->initClass($name.'\\'.$k,$v,$arr[$k]);
				}
			}else{
				foreach ($val as $key => $value) {
					$arr[$key]=new $class();
					foreach($value as $k => $v){
						if(is_object($arr[$key]->$k)){
							$this->initClass($name.'\\'.$k,$v,$arr[$key]->$k);
						}else{
							$arr[$key]->$k=$v;
						}
					}
				}
			}
			$obj->setValue($arr);
		}else{
			foreach($val as $k => $v){
				if(is_object($obj->$k)){
					$this->initClass($name.'\\'.$k,$v,$obj->$k);
				}else{
					$obj->$k=$v;
				}
			}
		}
	}
	function __get($name){
		return $this->$name;
	}

	function serialize($bs){
		$bs->pushUint32_t($this->getClassLen());
		$this->serialize_internal($bs);
	}

	function serialize_internal($bs){
		$bs->pushUint32_t($this->version);	//<uint32_t> 版本控制
		$bs->pushString($this->source);	//<std::string> 来源
		$bs->pushUint64_t($this->uid);	//<uint64_t> 赠送人userId
		$bs->pushUint64_t($this->fuin);	//<uint64_t> 被赠送人uin
		$bs->pushString($this->sign);	//<std::string> 签名
	}

	function unserialize($bs){
		$class_len = $bs->popUint32_t();
		$startPop  = $bs->getReadLength();
		$this->_arr_value['version'] = $bs->popUint32_t();	//<uint32_t> 版本控制
		$this->_arr_value['source'] = $bs->popString();	//<std::string> 来源
		$this->_arr_value['uid'] = $bs->popUint64_t();	//<uint64_t> 赠送人userId
		$this->_arr_value['fuin'] = $bs->popUint64_t();	//<uint64_t> 被赠送人uin
		$this->_arr_value['sign'] = $bs->popString();	//<std::string> 签名

		/**********************为了支持多个版本的客户端************************/
		$needPopLen = $class_len - ($bs->getReadLength() - $startPop);
		for($idx = 0;$idx < $needPopLen;$idx++){
			$bs->popUint8_t();
		}
		/**********************为了支持多个版本的客户端************************/
		
		return $this->_arr_value;
	}

	function getClassLen(){
		$len_bs = new \ByteStream2();
		$len_bs->setRealWrite(false);
		$this->serialize_internal($len_bs);
		$class_len = $len_bs->getWrittenLength();

		return $class_len;
	}
}
}

