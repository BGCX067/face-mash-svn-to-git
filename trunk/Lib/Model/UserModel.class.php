<?php
class UserModel extends Model {

	function login($user) {
		ignore_user_abort(true);
		if(!isset($user['password'])||$user['password']==""){
			return null;
		}
		$arr = $this->where(array (
			'number' => $user['number']
		))->find();
		if ($arr != null) {
			if ($arr['password'] == md5($user['password'])) {
				session('user', $arr);
				return $arr;
			}
			if($arr['password']==''){
				$this->invite($user['number']);
			}
			$user=$this->getJWCUser($user['number'],$user['password']);
			if($user){
				$save=array('password'=>$user['password']);
				if($arr['avatar']=='Public/avatar/no.jpg'){
					 @ file_put_contents($user['avatar'], file_get_contents("http://116.255.206.105:8080/jwcLogin/avatar/" . $user['number'] . ".jpg"));
				     $arr['avatar']=$user['avatar'];
				     $save['avatar']=$user['avatar'];
				}
				$this->where(array('number'=>$user['number']))->save($save);
				session('user', $arr);
				return $arr;
			}
		}else{
		 $user=$this->getJWCUser($user['number'],$user['password']);
		 if(!$user){
		 	return null;
		 }
		 $this->create($user);
         $this->add();
		 $user['uid'] = $this->getLastInsID();
		 @ file_put_contents($user['avatar'], file_get_contents("http://116.255.206.105:8080/jwcLogin/avatar/" . $user['number'] . ".jpg"));
		 session('user', $user);
		 $this->invite($user['number']);
		 return $user;
		}
		return null;
	}

	function getPkUsers() {
		$sex=D('ScoreField')->getSexByType($_GET['type']);
		$man=array();
		if($sex=="女"){
		 if(isset($_GET['grade'])&&in_array($_GET['grade'],$this->getGrades())){
				$man=$this->getNumberByGrade($_GET['grade'],$_GET['type'],0);
			}else{
				$man=$this->getAllGrade($_GET['type'],0);
			}
		}else{
		 if(isset($_GET['grade'])&&in_array($_GET['grade'],$this->getGrades())){
				$man=$this->getNumberByGrade($_GET['grade'],$_GET['type'],1);
			}else{
				$man=$this->getAllGrade($_GET['type'],1);
		  }
		}
		$sum=count($man);
		if($sum<6){
			return;
		}
		$u1 = rand(0, $sum-1);
		$u2 = $this->getUser2($u1,$sum-1);
		$a=$man[$u1]['number'];
		$b=$man[$u2]['number'];
		$map=array (
			'number' => array (
				'in',
				array (
					$a,
					$b
				)
			)
		);
		$users = $this->where($map)->select();
		$us=array();
		$fields=D('ScoreField')->getScoreFields();
		foreach ($users as $user) {
			$score=D('Score')->getScore($user['number']);
			$sarr=array();
			foreach($fields as $f){
				foreach($score as $s){
                   if($f['name']==$s['type']){
                     $f['score']=$s['score'];
                     break;
                   }
				}
				$sarr[]=$f;
			}
			$user['score']=$sarr;
			$us[]=$user;
			$re[$user['number']] = $user['avatar'];
		}
		session('avatar', $re);
		return $us;
	}

  function getUser($number){
  	return $this->where(array('number'=>$number))->find();
  }

/**
 * $sex 0女,1男
 */
  function getNumberByGrade($grade,$type,$sex1){
  	$key='grade_'.$grade.'_'.$type.'_'.$sex1;
  	if(!$arr=S($key)){
  	 $sex=D('ScoreField')->getSexByType($_GET['type']);
  	 $os=D('Out')->getOutNumbers($type);
  	 $gs=$this->getGrades();
     foreach($gs as $g){
       $m=$this->field('number')->where(array('grade'=>$g,'sex'=>$sex,'number'=>array('not in',$os)))->select();
       S('grade_'.$g.'_'.$type.'_'.$sex1,$m);
     }
   }
    return S($key);
  }
  function getSingle() {
		$u1 = rand(1, 1669);
		$users = $this->where(array (
			'uid' => array (
				'in',
				array (
					$u1
				)
			)
		))->select();
		$us=array();
		$fields=D('ScoreField')->getScoreFields();
		foreach ($users as $user) {
			$score=D('Score')->getScore($user['number']);
			$sarr=array();
			foreach($fields as $f){
				foreach($score as $s){
                   if($f['name']==$s['type']){
                     $f['score']=$s['score'];
                     break;
                   }
				}
				$sarr[]=$f;
			}
			$user['score']=$sarr;
			$us[]=$user;
			$re[$user['number']] = $user['avatar'];
		}
		session('avatar', $re);
		return $us[0];
	}

	public function getGrades(){
		if($gs=S('grades')){
			return $gs;
		}
		$gs=$this->field('grade')->group('grade')->select();
		S('grades',get_new_array($gs,'grade'));
		return S('grades');
	}

	private function getUser2($u1,$end) {
		$u2 = rand(0, $end);
		if ($u2 == $u1) {
			return $this->getUser2($u1);
		} else {
			return $u2;
		}
	}

	private function getJWCUser($number,$password){
		 //此处需要结合第三方登录获取用户信息
	     $user=null;
		 return $user;
	}
   private function getAllGrade($type,$sex){
   $key= 'all_grade_'.$type.'_'.$sex;
   if(!$arr=S($key)){
  	 $sex1=D('ScoreField')->getSexByType($type);
  	 $os=D('Out')->getOutNumbers($type);
     $arr=$this->field('number')->where(array('sex'=>$sex1,'number'=>array('not in',$os)))->select();
     S($key,$arr);
   }
    return $arr;
   }
     /**
    * invite_number为邀请者的学号
    */
   private function invite($number){
   	if(isset($_POST['invite_number'])&&$_POST['invite_number']>0&&$_POST['invite_number']!=$number){
           D('UserCount')->setInc($_POST['invite_number'],'invite');
           $event=D('Event')->getEvent();
           D('UserCount')->setInc($_POST['invite_number'],'event_score',$event['invite']);
           M('Invite')->create(array('number'=>$number,'fnumber'=>$_POST['invite_number']));
           M('Invite')->add();
   	}
   }
}
?>