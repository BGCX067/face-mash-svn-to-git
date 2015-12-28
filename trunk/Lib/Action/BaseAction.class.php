<?php
class BaseAction extends Action {

	function BaseAction() {
		parent :: __construct();
		define('DOMAIN', $_SERVER['HTTP_HOST']);
		$port = $_SERVER["SERVER_PORT"];
		$port = $port == 80 ? '' : ':' . $port;
		define('SITE_URL', 'http://' . DOMAIN . $port . __ROOT__ . '/');
		$types = D('ScoreField')->getScoreFields();
		$this->assign('types', $types);
		$user = session('user');
		$this->assign("user", $user);
		$this->assign("config", D('Config')->getConfig());
		$this->event();
	}

	private function event() {
		$user = session('user');
		$event = D('Event')->getEvent();
		$ac = MODULE_NAME . '_' . ACTION_NAME;
		if (isset ($event[$ac]) && $user != null && $ac != 'Index_score') {
			if ($event[$ac] < 0) {
				$score = D('UserCount')->getCount($user['number'], 'event_score');
				if ($score + $event[$ac] < 0) {
					if ($ac == 'Upload_upload') {
						echo '<script type="text/javascript">alert("对不起,上传头像需要' . (- $event[$ac]) . '经验值，您的经验值不够");</script>';
						exit;
					}
					$this->error('由于您的经验值不够，不能进行该操作', $_SERVER["HTTP_REFERER"]);
					exit;
				}
			}
			D('UserCount')->setInc($user['number'], 'event_score', $event[$ac]);
			D('ScoreLog')->create(array (
				'number' => $user['number'],
				'desc' => $ac,
				'score' => $event[$ac],
			'ctime' => time()));
			D('ScoreLog')->add();
		}
	}
}
?>