<?php

namespace OA\Controller;
use OA\Think;


class SendmessageApiController extends \Think\Controller
{
	public function SendMessage_api()
	{
		$zhanghao1 = m('duanxinset');
		$zhanghao = $zhanghao1->where('yy_ID=' . i('post.yyid') . '')->find();
		$contents = i('post.message_content');
		$receive_number = i('post.shouji');
		$username = $zhanghao['dx_username'];
		$password = $zhanghao['dx_password'];
		$post_data = array('username' => $username, 'password' => $password, 'action' => 'send', 'split_type' => '1', 'receive_number' => $receive_number, 'message_content' => $contents);
		$url = 'http://175.6.1.175:83/api.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);

		if ($output == 1) {
			js_alert('', '发送成功');
		}
		else {
			js_alert('', '发送失败，请检查！');
		}
	}

	public function Sendverify_api($receive_number, $contents)
	{
		$contents = $contents . '[预约系统验证码]，10分钟之内登陆有效，请妥善保管且勿转发。';
		$duanxin = m('shoujiyanzheng');
		$zhanghao = $duanxin->where('ID=1')->find();
		$username = $zhanghao['dx_username'];
		$password = $zhanghao['dx_password'];
		$post_data = array('username' => $username, 'password' => $password, 'action' => 'send', 'split_type' => '1', 'receive_number' => $receive_number, 'message_content' => $contents);
		$url = 'http://175.6.1.175:83/api.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}


?>
