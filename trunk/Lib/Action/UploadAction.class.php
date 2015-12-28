<?php
class UploadAction extends BaseAction{

	function upload() {
		if(!session('user')){
			exit;
		}
?>
    	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<?

		@ header("Expires: 0");
		@ header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
		@ header("Pragma: no-cache");
		define('SD_ROOT', APP_PATH);
		$user=session('user');
		$_SESSION['number']=$user['number'];
		$pic_id = $user['number'].'-'.time(); //使用时间来模拟图片的ID.
		$pic_path = SD_ROOT . './Public/upload/avatar/avatar_origin/' . $pic_id . '.jpg';
		//上传后图片的绝对地址
		//$pic_abs_path = 'http://sns.com/avatar_test/avatar_origin/'.$pic_id.'.jpg';
		$pic_abs_path = './Public/upload/avatar/avatar_origin/' . $pic_id . '.jpg';
		//保存上传图片.
		if (empty ($_FILES['Filedata'])) {
			echo '<script type="text/javascript">alert("对不起, 图片未上传成功, 请再试一下");</script>';
			exit ();
		}

		$file = @ $_FILES['Filedata']['tmp_name'];

		file_exists($pic_path) && @ unlink($pic_path);
		if (@ copy($_FILES['Filedata']['tmp_name'], $pic_path) || @ move_uploaded_file($_FILES['Filedata']['tmp_name'], $pic_path)) {
			@ unlink($_FILES['Filedata']['tmp_name']);
			/*list($width, $height, $type, $attr) = getimagesize($pic_path);
			if($width < 10 || $height < 10 || $width > 3000 || $height > 3000 || $type == 4) {
				@unlink($pic_path);
				return -2;
			}*/
		} else {
			@ unlink($_FILES['Filedata']['tmp_name']);
			echo '<script type="text/javascript">alert("对不起, 上传失败' . $pic_path . '");</script>';
		}

		//写新上传照片的ID.
		echo '<script type="text/javascript">window.parent.hideLoading();window.parent.buildAvatarEditor("' . $pic_id . '","' . $pic_abs_path . '","photo");</script>';
?>
</body>
</html>
<?php


	}
	function saveAvatar() {
		if(!session('user')){
			exit;
		}
		define('SD_ROOT', APP_PATH);
		@ header("Expires: 0");
		@ header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
		@ header("Pragma: no-cache");
		//这里传过来会有两种类型，一先一后, big和small, 保存成功后返回一个json字串，客户端会再次post下一个.
		$type = isset ($_GET['type']) ? trim($_GET['type']) : 'small';
		$pic_id = trim($_GET['photoId']);
		//$orgin_pic_path = $_GET['photoServer']; //原始图片地址，备用.
		//$from = $_GET['from']; //原始图片地址，备用.
		//生成图片存放路径
		$new_avatar_path = 'Public/upload/avatar/avatar_' . $type . '/' . $pic_id . '_' . $type . '.jpg';
		//将POST过来的二进制数据直接写入图片文件.
		$len = file_put_contents(SD_ROOT . $new_avatar_path, file_get_contents("php://input"));
		//原始图片比较大，压缩一下. 效果还是很明显的, 使用80%的压缩率肉眼基本没有什么区别
		//小图片 不压缩约6K, 压缩后 2K, 大图片约 50K, 压缩后 10K
		$avtar_img = imagecreatefromjpeg(SD_ROOT . $new_avatar_path);
		imagejpeg($avtar_img, SD_ROOT . $new_avatar_path, 80);
		//nix系统下有必要时可以使用 chmod($filename,$permissions);

		//输出新保存的图片位置, 测试时注意改一下域名路径, 后面的statusText是成功提示信息.
		//status 为1 是成功上传，否则为失败.
		D('Attach')->saveAvatar(array (
			'size' => $len,
			'path' => $new_avatar_path,
			'type' => $type,
		));
		$d = new pic_data();
		//$d->data->urls[0] = 'http://sns.com/avatar_test/'.$new_avatar_path;
		$d->data->urls[0] = $new_avatar_path;
		$d->status = 1;
		$d->statusText = '上传成功!';
		$msg = json_encode($d);
		echo $msg;
	}
}
function log_result($word) {
	@ $fp = fopen("log.txt", "a");
	@ flock($fp, LOCK_EX);
	@ fwrite($fp, $word . "：执行日期：" . strftime("%Y%m%d%H%I%S", time()) . "\r\n");
	@ flock($fp, LOCK_UN);
	@ fclose($fp);
}
class pic_data {
	public $data;
	public $status;
	public $statusText;
	public function __construct() {
		$this->data->urls = array ();
	}
}