<?php

class UserCountModel extends Model{

	/**
	 * 用户信息统计增加
	 *  可以根据需求自行增加需要的字段$type,无需修改代码，直接调用即可
	 * $inc  增加数量，默认1
	 */
	function setInc($number, $type, $inc = 1) {
		if (is_array($number)) {
			foreach ($number as $id) {
				$this->setInc($id, $type, $inc);
			}
		} else {
			if ($number < 1) {
				return false;
			}
			if ($arr = $this->where(array (
					'number' => $number,
					'type' => $type
				))->find()) {
				$arr['sum'] = $arr['sum'] + $inc;
				$this->where(array (
					'number' => $number,
					'type' => $type
				))->save($arr);
			} else {
				$this->add(array (
					'number' => $number,
					'type' => $type,
					'sum' => $inc
				));
			}
		}
		$this->delCache($number);
	}

	/**
	 * 同上
	 * $dec为减少
	 */
	function setDec($number, $type, $dec = 1) {
		if (is_array($number)) {
			foreach ($number as $id) {
				$this->setDec($id, $type, $dec);
			}
		} else {
			if ($number < 1) {
				return false;
			}
			if ($arr = $this->where(array (
					'number' => $number,
					'type' => $type
				))->find()) {
				$arr['sum'] = $arr['sum'] - $dec;
				$this->where(array (
					'number' => $number,
					'type' => $type
				))->save($arr);
			} else {
				$this->add(array (
					'number' => $number,
					'type' => $type,
					'sum' => 0 - $dec
				));
			}
		}
		$this->delCache($number);
	}

	/**
	 * 根据用户id获取用户统计信息
	 * $number 用户ID
	 * $type 获取统计的类型，可选，不选表示获取用户所有统计
	 */
	function getCount($number,$type='') {
		$count = array ();
		if (!$count = S('user_count_' . $number)) {
			$list = $this->where(array (
				'number' => $number
			))->select();
			foreach ($list as $v) {
					$count[$v['type']] = $v['sum'];
			}
			S('user_count_' . $number, $count);
		}
		if($type!=''){
			if(!isset($count[$type])){
				return 0;
			}
			return $count[$type];
		}
		return $count;
	}
	private function delCache($number) {
		S('user_count_' . $number, null);
	}
}
?>