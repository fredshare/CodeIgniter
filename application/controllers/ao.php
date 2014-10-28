<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ao extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("ao_model");
	}
	/**
	 * @title 链接ao服务操作数据库demo.
	 */
	public function index(){
		$data = $this->ao_model->getData();
		$this->view("show.php",array("data"=>$data));
	}
}

/* End of file log.php */
/* Location: ./application/controllers/log.php */