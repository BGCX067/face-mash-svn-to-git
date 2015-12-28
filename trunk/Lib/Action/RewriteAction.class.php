<?php

class RewriteAction extends AdminAction{

    private function getRewriteModel(){
    	return D('rewrite/Rewrite');
    }

    function index() {
    	$res=$this->getRewriteModel()->getRules();
    	$this->assign('res',$res);
    	$this->display('Admin:rewrite');
    }
    function add(){
    	$this->getRewriteModel()->doAdd($_POST);
    	$this->redirect('Rewrite/index');
    }

    function del(){
    	$this->getRewriteModel()->del($_GET['rwid']);
    	$this->redirect('Rewrite/index');
    }
    function update(){
    	$this->getRewriteModel()->update($_POST);
    	$this->redirect('Rewrite/index');
    }
}
?>