<?php

class ConfigAction extends AdminAction{

    function index() {
    	$configs=D('Config')->select();
    	$this->assign('configs',$configs);
    	$this->display('Admin:config');
    }
    function update(){
    	D('Config')->update();
    	redirect(U('Config/index'));
    }
}
?>