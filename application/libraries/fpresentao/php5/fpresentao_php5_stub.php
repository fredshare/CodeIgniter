<?php
// source idl: com.paipai.lottery.fpresent.FpresentAo.java
namespace lottery;
require_once "fpresentao_php5_xxoo.php";

namespace lottery\fpresent\ao;
if (!class_exists("lottery\fpresent\ao\IsUserRegistReq", false)) {
class IsUserRegistReq{
	private $_routeKey;
	private $_arr_value=array();	//数组形式的类
	private $req;	//<lottery::fpresent::po::CIsUserRegistReqPo> 查询QQ用户是否存在请求(版本>=0)

	function __construct() {
		$this->req = new \lottery\fpresent\po\IsUserRegistReqPo();	//<lottery::fpresent::po::CIsUserRegistReqPo>
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
			exit("IsUserRegistReq\\{$name}：不存在此变量，请查询stub。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("IsUserRegist\\{$name}：请直接赋值为数组，无需new ***。");
		$base=array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){			
			$class=$obj->element_type;
			$arr = array();	
			if(in_array($class, $base) || get_class($obj) == "stl_bitset2"){
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
	
	function getRouteKey(){
		if($this->_routeKey){
			return $this->{$this->_routeKey};
		}
		
		return null;
	}
	
	function Serialize($bs){
		$bs->pushObject($this->req,'\lottery\fpresent\po\IsUserRegistReqPo');	//<lottery::fpresent::po::CIsUserRegistReqPo> 查询QQ用户是否存在请求

		return $bs->isGood();
	}
	
	function getCmdId(){
		return 0x28931803;
	}
}
}

if (!class_exists("lottery\fpresent\ao\IsUserRegistResp", false)) {
class IsUserRegistResp{
	private $result;	
	private $_arr_value=array();	//数组形式的类
	private $resp;	//<lottery::fpresent::po::CIsUserRegistRespPo> 查询QQ用户是否存在返回(版本>=0)

	function __get($name){
		if($name=="errmsg" && !array_key_exists('errmsg', $this->_arr_value)){
			if(array_key_exists('errMsg', $this->_arr_value)){
				$name='errMsg';
			}else{
				return "errmsg is not define.";
			}
		}
		return $this->_arr_value[$name];
	}
	
	function Unserialize($bs){
		$this->_arr_value['result'] = $bs->popUint32_t();
		$this->_arr_value['resp'] = $bs->popObject('\lottery\fpresent\po\IsUserRegistRespPo');	//<lottery::fpresent::po::CIsUserRegistRespPo> 查询QQ用户是否存在返回

	}

	function getCmdId() {
		return 0x28938803;
	}
}
}

namespace lottery\fpresent\ao;
if (!class_exists("lottery\fpresent\ao\PresentFriendReq", false)) {
class PresentFriendReq{
	private $_routeKey;
	private $_arr_value=array();	//数组形式的类
	private $req;	//<lottery::fpresent::po::CPresentFriendReqPo> 赠送好友彩票请求(版本>=0)

	function __construct() {
		$this->req = new \lottery\fpresent\po\PresentFriendReqPo();	//<lottery::fpresent::po::CPresentFriendReqPo>
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
			exit("PresentFriendReq\\{$name}：不存在此变量，请查询stub。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("PresentFriend\\{$name}：请直接赋值为数组，无需new ***。");
		$base=array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){			
			$class=$obj->element_type;
			$arr = array();	
			if(in_array($class, $base) || get_class($obj) == "stl_bitset2"){
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
	
	function getRouteKey(){
		if($this->_routeKey){
			return $this->{$this->_routeKey};
		}
		
		return null;
	}
	
	function Serialize($bs){
		$bs->pushObject($this->req,'\lottery\fpresent\po\PresentFriendReqPo');	//<lottery::fpresent::po::CPresentFriendReqPo> 赠送好友彩票请求

		return $bs->isGood();
	}
	
	function getCmdId(){
		return 0x28931802;
	}
}
}

if (!class_exists("lottery\fpresent\ao\PresentFriendResp", false)) {
class PresentFriendResp{
	private $result;	
	private $_arr_value=array();	//数组形式的类
	private $resp;	//<lottery::fpresent::po::CPresentFriendRespPo> 赠送好友彩票返回(版本>=0)

	function __get($name){
		if($name=="errmsg" && !array_key_exists('errmsg', $this->_arr_value)){
			if(array_key_exists('errMsg', $this->_arr_value)){
				$name='errMsg';
			}else{
				return "errmsg is not define.";
			}
		}
		return $this->_arr_value[$name];
	}
	
	function Unserialize($bs){
		$this->_arr_value['result'] = $bs->popUint32_t();
		$this->_arr_value['resp'] = $bs->popObject('\lottery\fpresent\po\PresentFriendRespPo');	//<lottery::fpresent::po::CPresentFriendRespPo> 赠送好友彩票返回

	}

	function getCmdId() {
		return 0x28938802;
	}
}
}

namespace lottery\fpresent\ao;
if (!class_exists("lottery\fpresent\ao\PresentWithSignReq", false)) {
class PresentWithSignReq{
	private $_routeKey;
	private $_arr_value=array();	//数组形式的类
	private $req;	//<lottery::fpresent::po::CPresentWithSignReqPo> 签名认证赠送好友彩票请求(版本>=0)

	function __construct() {
		$this->req = new \lottery\fpresent\po\PresentWithSignReqPo();	//<lottery::fpresent::po::CPresentWithSignReqPo>
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
			exit("PresentWithSignReq\\{$name}：不存在此变量，请查询stub。");
		}
	}

	function initClass($name,$val,$obj){
		if(!is_array($val)) exit("PresentWithSign\\{$name}：请直接赋值为数组，无需new ***。");
		$base=array('bool','byte','uint8_t','int8_t','uint16_t','int16_t','uint32_t','int32_t','uint64_t','int64_t','long','int','string','stl_string');
		if(strpos(get_class($obj), 'stl_')===0){			
			$class=$obj->element_type;
			$arr = array();	
			if(in_array($class, $base) || get_class($obj) == "stl_bitset2"){
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
	
	function getRouteKey(){
		if($this->_routeKey){
			return $this->{$this->_routeKey};
		}
		
		return null;
	}
	
	function Serialize($bs){
		$bs->pushObject($this->req,'\lottery\fpresent\po\PresentWithSignReqPo');	//<lottery::fpresent::po::CPresentWithSignReqPo> 签名认证赠送好友彩票请求

		return $bs->isGood();
	}
	
	function getCmdId(){
		return 0x28931801;
	}
}
}

if (!class_exists("lottery\fpresent\ao\PresentWithSignResp", false)) {
class PresentWithSignResp{
	private $result;	
	private $_arr_value=array();	//数组形式的类
	private $resp;	//<lottery::fpresent::po::CPresentWithSignRespPo> 签名认证赠送好友彩票返回(版本>=0)

	function __get($name){
		if($name=="errmsg" && !array_key_exists('errmsg', $this->_arr_value)){
			if(array_key_exists('errMsg', $this->_arr_value)){
				$name='errMsg';
			}else{
				return "errmsg is not define.";
			}
		}
		return $this->_arr_value[$name];
	}
	
	function Unserialize($bs){
		$this->_arr_value['result'] = $bs->popUint32_t();
		$this->_arr_value['resp'] = $bs->popObject('\lottery\fpresent\po\PresentWithSignRespPo');	//<lottery::fpresent::po::CPresentWithSignRespPo> 签名认证赠送好友彩票返回

	}

	function getCmdId() {
		return 0x28938801;
	}
}
}
