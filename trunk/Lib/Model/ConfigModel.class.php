<?php

class ConfigModel extends Model {

    function update() {
     for($i=0;$i<count($_POST['name']);$i++){
     	$map['name']=$_POST['name'][$i];
     	$map['cid']=$_POST['cid'][$i];
     	$map['value']=$_POST['value'][$i];
     	$this->create($map);
     	$this->save();
     }
     S('config',null);
    }
    function getConfig(){
    	if(!$conf=S('config')){
    		$arr=$this->select();
    		foreach($arr as $v){
    			$conf[$v['name']]=$v['value'];
    		}
    		S('config',$conf);
    	}
    	return $conf;
    }
}
?>