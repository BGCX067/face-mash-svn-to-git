<?php

class EventAction extends AdminAction{

    function index() {
    	$res=D('Event')->select();
    	$this->assign('events',$res);
    	$this->display('Admin:event');
    }
    function add(){
    	D('Event')->doAdd($_POST);
    	$this->redirect('Event/index');
    }

    function del(){
    	D('Event')->del($_GET['eid']);
    	$this->redirect('Event/index');
    }
    function update(){
    	D('Event')->update($_POST);
    	$this->redirect('Event/index');
    }
}
?>