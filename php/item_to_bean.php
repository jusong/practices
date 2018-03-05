<?php
function item_to_bean($item, $rules) {
    if (empty($item) OR empty($rules)) {
        return $item;
    }

    $item = (array)$item;
    foreach($rules as $rule) {
        if (2 > count($rule)) { /* 规则不合法，忽略 */
            continue;
        }
        @list($field, $_rule, $saveField, $_addnRule) = $rule;
        $saveField = isset($saveField) ? (empty($saveField) ? $field : $saveField) : $field.'_text';
        $_addnRule = isset($_addnRule) ? $_addnRule : 'normal';

        switch($_addnRule) {
        case 'callback':
            $item[$saveField] = call_user_func($_rule, $item[$field]);
            break;
        case 'field':
            $item[$saveField] = $item[$_rule];
            break;
        case 'normal':
            $item[$saveField] = $_rule;
            break;
        }
    }

    return $item;
}

class T {
	public function getName($id) {
		return 'name_of_'.$id;
	}
	public static function getAge($age) {
		return $age.'岁';
	}
	public function getSex($sex) {
		return $sex == 1 ? '男' : '女';
	}
}

function dateFormat($time) {
	return date('Y-m-d H:i:s', $time);
}

$t = new T();
$rules = array(
		array('id', array($t, 'getName'), 'name', 'callback'),
		array('age', 'T::getAge', '', 'callback'),
		array('sex', array('T', 'getSex'), null, 'callback'),
		array('birth', 'dateFormat', null, 'callback'),
		array('uid', 'id', '', 'field'),
		array('mobile', '18210672147', '', 'normal'),
		array('adr', 'an hui bozhou', ''),
		);

$item = array(
		'id' => 199,
		'age' => 12,
		'sex' => 1,
		'birth' => 1493868799,
		'uid' => 102,
		'mobile' => 234234,
		'adr' => 'beijing',
		);

$item = item_to_bean($item, $rules);
print_r($item);
