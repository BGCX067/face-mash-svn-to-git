<?php

class AvatarAction extends AdminAction{

    function index() {
    	$arr=D('Attach')->field('a.*,u.avatar as avatar')->join('a left join f_user u on a.number=u.number')->where(array('a.check'=>0,'a.type'=>'big'))->select();
        $this->assign('as',$arr);
        $this->display('Admin:avatar');
    }

    function del(){
    	D('Attach')->where(array('aid'=>$_GET['aid']))->delete();
    	redirect(U('Avatar/index'));
    }

    function yes(){
    	D('Attach')->where(array('aid'=>$_GET['aid']))->save(array('check'=>1));
    	redirect(U('Avatar/index'));
    }
}
?>