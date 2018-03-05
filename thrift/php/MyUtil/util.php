<?php
namespace MyUtil;

require_once 'config/autoload.php';

class MyUtil {
	/**
	 * 数组转对象(支持嵌套)
	 * 
	 * @param array $arr
	 * @param string $className
	 * @return object
	 */
    public static function array2object($arr, $className = "") {
		if (!$className && isset($arr["__TYPE"]) && !empty($arr["__TYPE"])) {
			$className = $arr["__TYPE"];
			$arr = $arr["__PROPS"];
		}
		
		if ($className) {
			//对象数组
            $class = new $className();
            foreach($arr as $key => $value) {
 	   			if (is_array($value)) {
 	   				if (isset($value["__TYPE"])) {
 	   					$class->$key = self::array2object($value["__PROPS"], $value["__TYPE"]);
 	   				} else {
 	   					$class->$key = self::array2object($value);
 	   				}
 	   			} else {
                	$class->$key = $value;
 	   			}
        	}
			return $class;
		} else {
			//普通数组
			foreach($arr as $key => $value) {
 	   			if (is_array($value)) {
 	   				if (isset($value["__TYPE"])) {
 	   					$arr[$key] = self::array2object($value["__PROPS"], $value["__TYPE"]);
 	   				} else {
 	   					$arr[$key] = self::array2object($value);
 	   				}
 	   			} else {
                	$arr[$key] = $value;
 	   			}
			}
        	return $arr;
		}
    }
	
	/**
	 * 变量转数组(支持对象数组及嵌套)
	 *
	 * @param object $object
	 * @return array
	 */
    public static function object2array($object) {
        $arr = array();
		if (is_object($object)) {
            $propArray = array_keys(get_class_vars(get_class($object)));
            foreach($propArray as $prop) {
	 	   		$prop_value = $object->$prop;
	 	    	if (is_object($prop_value)) {
                  	$arr[$prop] = array("__TYPE" => get_class($prop_value), "__PROPS" => self::object2array($prop_value));
		 	   	} else if (is_array($prop_value)) {
		 	   		$arr[$prop] = self::object2array($prop_value);
	 	    	} else {
                 	$arr[$prop] = $prop_value;
	 	    	}
            }
		} else if (is_array($object)) {
			foreach($object as $key => $value) {
	 	    	if (is_object($value)) {
                  	$arr[$key] = array("__TYPE" => get_class($value), "__PROPS" => self::object2array($value));
		 	   	} else if (is_array($value)) {
		 	   		$arr[$key] = self::object2array($value);
	 	    	} else {
                 	$arr[$key] = $value;
	 	    	}
			}
		} else {
			$arr[] = $object;
		}

        return $arr;
    }
}
?>
