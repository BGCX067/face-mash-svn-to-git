<?php
class ScoreModel extends Model {

	function getScore($number, $type = "") {
		$map['number'] = $number;
		if ($type == "") {
			$arr = $this->where($map)->select();
			$types = D('ScoreField')->getScoreFieldsByNumber($number);
			if (count($arr) < count($types)) {
				foreach ($types as $type1) {
					$this->doAdd($number, $type1['name']);
				}
			}
			$sarr = array ();
			foreach ($types as $f) {
				foreach ($arr as $s) {
					if ($f['name'] == $s['type']) {
						$f['score'] = $s['score'];
						break;
					}
				}
				$sarr[] = $f;
			}
			return $sarr;
		} else {
			$map['type'] = $type;
		}
		$score = $this->where($map)->find();
		if (!$score) {
			$this->doAdd($number, $type);
			$score = $this->where($map)->find();
			;
		}
		return $score;
	}

	function doAdd($number, $type) {
		$mem = D('User')->where(array (
			'number' => $number
		))->find();
		$typeSex = D('ScoreField')->getSexByType($type);
		if ($mem['sex'] != $typeSex) {
			return;
		}
		$arr = $this->where(array (
			'number' => $number,
			'type' => $type
		))->find();
		if (!$arr) {
			$this->create(array (
				'number' => $number,
				'type' => $type,
				'score' => 0,
				'grade' => $mem['grade']));
			$this->add();
		}
	}

	function score() {
		if (isset ($_GET['type'])) {
			$type = $_GET['type'];
			$types = D('ScoreField')->getScoreFields();
			if (in_array($type, get_new_array($types, 'name'))) {
				$s = session('avatar');
				if (isset ($s[$_GET['number']])) {
					$p = array ();
					foreach ($s as $k => $v) {
						$t['number'] = $k;
						$score = $this->getScore($k, $type);
						$t['score'] = $score['score'];
						$t['sid'] = $score['sid'];
						if ($k == $_GET['number']) {
							$t['this'] = 1;
						} else {
							$t['this'] = 0;
						}
						$p[] = $t;
						unset ($t);
					}
					if (count($p) != 2) {
						session('avatar', null);
						return false;
					}
					$re = pk($p[0]['score'], $p[1]['score'], $p[0]['this'], $p[1]['this']);
					$p[0]['score'] = $re[0];
					$p[1]['score'] = $re[1];
					$p[0]['type'] = $type;
					$p[1]['type'] = $type;
					unset ($p[0]['this']);
					unset ($p[1]['this']);
					$this->save($p[0]);
					$this->save($p[1]);
					$this->setScoreLog($_GET['number']);
					session('avatar', null);
					return true;
				}
				session('avatar', null);
			}
		}
	}

	function getMyRank($number) {
		$score = D('Score')->getScore($number);
		$re = array ();
		foreach ($score as $s) {
			$rank = $this->where(array (
				'type' => $s['name'],
				'score' => array (
					'gt',
					$s['score']
				)
			))->count();
			$s['rank'] = $rank +1;
			$re[] = $s;
		}
		return $re;
	}

	function getRank($type, $limit = 10, $order = "score DESC") {
		$map = array (
			'type' => $type
		);
		if (isset ($_GET['grade']) && in_array($_GET['grade'], D('User')->getGrades())) {
			$map['grade'] = $_GET['grade'];
		}
		$arr = $this->where($map)->order($order)->limit($limit)->select();
		$nos = get_new_array($arr, 'number');
		if (count($nos) > 0) {
			$users = D('User')->where(array (
				'number' => array (
					'in',
					$nos
				)
			))->select();
			$this->setAvatarSession($users);
			$re = array ();
			foreach ($users as $user) {
				foreach ($arr as $v) {
					if ($user['number'] == $v['number']) {
						$user['score'] = $v['score'];
						break;
					}
				}
				$re[] = $user;
			}
			usort($re, "cmp");
			return $re;
		}
		return array ();
	}
	private function setAvatarSession($users) {
		$re = array ();
		foreach ($users as $user) {
			$re[$user['number']] = $user['avatar'];
		}
		session('avatar', $re);
	}
	private function setScoreLog($winner) {
		if ($user = session('user')) {
			$event = D('Event')->getEvent();
			if(!isset($event['Index_score'])){
				return;
			}
			D('UserCount')->setInc($user['number'], 'event_score', $event['Index_score']);
			D('ScoreLog')->create(array (
				'number' => $user['number'],
				'desc' => 'Index_score',
				'score' => $event['Index_score'],
				'ctime' => time(),
				'fnumber'=>$winner
			));
			D('ScoreLog')->add();
		}
	}
}

function cmp($a, $b) {
	if ($a['score'] == $b['score']) {
		return 0;
	}
	return ($a['score'] > $b['score']) ? -1 : 1;
}