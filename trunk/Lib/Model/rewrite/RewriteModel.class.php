<?php
class RewriteModel extends Model {
  function getRules() {
		return $this->select();
  }

  function getValues() {
		if (!$result = S('getRulesKey2Value')) {
			$rules = $this->order('rwid ASC')->select();
			foreach ($rules as $rule) {
				$result[$rule['name']] = $rule['value'];
			}
			S('getRulesKey2Value', $result);
		}
		if($result){
			return $result;
		}
		return null;
	}
	function getkeys() {
		if (!$result = S('getRulesValue2key')) {
			$rules = $this->order('rwid DESC')->select();
			foreach ($rules as $rule) {
				$result[$rule['value']] = $rule['name'];
			}
			S('getRulesValue2key', $result);
		}
		if(isset($result)){
			return $result;
		}
		return null;
	}

	function update($map){
		for($i=0;$i<count($map['name']);$i++){
           $this->save(array('rwid'=>$map['rwid'][$i],'name'=>$map['name'][$i],'value'=>$map['value'][$i]));
		}
		S('getRulesValue2key', null);
		S('getRulesKey2Value', null);
	}

	function doAdd($map){
		$this->create($map);
		$this->add();
		S('getRulesValue2key', null);
		S('getRulesKey2Value', null);
	}

	function del($id){
		$this->where(array('rwid'=>$id))->delete();
	    S('getRulesValue2key', null);
		S('getRulesKey2Value', null);
	}
}
?>