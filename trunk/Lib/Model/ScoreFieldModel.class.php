<?php

class ScoreFieldModel extends Model {

    function getScoreFields() {
    	if(!$fields=S('fields')){
    		$fields=$this->select();
    		S('fields',$fields);
    	}
    	return $fields;
    }

    function getScoreFieldsByNumber($number) {
    	$user=D('User')->where(array('number'=>$number))->find();
    	$re=array();
    	$fs=$this->getScoreFields();
    	foreach($fs as $f){
    		if($f['sex']==$user['sex']){
    			$re[]=$f;
    		}
    	}
    	return $re;
    }

    function getSexByType($type){
    	$fs=$this->getScoreFields();
    	foreach($fs as $f){
    		if($f['name']==$type){
    			return $f['sex'];
    			break;
    		}
    	}
    	return "";
    }

    function doAdd($map){
    	$this->create($map);
    	$this->add();
    	S('fields',null);
    }
    function doUpdate($map){
    	$arr=$this->find($map['sfid']);
    	if($arr['name']!=$map['name']){
    		D('Score')->where(array('type'=>$arr['name']))->save(array('type'=>$map['name']));
    		D('Out')->where(array('type'=>$arr['name']))->save(array('type'=>$map['name']));
    	}
    	$this->create($map);
    	$this->save();
    	S('fields',null);
    }
    function del($id){
    	$this->where(array('sfid'=>$id))->delete();
    	S('fields',null);
    }
}
?>