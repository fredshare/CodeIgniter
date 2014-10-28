<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timer extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	/**
	 * @title 每个一分钟执行
	 */
	public function oneMinute(){
		echo '过了一分钟了';
	}

	public function tenMinute(){
		echo '过了十分钟了';
	}

	public function oneHour(){
		echo '过了一个小时了';
	}

}