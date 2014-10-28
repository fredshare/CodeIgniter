<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	@info 封装对PHP调用ao接口
 *	@method
 *	1、引用目标的php5stub：
 *		require_once APPPATH . 'libraries/userinfoao/php5/userinfoao_php5_stub.php';
 *	2、执行调用
 *		$api = new AO_API5('lottery\userinfo\ao\GetUserinfo');
 *		$resp = $api->exec($param);
 *	@param method:带命名空间的方法，比如：lottery\userinfo\ao\GetUserinfo	   
 *	@author sharexie
 *	@time 2013-09-29
**/
class AO_API5 {
	function __construct($method){	
		$this->method = $method;
		
	}
	function getRemoteIp(){
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	// 执行接口
	function exec($param){
		//在api处统一控制opt参数
		$optPara = array( 
				'uin'       => time(),
				'caller' 	=> 'lottery',
				'timeout' 	=> 10,
				'domainid'	=> 152, 
				'uid' 		=> 0, 
				'appid' 	=> 17000101, 
				'employeeid'=> 0,
				'clientip'	=> ip2long(getRemoteIp())
            );
		if(isset($param['opt'])){
			$optExec = array_merge($optPara,$param['opt']);
		}else{
			$optExec = $optPara;
		}
		/**
		 * opt参数说明:如无特殊需求，业务方只需填写uin(用于路由)和operator(用于记录操作者)即可
		 * uin 		: 使用Mod+L5方式设置路由(对应cpp cntlInfo中的setRouteKey)，一般填写用户QQ（对应setDwUin）
		 * operator	: 操作者ID，一般填写用户QQ（对应setDwOperatorId）
		 * host 	: 除了使用配置中心，调用方也可以自己指定服务器，ip：port必须同时填写（对应setPeerIPPort）
		 * passport : 用于传递skey，某些APP需要验证登录态(对应setSPassport), (该字段最大只支持10个字节，1.1.7版本后的so请使用skey字段)
		 * caller 	: 调用方名字，用于模调(对应setCallerName)
		 * itil 	: 填写申请的itil Id(对应setItilId:success|fail|timeout) 注意：使用此项需载入itil.so扩展，否则会报错。
		 * timeout 	: 超时时间，以秒为单位，特殊情况下可以调大
		 * skey 	: session key (对应 setSKey)
		 * uid 		: 预留, 不用填 (对应setQwUid)
		 * appid 	: 接入ptlogin的业务id (对应setDwAppId)
		 * domainid : 域名id (对应setDwDomainId)
		 * employeeid:预留,不用填,工号id (对应setQwEmployeeId)
		**/
		$opt=array(
            'opt'=> $optExec,            
            'req'=> $param['req']
        );
        //发起请求
        $result = WebStubCntl2::request(
            $this->method,
            $opt
        );
        /**
         * 返回结果
         * array( 
         *   'code' => 0,//返回码：0，成功；-1，服务错误（具体参考msg）；其余值为后台返回错误
         *   'msg' => 'getCmdId failed',//返回信息，成功时为‘’
         *   'data' => array()//后台返回数据，纯数组
         *	)
        */
        if(isset($_REQUEST['debug']) && $_REQUEST['debug']=='ao'){
        	print_r($opt);
        	print_r($result);
        }
        if($result['code'] == 0){
        	return $result['data'];
        }else if($result['code'] == -1){
        	return array(
        		'result' => 0xf0000,
        		'msg'    => '调用ao失败'
        		);
        }else{
        	if(empty($result['data'])){
        		return array('result'=>$result['code'],'resp'=>array('errCode'=>$result['code'],'errMsg'=>$result['msg']));
        	}
        	return $result['data'];
        }
        
	}
}
?>