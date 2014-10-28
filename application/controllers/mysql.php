<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mysql extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("mysql_model");
	}
	/**
	 * @title 直连数据库demo.
	 */
	public function index(){
		$data = $this->mysql_model->getData();
		$this->view("show.php",array("data"=>$data));
	}
}

/* End of file log.php */
/* Location: ./application/controllers/log.php */