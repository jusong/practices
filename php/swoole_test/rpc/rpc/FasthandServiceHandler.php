<?php
namespace Fasthand\php;

require_once 'common.php';
require_once 'dao/datasourceFactroy.php';
require_once 'config/autoload.php';

class FasthandServiceHandler implements \Fasthand\FasthandServiceIf {
	/**
	 * 通过ID获取用户信息
	 */
	public function getUserInfo($userId) {
		$fasthandUserService = new \FasthandUserService();
		$fasthand_user = new \Fasthand_user();
		$fasthand_user->setId($userId);
		$res = $fasthandUserService->loadById($fasthand_user);
		if (!$res) {
			$oth = new \shared\InvalideService();
			$oth->code = 400;
			$oth->message = "error";
			throw $oth;
		}
		$a = get_object_vars($fasthand_user);
		return $a;
	}

	public function getAreaInfo($parent_code = 0) {
		$fasthandAreaService = new \FasthandAreaService();
		$res = $fasthandAreaArray = $fasthandAreaService->loadAll();
		if (!$res) {
			$oth = new \shared\InvalideService();
			$oth->code = 400;
			$oth->message = "error";
			throw $oth;
		}
		$areaArray = array();
		foreach ($fasthandAreaArray as $fasthand_area){
			$area = new \Fasthand\Area();
			$area->parent_code = $fasthand_area->getParent_id();
			$area->code = $fasthand_area->getCode();
			$area->name = $fasthand_area->getName();
			$area->type = $fasthand_area->getType();
			$areaArray[] = $area;
		}
		return $areaArray;
	}

	public function getAreaNameList() {
		$fasthandAreaService = new \FasthandAreaService();
		$res = $fasthandAreaArray = $fasthandAreaService->loadAll();
		if (!$res) {
			$oth = new \shared\InvalideService();
			$oth->code = 400;
			$oth->message = "error";
			throw $oth;
		}
		$areaNameArray = array();
		foreach ($fasthandAreaArray as $fasthand_area){
			$areaName = new \shared\SharedStruct();
			$areaName->key = $fasthand_area->getCode();
			$areaName->value = $fasthand_area->getName();
			$areaNameArray[] = $areaName;
		}
		return $areaNameArray;
	}

	/**
	 * 获取当前系统时间
	 */
	public function getTime() {
		return time();
	}
}
?>
