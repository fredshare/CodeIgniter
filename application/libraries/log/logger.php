<?php
/**
 * 可以进行缓存, 利用析构函数来控制输入和输出,
 * 在 web server 上能大幅度提高性能, 在第一次写文件的时候才真正建立文件句柄
 */
class Logger
{
	const INFO  = 4;
	const WARN  = 3;
	const ERR   = 2;
	const FATAL = 1;

	// log 级别, 分为 INFO NOTICE WARN ERR (FATAL)
	private $level;

	// 保存 log 文件的时间
	private $logDate;

	// 保存 log 文件的句柄
	private $logFile;

	// 保存 log 文件名称
	private $logFileName;

	// 客户端 ip
	private $ip;

	/**
	 * 单例模式
	 *
	 * @var Logger
	 */
	private static $log;

	// 缓存
	private $records = array();

	// 记录 cache 中保存的流水的大小, 即每 20 条写一次文件
	private $maxRecordCount = 1;

	// 记录 cache 中当前保存的流水的数量
	private $curRecordCount = 0;

	// 当前进程ID
	private $processID = '0';

    private $timer = 0;

	/**
	 * 构造函数
	 *
	 * @param		string		$file, log文件名
	 * @return		void
	 */
	function __construct($logname = '')
	{
		if ( !empty(self::$log) ) {
			return;
		}

		if ( strlen($logname) ) {
			$logname		= self::_transFilename($logname);
			$logname		= basename($logname, '.log');//basename获取路径中的文件名
		} else {
			$logname		= basename($_SERVER['SCRIPT_NAME'], '.php');
		}
		$this->logFileName	= $logname . '.log';
		$this->level		= defined('LOG_LEVEL') ? LOG_LEVEL : self::ERR;
		$x_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "";
        $r_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
		$this->ip			= str_pad( preg_replace('/[^0-9\.]/', '', $x_ip ? $x_ip : $r_ip), 15, ' ', STR_PAD_LEFT);
		$this->processID	= str_pad( getmygid(), 5, ' ', STR_PAD_LEFT );

        $this->timer        = microtime(true);

		self::$log			= $this;
	}

	/**
	 * 析构函数
	 */
	function __destruct()
	{
		if ( $this->curRecordCount > 0 ) {
			if ( empty($this->logFile) || $this->logDate != date('Ymd') ) {
				if ( !empty($this->logFile) ) {
					fclose($this->logFile);
				}
				$this->_setHandle();
			}

			$str = implode("\n", $this->records);
			fwrite($this->logFile, $str . "\n");
			$this->records = array();
			$this->curRecordCount = 0;
		}

		if ( !empty($this->logFile) ) {
			fclose($this->logFile);
		}
	}

	/**
	 * 打开log文件句柄, 初始化成员变量
	 */
	private function _setHandle()
	{
		$this->logDate	= date('Ymd');
		//$logDir 		= LOG_ROOT . $this->logDate . '/';
		//echo date('w');
		$logDir 		= LOG_ROOT;
		if ( !file_exists($logDir) ) {
			@umask(0);
			@mkdir($logDir, 0777, true);
		}
		$this->logFile	= fopen($logDir . $this->logDate . '_'.$this->logFileName, 'a');
	}

	/**
	 * 转义文件名包含的非法字符
	 *
	 * @param		string		$filename, 文件名
	 *
	 * @return		string		$filename
	 */
	private function _transFilename($filename)
	{
		if  ( !strlen($filename) ) {
			return $filename;
		}

		$filename = str_replace('\\', '#', $filename);
		$filename = str_replace('/', '#', $filename);
		$filename = str_replace(':', ';', $filename);
		$filename = str_replace('"', '$', $filename);
		$filename = str_replace('*', '@', $filename);
		$filename = str_replace('?', '!', $filename);
		$filename = str_replace('>', ')', $filename);
		$filename = str_replace('<', '(', $filename);
		$filename = str_replace('|', ']', $filename);

		return $filename;
	}

	/**
	 * 初始化 log 文件名
	 *
	 * @param		string			$filename, log 文件名
	 *
	 * @return		void
	 */
	public static function init()
	{
		if ( empty(self::$log) ) {
			$stack	= debug_backtrace();
			$top_call = $stack[0];
			$logname = basename($top_call['file'], '.php');
			self::$log = new Logger($logname);
		}
	}

	/**
	 * 检测日志文件是否是当前日期的, 主要考虑 Server, Daemon
	 */
	private function _write($s)
	{
		//echo $s;
		if ( !strlen($s) ) {
			return false;
		}

		self::$log->records[] = $s;
		self::$log->curRecordCount++;

		if ( self::$log->curRecordCount >= self::$log->maxRecordCount ) {
			
			if ( empty(self::$log->logFile) || self::$log->logDate != date('Ymd') ) {
				if ( !empty(self::$log->logFile) ) {
					fclose(self::$log->logFile);
				}
				self::$log->_setHandle();
			}
			$str = implode("\n", self::$log->records);

			fwrite(self::$log->logFile, $str . "\n");
			self::$log->curRecordCount = 0;
			self::$log->records = array();
		}
		return true;
	}

	/**
	 * 记录 info 型的 log
	 *
	 * @param		string		$str, log信息
	 */
	public static function info($str)
	{
		if ( !strlen($str) ) {
			return false;
		}
		if ( empty(self::$log) ) {
			self::$log = new Logger();
		}
		if (self::$log->level < self::INFO) {
			return false;
		}
		$s = date('H:i:s');
		$s .= "|INFO|" . self::$log->getTimer();
		$s .= "|" . self::$log->ip;
		$s .= "|" . $str;
		self::_write($s);

		return true;
	}

	/**
	 * 记录 error 型的 log
	 *
	 * @param		string		$str, log信息
	 */
	public static function err($str)
	{
		if ( !strlen($str) ) {
			return false;
		}
		if ( empty(self::$log) ) {
			self::$log = new Logger();
		}
		if (self::$log->level < self::ERR) {
			return false;
		}
		$s = date('H:i:s');
		$s .= "| ERR|" . self::$log->getTimer();
		$s .= "|" . self::$log->ip;
		$s .= "|" . $str;
		self::_write($s);

		return true;
	}

    public function getTimer() {
        return str_pad(round((microtime(true) - $this->timer)*1000, 2).'ms', 9, ' ', STR_PAD_LEFT);
    }
}

//End of script