<?php
class Data {
	public $__TYPE = '';
	public $__COMITEMS = array();
	public $__SPECITEMS = array();
}

class SrvUtil {
    /**
     * 将对象(包括数组、对象、标量)转换成通用Data对象
     * @param mixed $obj
     * @return Fasthand\Service\Common\Data
     */
	public static function toData($obj) {
		$data = new Data;
		if (is_array($obj)) {
            /* 数组 */
			$data->__TYPE = "__ARRAY";
			foreach($obj as $key => $val) {
				if (is_array($val) || is_object($val)) {
					$data->__SPECITEMS[$key] = self::toData($val);
				} else {
					$data->__COMITEMS[$key] = $val;
				}
			}
		} else if (is_object($obj)) {
            /* 对象 */
			$className = get_class($obj);
			$data->__TYPE = $className;
            /* 此处只获取对象公有成员 */
            $propArray = array_keys(get_object_vars($obj));
			foreach($propArray as $prop) {
				$prop_val = $obj->$prop;
				if (is_array($prop_val) || is_object($prop_val)) {
					$data->__SPECITEMS[$prop] = self::toData($prop_val);
				} else {
					$data->__COMITEMS[$prop] = $prop_val;
				}
			}
		} else {
            /* 标量 */
			$data->__TYPE = "__SCALAR";
			$data->__COMITEMS[] = $obj;
		}
		return $data;
	}

    /**
     * 将通用Data对象转换成原始对象(包括数组、对象、标量)
     * @param Fasthand\Service\Common\Data $data
     * @return mixed
     */
	public static function fromData($data) {
		if (get_class($data) !== "Fasthand\Service\Common\Data") return null;

		$obj = null;
		$type = $data->__TYPE;
		if ($type == "__ARRAY") {
            /* 数组 */
			if (is_array($data->__COMITEMS)) {
				$obj = $data->__COMITEMS;
			} else {
				$obj = array();
			}
			if ($data->__SPECITEMS) {
				foreach($data->__SPECITEMS as $key => $val) {
					$obj[$key] = self::fromData($val);
				}
			}
            ksort($obj);
		} else if ($type == "__SCALAR") {
            /* 标量 */
			if (is_array($data->__COMITEMS)) {
				$obj = $data->__COMITEMS[0];
			}
		} else {
            /* 对象 */
			$obj = new $type();
			if ($data->__COMITEMS) {
				foreach($data->__COMITEMS as $key => $val) {
					$obj->$key = $val;
				}
			}
			if ($data->__SPECITEMS) {
				foreach($data->__SPECITEMS as $key => $val) {
					$obj->$key = self::fromData($val);
				}
			}
		}
		return $obj;
	}
}
