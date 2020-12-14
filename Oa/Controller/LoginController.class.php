<?php

namespace OA\Controller;
use OA\Think;


class LoginController extends \Think\Controller
{
	public function welcome()
	{
		$this->display('index');
	}

	public function verifyImg()
	{
		ob_clean();
		$config = array('imageH' => 28, 'imageW' => 100, 'fontSize' => 14, 'fontttf' => '4.ttf', 'length' => '4');
		ob_clean();
		$verify = new \Think\Verify($config);
		$verify->entry();
	}

	public function Checkuser()
	{
		//yumingpanduan();
		//shijianpanduan();
		$verify = new \Think\Verify();

		if (!$verify->check($_POST['code'])) {
			$this->assign('tishi', '<script language="javascript">parent.layer.msg("验证码错误");</script>');
			$this->assign('userpsw', $_POST['userpsw']);
		}
		else {
			$user = m('Useradmin');

			if ($user->create()) {
				$username = $user->where('username = \'' . trim($user->username) . '\' and user_del=0')->select();

				if ($username[0]['username'] != '') {
					$userpsw = $user->where('userpsw = \'' . trim(md5($user->userpsw)) . '\' and username = \'' . trim($user->username) . '\'')->getField('userpsw');

					if ($userpsw != '') {
						$lifetime = 60 * 60 * 8;
						session_start();
						session(array('name' => 'username_lf', 'expire' => $lifetime));
						session('username_lf', $username[0]['user_ID']);
						session(array('name' => 'user_role', 'expire' => $lifetime));
						session('user_role', $username[0]['role_ID']);
						$data['user_lasttime'] = date('Y-m-d H:i:s');
						$data['user_lastIP'] = get_client_ip();
						$ch = curl_init();
						$url = 'http://apis.baidu.com/showapi_open_bus/ip/ip?ip=' . $data['user_lastIP'];
						$header = array('apikey: 916e5efe634b5ccd22b5b4cc9f914097');
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_URL, $url);
						$res = curl_exec($ch);
						$str = $res;
						$arr = json_decode($str, true);
						$sheng = $arr['showapi_res_body']['region'];
						$chengshi = $arr['showapi_res_body']['city'];
						$yunyin = $arr['showapi_res_body']['isp'];
						$user->where('user_ID=' . $username[0]['user_ID'] . '')->save($data);
						$yy_ID = $user->where('user_ID=' . $username[0]['user_ID'] . '')->getField('yy_ID');
						$dengrurizhi['user_ID'] = $username[0]['user_ID'];
						$dengrurizhi['dr_time'] = date('Y-m-d H:i:s');
						$dengrurizhi['user_IP'] = $data['user_lastIP'];
						$dengrurizhi['yy_ID'] = $yy_ID;
						$dengrurizhi['diqu'] = $sheng . '-' . $chengshi . '-' . $yunyin;
						$dengrurizhi['weizhi'] = $_POST['weizhi'];
						$dengrurizhi['jingweidu'] = $_POST['jingweidu'];
						$ip_allow = $this->allowip_verify($data['user_lastIP'], $username[0]['username']);

						if ($ip_allow == false) {
							$dengrurizhi['illegal_login'] = 0;
						}

						$dengru = m('dengrurizhi');
						$dengru->add($dengrurizhi);

						if ($ip_allow == false) {
							session('username_lf', NULL);
							session('user_role', NULL);
							session('shouji_verify', NULL);
							session_destroy();
							$this->success('禁止此IP登陆，请联系管理员', '' . DQURL . 'login/welcome', 2);
							exit();
						}

						$sql = 'delete From oa_dengrurizhi where DATE(dr_time) <= DATE(DATE_SUB(NOW(),INTERVAL 90  day))';
						$dengru->query($sql);
						$shoujiyanzheng = m('shoujiyanzheng');
						$shoujikaiqi = $shoujiyanzheng->where('ID=1')->getField('shifoukaiqi');

						if ($shoujikaiqi == 0) {
							session(array('name' => 'shouji_verify', 'expire' => $lifetime));
							session('shouji_verify', '0');
							$this->success('登录成功', '' . DQURL . 'login/shouji_verify', 0);
						}
						else {
							$this->success('登录成功', '' . DQURL . 'index/main', 2);
						}

						exit();
					}
					else {
						$this->assign('tishi', '<script language="javascript">parent.layer.msg("用户名或密码错误");</script>');
					}
				}
				else {
					$this->assign('tishi', '<script language="javascript">parent.layer.msg("用户名或密码错误");</script>');
				}
			}
			else {
				$this->error($user->getError(''));
			}
		}

		$this->assign('username', $_POST['username']);
		$this->display('index');
	}

	public function allowip_verify($userip, $username)
	{
		$ipallow = m('ipallow');
		$ipallowinfo = $ipallow->where('ID=1')->find();

		if ($ipallowinfo['shifoukaiqi'] == 0) {
			$userarr = explode(';', $ipallowinfo['allow_username']);
			$userarr = array_filter($userarr);

			foreach ($userarr as $k => $v ) {
				$userarr[$k] = trim($v);
			}

			if (in_array($username, $userarr)) {
				return true;
				exit();
			}

			$IParr = explode(';', $ipallowinfo['IPdizhi']);
			$IParr = array_filter($IParr);

			foreach ($IParr as $k => $v ) {
				$IParr[$k] = trim($v);
			}

			$IParrduan = explode(';', $ipallowinfo['IPdizhiduan']);
			$IParrduan = array_filter($IParrduan);

			foreach ($IParrduan as $k => $v ) {
				$IParrduan[$k] = trim($v);
			}

			$user_iparr = explode('.', $userip);
			$userip2 = $user_iparr[0] . '.' . $user_iparr[1];
			if (!in_array($userip, $IParr) && !in_array($userip2, $IParrduan)) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}
	}

	public function shouji_verify()
	{
		$useradmin = m('useradmin');
		$userinfo = $useradmin->where('user_ID=' . session('username_lf') . '')->getField('user_ID,user_shouji,userchinaname,QQhaoma');
		$qq = $userinfo[session('username_lf')]['QQhaoma'];
		$userchinaname = $userinfo[session('username_lf')]['userchinaname'];
		$this->assign('qq', $qq);
		$qqimg = 'http://q1.qlogo.cn/g?b=qq&nk=' . $qq . '&s=640&t=' . time() . '';
		$qqdaxiao = '150px';

		if ($qq != '') {
			$header_array = get_headers($qqimg, true);
			$qqsize = $header_array['Content-Length'];

			if ($qqsize < 3710) {
				$qqimg = 'http://q1.qlogo.cn/g?b=qq&nk=' . $qq . '&s=100&t=' . time() . '';
				$qqdaxiao = '100px';
			}
		}

		$this->assign('user_img', $qqimg);
		$this->assign('qqdaxiao', $qqdaxiao);
		$this->assign('userchinaname', $userchinaname);
		$this->assign('user_shouji', $user_shouji);
		$this->display();
	}

	public function getshouji_verify()
	{
		$dqtime = date('Y-m-d H:i:s');
		$useradmin = m('useradmin');
		$userinfo = $useradmin->where('user_ID=' . session('username_lf') . '')->getField('user_ID,verify_shouji,user_shouji,verify_shouji_addtime,user_shouji');
		$user_shouji = $userinfo[session('username_lf')]['user_shouji'];

		if ($user_shouji == '') {
			echo '没有注册手机号码，请联系管理员';
			exit();
		}

		$verify_shouji = $userinfo[session('username_lf')]['verify_shouji'];
		$verify_shouji_addtime = $userinfo[session('username_lf')]['verify_shouji_addtime'];
		$user_shouji = $userinfo[session('username_lf')]['user_shouji'];
		$verify = rand('1000', '9999');
		$starttime = strtotime($verify_shouji_addtime);
		$endtime = strtotime($dqtime);
		$shijiancha = $endtime - $starttime;

		if (!empty($verify_shouji_addtime)) {
			if ($shijiancha < 30) {
				echo '短信验证码已发送,请耐心等候;30秒内不允许重复获取';
				exit();
			}
			else {
				if (($shijiancha < 600) && (30 < $shijiancha)) {
					$send_content = $verify_shouji;
					$data['verify_shouji'] = $verify_shouji;
					$data['verify_shouji_addtime'] = $verify_shouji_addtime;
				}
				else {
					$send_content = $verify;
					$data['verify_shouji'] = $send_content;
					$data['verify_shouji_addtime'] = $dqtime;
				}
			}
		}
		else {
			$send_content = $verify;
			$data['verify_shouji'] = $send_content;
			$data['verify_shouji_addtime'] = $dqtime;
		}

		$sendmessage = a('SendmessageApi');
		$send = $sendmessage->Sendverify_api($user_shouji, $send_content);

		if ($send = 1) {
			echo '短信验证码已发送至:' . $user_shouji . '';
			$useradmin->where('user_ID=' . session('username_lf') . '')->save($data);
		}
		else {
			echo '发送失败，提示请稍后再试，或联系管理员';
		}
	}

	public function shoujiverify_check()
	{
		$user_shouji_verify = i('post.shouji_verify');
		$dqtime = date('Y-m-d H:i:s');
		$useradmin = m('useradmin');
		$userinfo = $useradmin->where('user_ID=' . session('username_lf') . '')->getField('user_ID,verify_shouji,user_shouji,verify_shouji_addtime');
		$verify_shouji = $userinfo[session('username_lf')]['verify_shouji'];
		$verify_shouji_addtime = $userinfo[session('username_lf')]['verify_shouji_addtime'];
		$starttime = strtotime($verify_shouji_addtime);
		$endtime = strtotime($dqtime);
		$shijiancha = $endtime - $starttime;

		if (600 < $shijiancha) {
			$this->success('验证码错误或超过10分钟有效期,请重新获取', '' . DQURL . 'login/shouji_verify', 2);
		}
		else if ($verify_shouji == $user_shouji_verify) {
			$this->success('验证成功', '' . DQURL . 'index/main', 2);
		}
		else {
			$this->success('验证码错误', '' . DQURL . 'login/shouji_verify', 2);
		}
	}

	public function _empty()
	{
		$this->success('访问不存在', '' . DQURL . 'Login/Welcome', 2);
	}
}


?>
