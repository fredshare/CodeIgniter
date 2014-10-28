<?php
/**
 * 使用 cmem 的缓存类
 *
 */

define('MODID', 108929);
define('CMD', 65536);
define('BID', 102030226);
define('HOST',"10.198.137.95");

class Mcache
{
    private $cmem;         // cmem 对象
    private $bId;
    private $router;

    /**
     * 构造函数
     */
    function __construct()
    { 
        $this->router = array(
           'flow' => 0,    //无意义，暂时未用
           'modid' => MODID,    //modid
           'cmd' => CMD,    //cmdid
           'host_ip' => '',//执行成功时，为路由ip
           'host_port' => 0//执行成功时，为路由port
        );
        $this->bId = BID; 
        $l5_time_out = 0.2;
        $errmsg = "";
        $ret = tphp_l5sys_getroute($this->router, $l5_time_out, $errmsg);
        
        if($ret == 1){
            $server = array($this->router["host_ip"].":9101");
        }else{
        	// 获取IP失败，这个时候我们不能上报，只能自己分配默认IP，并记录相关LOG
        	$server = array(HOST.":9101");
        	$this->router["host_ip"] = HOST;
        	$this->router["host_port"] = 9101;
        	$msg = "tphp_l5sys_getroute get route error ret:{$ret},errmsg:{$errmsg}";
        	OSS_LOG(__FILE__, __LINE__, LP_ERROR, "err:{$msg}\n");
        }
        $this->cmem = self::getMemCache($server);
     
    }

    /**
     * 获取cmem单例
     * @return unknown_type
     */
    public static function &getMemCache($server)
    {
        static $instance;

        if (!isset($instance))
        {
            /**
             * new tmem($connect_time_ms, $show_error);
             * $connect_time_ms是连接超时时间，单位ms，如果这里不指定，可以传0，回头用 $var->set_connect_timeout($connect_time_ms)来调用，效果一样
             * $show_error 用于调试，传1时，所有内部错误都会输出php log
             */
            $instance = new tmem(0, 1);
            /**
             * 配置服务器地址
             * $server 形如 array('10.136.9.77:9102', '10.136.9.77:9103', ...)
             * $timeout是收发数据的超时时间（毫秒）
             * $freetime是冻结时间（秒），当服务器出错后，在这个时间之内不会再尝试访问
            */
            $instance->set_servers($server, 1000, 30);   
            
        }

        return $instance;
    }

    /**
     * 得到缓存数据
     * @param $app_name      //应用名称
     * @param $var_name      //保存缓存的变量名
     * @return $data         //缓存的数组或字符串
     */
    function getCache($app_name, $var_name)
    {
    	$startTime = microtime(true);
        $result = $this->cmem->get($this->bId, $this->getKey($app_name, $var_name));
        $endTime = microtime(true);
        $useTime = round(($endTime - $startTime) * 1000, 2);
        
        $result = json_decode($result,1);
        $bool = 0;
        if(empty($result) || !is_array($result) || $result["ret"] != 0){
        	$bool = -1;
        }
        $this->update($bool,$useTime);
        if($bool == 0){
        	return $result['data'];
        }
        return false;
        
    }

    /**
     * 批量得到缓存数据（按100个分段拉取以减轻压力）
     * @param $app_name
     * @param $var_name_array
     * @return $data
     */
    function mGetCache($app_name, $var_name_array)
    {
        $result = array();

        while(!empty($var_name_array)) {
            $_sub = array_splice($var_name_array, 0, 100);
            $data = $this->_mGetCache($app_name, $_sub);

            if (is_array($data)) {
                foreach($data as $k=>$v) {
                    $result[$k] = $v;
                }
                unset($v);
            }

            unset($data);
            unset($_sub);
        }

        return $result;
    }

    /**
     * 批量得到缓存数据
     * @param $app_name
     * @param $var_name_array
     * @return $data
     */
    private function _mGetCache($app_name, $var_name_array)
    {
        $key_array = array();
        $rev_key_map = array();
        foreach($var_name_array as $var_name)
        {
            $key = $this->getKey($app_name, $var_name);
            $key_array[] = $key;
            $rev_key_map[$key] = $var_name;
        }
		$startTime = microtime(true);
        $data = $this->cmem->get($this->bId, $key_array);
		$endTime = microtime(true);
        $useTime = round(($endTime - $startTime) * 1000, 2);
        $bool = 0;
        $data = json_decode($data,1);
        if(empty($data) || !is_array($data))
        {
            $bool = -1;
        }
    	$this->update($bool,$useTime);
    	
    	if($bool == 0){
	        $result = array();
	        foreach($data as $k=>$v)
	        {
	        	$arrTemp = json_decode($v,1);
	        	$result[$rev_key_map[$k]] = empty($arrTemp["data"])? "" : $arrTemp["data"];
	        }
	
	        return $result;
    	}
    	return false;
    }

    /**
     * 设置缓存数据
     * @param $app_name    //应用名称
     * @param $var_name    //保存缓存的变量名
     * @param $data        //需要缓存的数组或字符串
     * @param $cache_lifetime //缓存生效时间，默认为NULL，使用类默认的 $this->cache_lifetime
     * @return boolean
     */
    function setCache($app_name, $var_name, $data, $expire_seconds = "")
    {
        $cas = -1;
        $data = json_encode(array("ret"=> 0,"data"=>$data));
        $startTime = microtime(true);
        $ret = $this->cmem->casset($this->bId, $this->getKey($app_name, $var_name), $data, $cas, $expire_seconds);
        $endTime = microtime(true);
        
        $useTime = round(($endTime - $startTime) * 1000, 2);
        $ret = ($ret < 0) ? -1 : 0;
        
        $this->update($ret,$useTime);
        return $ret;
        
    }

    /**
     * 清除指定缓存
     * @param $app_name    // 应用名称
     * @param $var_name    // 保存缓存的变量名,支持array(key1,key2,key3……)或者单个key
     * @return boolean
     */
    function clearCache($app_name, $var_name)
    {
        if(is_array($var_name)){
            $arr = array();
            foreach($var_name as $val){
                $arr[] = $this->getKey($app_name, $val);
            }
        }else{
            $arr = $this->getKey($app_name, $var_name);
        }
        return $this->cmem->del($this->bId, $arr);
    }


    /**
     * 生成 cmem 所用的 key
     * @param  $app_name
     * @param  $var_name
     * @return string
     */
    private function getKey($app_name, $var_name)
    {
        if (is_array($var_name))
        {
            foreach($var_name as $k=>$v)
            {
                if ( !is_array($v) )
                {
                    $var_name[$k] = strval($v);
                }
            }
            $var_name = serialize($var_name);
        }
        else
        {
            $var_name = strval($var_name);
        }

        $result = $app_name . '-' . $var_name;

        return $result;
    }
    
	/***
     * 业务访问结果更新，更新读取到的路由
     * @param $interfaceRet 业务是否正常，0表示正常，非0表示业务不正常
     * @param $interfaceTime 根据路由操作业务执行的时间，单位为毫秒（ms）
     * @return bool 更新路由是否成功true:成功，false:失败
     */
    public function update($interfaceRet, $interfaceTime) {
        $errmsg = "";
        $ret = tphp_l5sys_route_result_update($this->router, $interfaceRet, $interfaceTime * 1000, $errmsg);
        if ($ret < 0) {
              $router = $this->router;
              $host = $router['host_ip'];
              $port = $router['host_port'];
              $errorMsg = "update route result error errmsg:{$errmsg},host:{$host},port:{$port},user:{$interfaceTime}ms";
              OSS_LOG(__FILE__, __LINE__, LP_ERROR, "err:{$errorMsg}\n");
              return false;
        } else {
             return true;
        }
        
    }
    
	
}
?>
