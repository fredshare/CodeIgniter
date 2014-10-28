<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ao_model extends MY_Model{
	public $pagesize=8;
	function __construct(){
		parent::__construct();
		//ao_api5是php5调用ao的基类，通过基类调用，方便统一控制
		require_once APPPATH . 'libraries/platform/ao_api5.php';
		require_once APPPATH . 'libraries/fpresentao/php5/fpresentao_php5_stub.php';
		//stub文件是通过autogen生成的，放在library目录下
	}
	/**
	 * @title 通过ao操作数据库的demo
	 * @return [type] [description]
	 */
	function getData(){
		//拼装ao数据
		$req = array();
        $req['source'] = __FILE__;  
		if(isset($data['userId']) && $data['userId'] !== ''){	//赠送人userId
			$req['uid'] = $data['userId'];
		}
		if(isset($data['coopDealId']) && $data['coopDealId'] !== ''){
			$req['coopDealId'] = $data['coopDealId']; //合作方重入Id
		}
		if(isset($data['fuin']) && $data['fuin'] !== ''){ //被赠送人uin
			$req['fuin'] = $data['fuin'];
		}
		$opt=array(
            'opt'=>array( 
                'caller'    => __FUNCTION__
                //,'itil'		=> '647548|647549|647550' //通过ao调用也可以直接上报itil
            ),             
            'req'=>array(
                'req'=>$req
                )
            
        );
        //通过php5基类调用调用ao
        $api = new AO_API5('lottery\fpresent\ao\PresentWithSign');
        $resp = $api->exec($opt);
    	return $resp;
	}
}