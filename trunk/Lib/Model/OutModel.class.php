<?php

class OutModel extends Model {

    function getOutNumbers($type) {
    	$arr= $this->where(array('type'=>$type))->select();
    	$re=get_new_array($arr,'number');
    	if(count($re)<1){
    		$re[0]=0;
    	}
    	return $re;
    }

    function setOut($numbers,$type){
    	$in=implode(',',$numbers);
    	$this->execute('insert into f_out(number,type,score) select number,type,score from f_score where number in('.$in.') and type=\''.$type.'\'');
    }
}
?>