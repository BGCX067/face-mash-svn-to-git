<?php
class SpaceAction extends BaseAction {
	function SpaceAction() {
		parent :: __construct();
		$member = D('User')->getUser($_GET['number']);
		$this->assign('member', $member);
	}
	function index() {
		$GET=$_REQUEST;
		if (!isset ($_GET['number'])) {
			redirect(U('Index/index'));
		}
		$ranks = D('Score')->getMyRank($_GET['number']);
		$this->assign('ranks', $ranks);
		$this->display();
	}
}
?>