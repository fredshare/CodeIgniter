<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mysql_model extends MY_Model{
	public $pagesize=8;
	function __construct(){
		parent::__construct();	
	}
	/**
	 * @title 直接连数据库的demo
	 * @return [type] [description]
	 */
	function getData(){
		//选择数据库，在config/database.php中配置数据库信息
		$this->load->database("ppms");
		//操作数据库
		$query = $this->db->query("select * from ppms_page_data limit 0,4")->result();
		//返回信息
		return $query;
	}
}