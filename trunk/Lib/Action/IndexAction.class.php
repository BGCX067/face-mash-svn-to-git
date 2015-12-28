<?php
class IndexAction extends BaseAction {

	public function index() {
//	  	if(session('user')==null){
//    		redirect(U('Index/login'));
//    	}
		session('begin',time());
		$arr=D('ScoreField')->getScoreFields();
		$_GET['type'] = $arr[0]['name'];
		$users = D('User')->getPkUsers();
		$this->assign('users', $users);
		$this->display();
	}

	public function single() {
		$user = D('User')->getSingle();
		$this->assign('user', $user);
		$this->display();
	}

	public function pk() {
		session('begin',time());
		if (isset ($_GET['type'])) {
			$type = $_GET['type'];
			$types = D('ScoreField')->getScoreFields();
			if (in_array($type, get_new_array($types, 'name'))) {
				$users = D('User')->getPkUsers();
				$this->assign('users', $users);
				$this->display('index');
			} else {
				redirect(U('Index/index'));
			}
		} else {
			redirect(U('Index/index'));
		}
	}

	function showEvent(){
		$es=D('Event')->select();
		$this->assign('es',$es);
		$this->display('event');
	}

	function score() {
		if(time()-session('begin')<1){
			exit('Score too fast');
		}
		if (D('Score')->score()) {
			redirect($_SERVER["HTTP_REFERER"]);
		}
		redirect(U('Index/index'));
	}

	function login() {
		$user = session('user');
		if ($user != null) {
			redirect(U('Home/index'));
		}
		$this->display();
	}

	function adminLogin(){
      $this->display();
	}

	function doAdminLogin() {
		if ($_POST['username'] == C('admin_name')&&$_POST['password']==C('admin_pass')) {
			session('admin','admin');
			redirect(U('Admin/index'));
		}
		redirect(U('Index/index'));
	}

	function doLogin() {
		$user = D('User')->login($_POST);
		if ($user != null) {
			redirect(U('Home/index'));
		}
		redirect(U('Index/index'));
	}

	function rank() {
		if (isset ($_GET['type'])) {
			$type = $_GET['type'];
			$types = D('ScoreField')->getScoreFields();
			if (in_array($type, get_new_array($types, 'name'))) {
				$order="score DESC";
				if(isset($_GET['order'])&&$_GET['order']!='desc'){
					$order="score ASC";
				}
				 $sum=12;
				if(session('admin')!=null&&isset($_GET['sum'])&&$_GET['sum']>0){
					 $sum=$_GET['sum'];
				}
				$users = D('Score')->getRank($type, $sum, $order);
				$this->assign('users', $users);
				$this->display();
			}
		} else {
			redirect(U('Index/index'));
		}
	}

	function getRankImg() {
		$path = "Public/avatar/no.jpg";
		if ($as = session('avatar') && isset ($_GET['number'])) {
			$s = session('avatar');
			if (isset ($s[$_GET['number']])) {
				$path = $s[$_GET['number']];
			}
		}
		$this->showAvatar($path);
	}
	private function showAvatar($path) {
		header("Content-type:image/jpeg");
		echo @ file_get_contents($path);
	}

}