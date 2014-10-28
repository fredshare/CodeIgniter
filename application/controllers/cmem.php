<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmem extends MY_Controller {
	public function __construct() {
		parent::__construct();
		require_once APPPATH . 'libraries/cmem/Mcache.php';
	}
	/**
	 * @title 日志管理demo.
	 */
	public function index()
	{
		//使用封装的Mcache
		$appname = "test";
		$key = "php-cmem-plugin";
		$value = "test";
		$mcache = new Mcache();
		$ret = $mcache->setCache($appname, $key, $value, 10);
		var_dump($ret);
		$ret = $mcache->getCache($appname, $key);
		var_dump($ret);
		$ret = $mcache->clearCache($appname, $key);
		var_dump($ret);
	}
}
?>
