<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itil extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	/**
	 * @title itil上报demo
	 */
	public function oneMinute(){
		if(extension_loaded('itil')) {
            exd_Attr_API2(634162,1);
            return true;
        }else{
        	return false;
        }
		
	}


}