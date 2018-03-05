<?php
namespace test;
class CacheMemcached {
	private static $cas_token = array(); //cas_token集合
	public $handler = null;

	/**
	 * 架构函数
	 * @param array $options 缓存参数
	 * @access public
	 */
	function __construct($options = array()) {
		if ( !extension_loaded('memcached') ) {
			throw new Exception(L('_NOT_SUPPERT_').':memcached');
		}
		$this->options = $options;
		$this->handler = new \Memcached($this->options['host'] . $this->options['port']);
		if(!count($this->handler->getServerList())) {
			$this->handler->setOption(\Memcached::OPT_RECV_TIMEOUT, $this->options['timeout']);
			$this->handler->setOption(\Memcached::OPT_SEND_TIMEOUT, $this->options['timeout']);
			$this->handler->setOption(\Memcached::OPT_COMPRESSION , $this->options['compress']);
			$this->handler->setOption(\Memcached::OPT_TCP_NODELAY, true);
			$this->handler->setOption(\Memcached::OPT_PREFIX_KEY, $this->options['prefix']);
			$this->connected = $this->handler->addServer($this->options['host'], $this->options['port']);
		}
	}

	/**
	 * 是否连接
	 * @access private
	 * @return boolen
	 */
	private function isConnected() {
		return $this->connected;
	}

	/**
	 * 读取缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @return mixed
	 */
	public function get($name) {
		return $this->handler->get($name);
	}

	/**
	 * 写入缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @param mixed $value  存储数据
	 * @param integer $expire  有效时间（秒）
	 * @return boolen
	 */
	public function set($name, $value, $expire = null) {
		if(is_null($expire)) {
			$expire  =  $this->options['expire'];
		}else if($expire > 0){
			$expire  =  $expire;
		}
		if($this->handler->set($name, $value, $expire)) {
			return true;
		}
		return false;
	}

	/**
	 * 带检查的读取缓存(CAS)
	 * @access public 
	 * @param string $key
	 * @return mixed
	 */
	public function gets($key) {
		$res = $this->handler->get($key, null, $cas);
		self::$cas_token[$key] = $cas;
		echo "cas: ${cas}".PHP_EOL;
		return $res;
	}

	/**
	 * 添加缓存
	 * @access public
	 * @param string $key 键名
	 * @param mixed $data 值
	 * @param int $expire 失效时间，单位：秒
	 * @return boolean
	 */
	public function cas($key, $data, $expire = null) {
		if (empty($expire)) {
			$expire = $this->options['expire'];
		}
		$cas = self::$cas_token[$key];
		if ($cas) {
			$res = $this->handler->cas($cas, $key, $cas.":".$data, $expire);
		} else {
			$res = $this->handler->set($key, $data, $expire);
		}
		if ($res) {
			return true;
		}
		return false;
	}	

	/**
	 * 删除缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @return boolen
	 */
	public function rm($name, $ttl = false) {
		return $ttl === false ?
			$this->handler->delete($name) :
			$this->handler->delete($name, $ttl);
	}

	/**
	 * 清除缓存
	 * @access public
	 * @return boolen
	 */
	public function clear() {
		return $this->handler->flush();
	}
}

$options = array(
		"host" => "123.56.159.51",
		"port" => "11211",
		"timeout" => "1",
		"compress" => true,
		"prefix" => "test",
		"expire" => "3600",
		);
$ch = new CacheMemcached($options);

$childPidArr = array();

$count = 0;
$key = $argv[1];
for ($j = 0; $j < 10; $j++) {
	$pid = pcntl_fork();
	if ($pid == 0) {
		$pid = posix_getpid();
		for ($i = 0; $i < 10; $i++) {
			$res = $ch->gets($key);
			if ($res) {
				echo "pid: {$pid}; gets res: {$res}".PHP_EOL;
				$resArr = explode(":", $res);
				$count = end($resArr);
			} else {
				echo "pid: {$pid}; gets Faield: ".$ch->handler->getResultCode().PHP_EOL;
			}
			$count = $count + 1;
			if ($ch->cas($key, $count)) {
				echo "pid: {$pid}; cas: {$count}".PHP_EOL;
			} else {
				echo "pid: {$pid}; cas Faield: ".$ch->handler->getResultCode().PHP_EOL;
			}
		}
		exit;
	}
	$childPidArr[] = $pid;
}

foreach($childPidArr as $pid) {
	pcntl_waitpid($pid, $status);
	//echo "pid: {$pid}; status: {$status}".PHP_EOL;
}

$res = $ch->gets($key);
if ($res) {
	echo "pid: 0; gets res: {$res}".PHP_EOL;
} else {
	echo "pid: 0; gets Faield: ".$ch->handler->getResultCode().PHP_EOL;
}
echo "end\n";
