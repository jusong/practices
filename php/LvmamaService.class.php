<?php
/**
 * 驴妈妈接口服务
 * @author  blacknc <blacknc@163.com>
 * @date    2018-01-05 16:40
 */
require_once 'common/init.php';

class LvmamaService extends RootService {

	//生产环境配置
	//private $appkey = ""; //分销商ID
	//private $secret = ""; //接口密钥 
	//private $api	= "http://api.lvmama.com";

	//测试环境配置
	private $appkey = "SHENZHOUJIAJIAO"; //分销商ID
	private $secret = "d567d74207758a16da6e00ab963fa682"; //接口密钥 
	private $api	= "http://180.168.128.250:8090/tnt_api";
	//private $api	= "http://api.lvmama.com";


	/**
	 * 获取产品列表
	 * @return array
	 */
	public function productInfoListByPage() {

		$api = $this->api."/distributorApi/2.0/api/ticketProd/productInfoListByPage";
		$requestData = array(
			'appKey'	=> $this->appkey,
			'messageFormat'	=> 'json',
			'timestamp'		=> $_SERVER['REQUEST_TIME'],
			'sign'			=> md5($this->secret.$_SERVER['REQUEST_TIME'].$this->secret),
			'currentPage'	=> 1
		);
		//var_dump($api, $requestData);
		$res = $this->getRequest($api, $requestData);
		return $res;
	}

	/**
	 * 获取产品列表
	 * @param int/array $productIds 商品ID/数组
	 * @return array
	 */
	public function productInfoList($productIds, $refreshCache = false) {

		if (!is_array($productIds)) {
			$productIds = (array)$productIds;
		}

		$api = $this->api."/distributorApi/2.0/api/ticketProd/productInfoList";
		$requestData = array(
			'appKey'	=> $this->appkey,
			'messageFormat'	=> 'json',
			'timestamp'		=> $_SERVER['REQUEST_TIME'],
			'sign'			=> md5($this->secret.$_SERVER['REQUEST_TIME'].$this->secret),
			'productIds'		=> implode(',', $productIds)
		);
		//var_dump($api, $requestData);
		$res = $this->getRequest($api, $requestData);
		return $res;
	}

	/**
	 * 获取商品信息
	 * @param int/array $goodsIds 商品ID/数组
	 * @return array
	 */
	public function goodInfoList($goodsIds, $refreshCache = false) {

		if (empty($goodsIds)) {
			$this->error = '获取商品信息参数错误';
			return false;
		}

		if (!is_array($goodsIds)) {
			$goodsIds = (array)$goodsIds;
		}

		$goodsArr = array();
		$cacheKeyPrefix = 'lvmama_goods_id_';
		if (!$refreshCache) { //从缓存中读取商品数据
			foreach ($goodsIds as $key => $goodsId) {
				$goods = MemberCache::get($cacheKeyPrefix.$goodsId);
				if (!empty($goods)) {
					$goodsArr[] = $goods;
					unset($goodsIds[$key]);
				}
			}
			if (empty($goodsIds)) {
				return $goodsArr;
			}
		}

		$api = $this->api."/distributorApi/2.0/api/ticketProd/goodInfoList";
		$requestData = array(
			'appKey'	=> $this->appkey,
			'messageFormat'	=> 'json',
			'timestamp'		=> $_SERVER['REQUEST_TIME'],
			'sign'			=> md5($this->secret.$_SERVER['REQUEST_TIME'].$this->secret),
			'goodsIds'		=> implode(',', $goodsIds)
		);
		//var_dump($api, $requestData);
		$res = $this->getRequest($api, $requestData);
		return $res;
	}


	private function getRequest($url, $data) {

		if (empty($url) || empty($data)) {
			$this->error = '请求参数错误';
			return false;
		}

		$dataStr = '';
		foreach ($data as $k => $v) {
			$dataStr .= $k.'='.$v.'&';
		}
		$dataStr = substr($dataStr, 0, -1);
		$url .= '?'.$dataStr;
		var_dump($url);

		$ch = curl_init();
		$options = array(
			CURLOPT_URL				=> $url,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
		);
		curl_setopt_array($ch, $options);
		$res	= curl_exec($ch);
		$errno  = curl_errno($ch);
        if ($errno) {
			$this->error = '请求失败';
            LogTool::error(__METHOD__, func_get_args(), 'CURL_ERROR error: '.$errno.'-'.curl_error($ch));
            return false;
        }
        if (200 != ($httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE))) {
			$this->error = '请求失败';
            LogTool::error(__METHOD__, func_get_args(), 'CURL_ERROR http_code: '.$httpCode);
            return false;
        }

		return $res;
	}

	private function postRequest($url, $data) {

		if (empty($url) || empty($data)) {
			$this->error = '请求参数错误';
			return false;
		}

		$ch = curl_init();
		$options = array(
			CURLOPT_URL				=> $url,
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> $data,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
            CURLOPT_HTTPHEADER		=> array(
                'Content-type: application/json;charset=utf-8',
				'Accept: application/json'
            )
		);
		curl_setopt_array($ch, $options);
		$res	= curl_exec($ch);
		$errno  = curl_errno($ch);
        if ($errno) {
			$this->error = '请求失败';
            LogTool::error(__METHOD__, func_get_args(), 'CURL_ERROR error: '.$errno.'-'.curl_error($ch));
            return false;
        }
        if (200 != ($httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE))) {
			$this->error = '请求失败';
            LogTool::error(__METHOD__, func_get_args(), 'CURL_ERROR http_code: '.$httpCode);
            return false;
        }

		return $res;
	}
}

$lvmm = new LvmamaService();

$res = $lvmm->productInfoListByPage();
var_dump($res);
echo "\n";

$res  = $lvmm->productInfoList('862573');
var_dump($res);
echo "\n";

$res  = $lvmm->productInfoList('434492');
var_dump($res);
echo "\n";

$res  = $lvmm->goodInfoList('3357027');
var_dump($res);
echo "\n";

$res  = $lvmm->goodInfoList('1646663');
var_dump($res);
echo "\n";
