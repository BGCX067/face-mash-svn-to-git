<?php
/**
 * $ra a当前得分
 * $rb b当前得分
 * $sa a本轮得分
 * $sb b本轮得分
 * return result[0]为a本轮比赛后的总得分 result[1]为b本轮比赛后的最后得分
 */
function pk($ra, $rb, $sa = 0, $sb = 0) {
	$result = array ();
	$ea = 1 / (1 + pow(10, ($ra - $rb) / 400));
	$eb = 1 / (1 + pow(10, ($rb - $ra) / 400));
	$result[0] = $ra +16 * ($sa - $ea);
	$result[1] = $rb +16 * ($sb - $eb);
	return $result;
}

function get_new_array($array, $key = "", $condition = "") {
	$result = array ();
	foreach ($array as $temp_array) {
		if (is_object($temp_array)) {
			$temp_array = (array) $temp_array;
		}
		if (("" != $condition && $temp_array[$condition[0]] == $condition[1]) || "" == $condition) {
			$result[] = ("" == $key) ? $temp_array : isset ($temp_array[$key]) ? $temp_array[$key] : "";
		}
	}
	return $result;
}
function clear_cache(){
  $cache = Cache :: getInstance('', null);
  $cache->clear();
}


/**
 * 锁定表单
 *
 * @param int $life_time 表单锁的有效时间(秒). 如果有效时间内未解锁, 表单锁自动失效.
 * @return boolean 成功锁定时返回true, 表单锁已存在时返回false
 */
function lockSubmit($life_time = null) {
	if (isset ($_SESSION['LOCK_SUBMIT_TIME']) && intval($_SESSION['LOCK_SUBMIT_TIME']) > time()) {
		return false;
	} else {
		$life_time = $life_time ? $life_time : 5;
		$_SESSION['LOCK_SUBMIT_TIME'] = time() + intval($life_time);
		return true;
	}
}