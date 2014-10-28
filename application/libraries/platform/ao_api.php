<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'libraries/platform/web_stub_cntl.php';	
/*
	封装对configcenter4的调用过程。要求请求方法和返回方法的命名是一致的，如：xxxxreq,xxxxresp
	调用方法：
	1、引用目标stub：
		require_once APPPATH . 'libraries/platform/ao_api.php';
		require_once APPPATH . 'libraries/boss/categoryaov5_stub4php.php';
	2、执行调用
		$api = new AO_API('GetSellerInfolist');
		$resp = $api->exec($param);*/
class AO_API {
	function __construct($method){	
		$this->method = $method;
	}
	// 执行接口
	function exec($param,$caller="lottery",$timeout=3,$ip=""){
		// 发起请求
		$method_req = $this->method . 'Req';
		$req = new $method_req();
		foreach($param as $key => $value){
			$req->$key = $value;
		}
		// 获得请求
		$method_resp = $this->method . 'Resp';
		$resp = new $method_resp();
		$ret = $this->invoke($req, $resp,$caller,$timeout,$ip);
		return $resp;
	}
	function invoke($req, $resp,$caller,$timeout,$ip=""){
		$cntl = new WebStubCntl();
		$cntl->setDwUin(rand(1,10000000));  //路由控制，如果要随机路由的话，传一个随机整数就可以了。固定路由的话，写一个固定数字。
		if(!$caller){
			$caller=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		}
    	$cntl->setCallerName($caller);		
    	if($ip!==""){
    		$ip = explode(":",$ip);
    		$cntl->setPeerIPPort($ip[0],$ip[1]);
    	}
		$ret = $cntl->invoke($req, $resp, $timeout);
		if ($ret != 0){
			//printf("Invoke failed, RetCode[0x%x] ErrMsg[%s]\n", $ret, $cntl->getLastErrMsg());
			//echo '<br />请求服务器内容时出错';
			$msg = "Invoke failed, RetCode[0x".$ret."] ErrMsg[".$cntl->getLastErrMsg()." 请求服务器内容时出错] from: " . $this->method;
			exd_adv_attr_set2(634268, iconv('UTF-8','GBK',$msg));
			return $ret;
		}
	}
}
?>