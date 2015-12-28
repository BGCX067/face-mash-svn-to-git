<?php

class AttachModel extends Model{

    function saveAvatar($map) {
    	$user=session('user');
    	$map['number']=$user['number'];
    	$this->create($map);
    	$this->add();
    }
}
?>