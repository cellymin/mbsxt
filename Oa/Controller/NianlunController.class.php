<?php

namespace OA\Controller;


class NianlunController extends \Component\AdminController
{
	public function nianlunbj()
	{
		function oa_zxfs($zxfs)
		{
			switch ($zxfs) {
			case '个人QQ':
				$zxfs_ID = '9';
				break;

			case '企业QQ':
				$zxfs_ID = '10';
				break;

			case '商务通':
				$zxfs_ID = '7';
				break;

			case '网站留言':
				$zxfs_ID = '24';
				break;

			case '400':
				$zxfs_ID = '29';
				break;

			case '114':
				$zxfs_ID = '30';
				break;

			case '短信挂号':
				$zxfs_ID = '24';
				break;

			case '59101111':
				$zxfs_ID = '31';
				break;

			case '59101155':
				$zxfs_ID = '31';
				break;

			case '老数据':
				$zxfs_ID = '32';
				break;

			case '56955161':
				$zxfs_ID = '31';
				break;

			case '百度商桥':
				$zxfs_ID = '8';
				break;

			default:
				$zxfs_ID = '32';
				break;
			}

			return $zxfs_ID;
		}
		function oa_bingzhong($bingzhong)
		{
			switch ($bingzhong) {
			case '颈椎病':
				$bz_ID = '396';
				break;

			case '腰椎病':
				$bz_ID = '397';
				break;

			case '拇外翻':
				$bz_ID = '398';
				break;

			case '马蹄足':
				$bz_ID = '399';
				break;

			case '骨质增生':
				$bz_ID = '408';
				break;

			case '肩周炎':
				$bz_ID = '400';
				break;

			case '滑膜炎':
				$bz_ID = '401';
				break;

			case '坐骨神经痛':
				$bz_ID = '410';
				break;

			case '关节炎':
				$bz_ID = '402';
				break;

			case '其他':
				$bz_ID = '413';
				break;

			case '强直性脊柱炎':
				$bz_ID = '411';
				break;

			case '腱鞘炎，腱鞘囊肿':
				$bz_ID = '407';
				break;

			case '外伤骨折':
				$bz_ID = '405';
				break;

			case '痛风':
				$bz_ID = '404';
				break;

			case '？侧股骨头坏死':
				$bz_ID = '412';
				break;

			case '？侧膝关节疾病':
				$bz_ID = '402';
				break;

			case '（？部位）骨折术后':
				$bz_ID = '405';
				break;

			case '（？部位）畸形矫正':
				$bz_ID = '409';
				break;

			case '风湿病':
				$bz_ID = '403';
				break;

			case '类风湿':
				$bz_ID = '406';
				break;

			default:
				$bz_ID = '413';
				break;
			}

			return $bz_ID;
		}
		function oa_qudao($qudao)
		{
			switch ($qudao) {
			case '百度':
				$xx_ID = '33';
				break;

			case '谷歌':
				$xx_ID = '39';
				break;

			case '搜狗':
				$xx_ID = '39';
				break;

			case '搜搜':
				$xx_ID = '39';
				break;

			case '其他':
				$xx_ID = '39';
				break;

			case '360':
				$xx_ID = '39';
				break;

			case '百度竞价':
				$xx_ID = '2';
				break;

			case '搜搜竞价':
				$xx_ID = '39';
				break;

			case '搜狗竞价':
				$xx_ID = '4';
				break;

			case '120ask':
				$xx_ID = '39';
				break;

			default:
				$xx_ID = '39';
				break;
			}

			return $xx_ID;
		}
		function oa_useradmin($username)
		{
			switch ($username) {
			case '蒋凤军':
				$user_ID = '60';
				break;

			case '司彩彩':
				$user_ID = '61';
				break;

			case '韩贵军':
				$user_ID = '62';
				break;

			case '贾苗苗':
				$user_ID = '63';
				break;

			default:
				$user_ID = '97';
				break;
			}

			return $user_ID;
		}
		$starttime = explode(' ', microtime());
		$yyID = '187';
		$yyname = '北京年轮骨科';
		$huanze = m('huanze');
		$huanzeyuyue = m('huanzeyuyue');
		$huanzehuifang = m('huanzehuifang');
		$huanzejingjia = m('huanzejingjia');
		$huanzeInfo = m('huanzeInfo');
		$huanzeCaozuo = m('huanzeCaozuo');
		$huanzehuifang = m('huanzehuifang');
		$managezx = m('managezx');
		$data = m('customer');
		$nianlun = $data->select();

		for ($i = 0; $i <= count($nianlun) - 1; $i++) {
			$zxfs_ID = oa_zxfs($nianlun[$i]['Dialogue']);
			$bz_ID = oa_bingzhong($nianlun[$i]['Diseases']);
			$xx_ID = oa_qudao($nianlun[$i]['Channel']);
			$user_ID = oa_useradmin($nianlun[$i]['Consultant']);

			if ($nianlun[$i]['ReservationTime'] != '') {
				$shifouyuyue = 0;
			}
			else {
				$shifouyuyue = 1;
			}

			if ($nianlun[$i]['Visit'] == '0') {
				$shifoudaozhen = 1;
			}
			else {
				$shifoudaozhen = 0;
			}

			if ($nianlun[$i]['TelFeedback'] == '1') {
				$huifangcishu = 1;
			}
			else {
				$huifangcishu = 0;
			}

			$huanzeArr = array('yy_ID' => $yyID, 'zxfs_ID' => $zxfs_ID, 'userID' => $user_ID, 'bz_ID' => $bz_ID, 'xx_ID' => $xx_ID, 'zx_time' => $nianlun[$i]['RecordTime'], 'shifouyuyue' => $shifouyuyue, 'shifoudaozhen' => $shifoudaozhen, 'daozhen_time' => $nianlun[$i]['VisitTime'], 'yuyueBZ' => '', 'yuyueTime' => $nianlun[$i]['ReservationTime'], 'huifangcishu' => $huifangcishu, 'shifouzhuyuan' => '');
			echo '<pre>';
			print_r($huanzeArr);
			$huanzeyuyueArr = array('zx_ID' => $zx_ID, 'yuyueyishengID' => '', 'yuyueBeizhu' => '', 'huanzeName' => $nianlun[$i]['FullName'], 'shouji' => $nianlun[$i]['Phone'], 'yuyuehao' => '', 'ruyuantime' => '', 'chuyuantime' => '', 'xiaofei' => '', 'jiuzhenyishengID' => '');
			$huanzejingjiaArr = array('zx_ID' => $zx_ID, 'fangwenrukou' => '', 'guanjianci' => $nianlun[$i]['keywords'], 'faqizixun' => $nianlun[$i]['Url'], 'laiyuanwangzhan' => '');
			$huanzeInfoArr = array('zx_ID' => $zx_ID, 'QQhaoma' => $nianlun[$i]['QQ']);
			$huanzehuifangArr = array('zx_ID' => $zx_ID, 'hf_time' => $nianlun[$i]['RecordTime'], 'hf_content' => $nianlun[$i]['Feedback'], 'hf_zhuti' => '未知', 'user_ID' => '11', 'hf_addtime' => $nianlun[$i]['RecordTime'], 'hf_fangshi' => '电话');
			$huanzeCaozuoArr = array('zx_ID' => $zx_ID, 'add_time' => $nianlun[$i]['RecordTime'], 'addUser_ID' => '11');
			$managezxArr = array('zx_ID' => $zx_ID, 'userID' => $user_ID, 'userchinaname' => $nianlun[$i]['FullName'], 'yy_ID' => $yyID, 'yy_name' => $yyname, 'zxfs_ID' => $zxfs_ID, 'zxfs_name' => $nianlun[$i]['Channel'], 'bz_ID' => $bz_ID, 'bz_name' => $nianlun[$i]['Diseases'], 'xx_ID' => $xx_ID, 'xx_name' => $nianlun[$i]['Channel'], 'zx_time' => $nianlun[$i]['RecordTime'], 'yuyueTime' => $nianlun[$i]['ReservationTime'], 'shifouyuyue' => $shifouyuyue, 'huanzeName' => $nianlun[$i]['FullName'], 'shouji' => $nianlun[$i]['Phone'], 'yuyueyishengID' => '', 'guanjianci' => $nianlun[$i]['keywords'], 'laiyuanwangzhan' => '', 'wangzhan_url' => '', 'fangwenrukou' => '', 'faqizixun' => $nianlun[$i]['Url'], 'ppguanjianci' => '');
		}
	}
}


?>
