<?php
class HomeAction extends BaseAction {

	function HomeAction() {
		parent :: __construct();
		if (session('user') == null) {
			redirect(U('Index/index'));
		}
		$user = session('user');
		$user['score'] = D('Score')->getScore($user['number']);
		D('Score')->getMyRank($user['number']);
		$this->assign('event_score',D('UserCount')->getCount($user['number'],'event_score'));
		$this->assign("user", $user);
	}
	function index() {
		$user = session('user');
		$as = D('Attach')->where(array (
			'number' => $user['number'],
			'type' => 'big'
		))->select();
		$s = array ();
		foreach ($as as $v) {
			$s[$v['aid']] = $v['path'];
		}
		session('avatars', $s);
		$this->assign('as',$as);
		$this->display();
	}
	function quit() {
		session('user', null);
		redirect(U('Index/index'));
	}

	function getAvatar() {
		$s = session('avatars');
		header("Content-type:image/jpeg");
		if (isset ($_GET['aid']) && isset ($s[$_GET['aid']])) {
			echo @ file_get_contents($s[$_GET['aid']]);
		} else {
			echo @ file_get_contents("Public/avatar/no.jpg");
		}
	}

	function change(){
		$s = session('avatars');
		$user = session('user');
	    if (isset ($_GET['aid']) && isset ($s[$_GET['aid']])) {
			$c=$s[$_GET['aid']];
			$att=D('Attach')->find($_GET['aid']);
            if($att['check']==0){
            	$this->error('对不起，该照片还未通过审核，请稍等',U('Home/index'));
            	exit;
            }
			$old=$user['avatar'];
			D('Attach')->save(array('aid'=>$_GET['aid'],'path'=>$old));
			$user['avatar']=$c;
			D('User')->save($user);
			session('user',$user);
		}
		redirect(U('Home/index'));
	}

	function avatar() {
		$user = session('user');
		if ($user != null) {
			header("Content-type:image/jpeg");
			echo @ file_get_contents($user['avatar']);
		} else {
			echo @ file_get_contents("Public/avatar/no.jpg");
		}
	}

}

function jsonString($str) {
	return preg_replace("/([\\\\\/'])/", '\\\$1', $str);
}
function formatBytes($bytes) {
	if ($bytes >= 1073741824) {
		$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
	}
	elseif ($bytes >= 1048576) {
		$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
	}
	elseif ($bytes >= 1024) {
		$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
	} else {
		$bytes = $bytes . 'Bytes';
	}
	return $bytes;

}