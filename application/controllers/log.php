<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends MY_Controller {
	public function __construct() {
		parent::__construct();
		require_once APPPATH . 'libraries/log/logger.php';
		if (!defined('LOG_ROOT')) define('LOG_ROOT',APPPATH.'logs/active/');
		//日志路径最好设置在/data/log目录下，并按照controller分子目录
	}
	/**
	 * @title 日志管理demo.
	 */
	public function index(){
		$logFile = "logTest.log";
		$log = new Logger($logFile);
		$log->err("日志demo管理err");
		$log->info("日志demo管理info");
	}
}

/* End of file log.php */
/* Location: ./application/controllers/log.php */