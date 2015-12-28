<?php

class AdminAction extends BaseAction{

    function __construct(){
    	parent::__construct();
    	if(session('admin')==null){
    		redirect(U('Index/index'));
    	}
    }
    function index() {
    	$fs=D('ScoreField')->getScoreFields();
    	$this->assign('fs',$fs);
    	$this->display();
    }

    function add(){
    	D('ScoreField')->doAdd($_POST);
    	redirect(U('Admin/index'));
    }

    function delete(){
    	D('ScoreField')->del($_GET['id']);
    	redirect(U('Admin/index'));
    }

    function update(){
     for($i=0;$i<count($_POST['name']);$i++){
     	$map['name']=$_POST['name'][$i];
     	$map['desc']=$_POST['desc'][$i];
     	$map['sfid']=$_POST['sfid'][$i];
     	$map['sex']=$_POST['sex'][$i];
     	D('ScoreField')->doUpdate($map);
     }
     redirect(U('Admin/index'));
    }
    function pushOut(){
    	$numbers=array_keys(session('avatar'));
    	if(isset($_GET['type'])){
          D('Out')->setOut($numbers,$_GET['type']);
          D('Score')->where(array('number'=>array('in',$numbers),'type'=>$_GET['type']))->delete();
    	}
    	clear_cache();
    	redirect($_SERVER["HTTP_REFERER"]);
    }
}
?>