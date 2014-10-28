<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
        $this -> checkToken();
    }
    public function __destruct(){
    }
	protected $content = '';
	protected $data = array();
	
	// 组合输出 
	protected function view($type = 'html') {
		// 可选 $type = html | json
		if($type == 'html'){
			$this->parser->parse($this->content, $this->data);
		}else if($type == 'json'){
			$template = $this->parser->parse($this->content, $this->data, true);
			//这是为了兼容json的输出
			$template = str_replace(array(',]',',}'), array(']', '}'));
			$this->output->append_output($template);
		}
	}
	//将页面以json格式输出
	protected function showJson($data){
		$this->output->set_header("Content-Type:text/html; charset=UTF-8");
		require_once APPPATH . 'libraries/Json.php';
		$this->output->append_output(Json::encode($data));
	}
     //将页面以json格式输出
    protected function showJsonByOri($data){
        $this->output->set_header("Content-Type:text/html; charset=UTF-8");
        $r = preg_replace('/\\\n/i', '',json_encode($data));
        $this->output->append_output($r);
    }
	//将页面以jsonp的格式输出
	protected function showJsonp($data,$callback="callback"){
        $callback = $this->checkFucName($callback);
        $callback = $callback ? $callback : "callback";
        $this->output->append_output($callback."(".json_encode($data).")");
    }
    //函数命名检测
    protected function checkFucName($name){
        if(preg_match('/^[a-zA-Z_][a-zA-Z0-9_\.]*/', $name)){
            return $name;
        } else {
            return false;
        }
    }
	//alert提示并跳转
	protected function alertGo($info,$url){
		echo('<script language="javascript">alert("'.$info.'");location.href="'.$url.'"</script>');
		exit;
	}
	//alert提示并返回
	protected function alertGoback($info,$step=-1){
		echo('<script language="javascript">alert("'.$info.'");history.go('.$step.')</script>');
		exit;
	}
	//页面测试
	protected function pageTest(){
		echo "page testing".__FILE__."<br/>";
		print_r($_SERVER);
		
	}
	//time33加密算法
	protected function times33($string){
		$code = 5381;
		for ($i = 0, $len = strlen($string); $i < $len; $i++) {
			$code = (int) (($code << 5) + $code + ord($string{$i})) & 0x7fffffff;
					  //与0x7fffffff做与运算是为了防止内存溢出
		}
		return $code;
	}

    protected function showError($code,$msg){
    	//var_dump($code,$msg);
    	$this->showJson(array("errCode"=>$code,"msg"=>$msg,"data"=>array()));
    	//exit;	
    }

    protected function debug($msg){
    	if($this->input->get("debug")==1){
    		echo "<pre>";
    		var_dump($msg);
    	}
    }

    /**
	 * 接口成功回调
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 * @title 接口成功回调
	 */
	protected function showSuccess($arr,$use=1){
		$r = array(
			"errCode"=>0,
			'data'=>$arr,
			'retCode'=>0,
			'msg'=>""
		);
		if($use == 1){
            $this->showJson($r);
        }else if($use ==2){
            $this->showJsonByOri($r);
        }
	}

}