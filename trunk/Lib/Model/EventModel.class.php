<?php

class EventModel extends Model{

    function update() {
     for($i=0;$i<count($_POST['ac']);$i++){
     	$map['ac']=$_POST['ac'][$i];
     	$map['eid']=$_POST['eid'][$i];
     	$map['score']=$_POST['score'][$i];
     	$this->create($map);
     	$this->save();
     }
     S('event',null);
    }
    function getEvent(){
    	if(!$event=S('event')){
    		$arr=$this->select();
    		foreach($arr as $v){
    			$event[$v['ac']]=$v['score'];
    		}
    		S('event',$event);
    	}
    	return $event;
    }

	function doAdd($map){
		$this->create($map);
		$this->add();
		S('event',null);
	}

	function del($id){
		$this->where(array('eid'=>$id))->delete();
	    S('event',null);
	}
}
?>