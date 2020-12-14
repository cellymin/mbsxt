<?php

namespace OA\Controller;


class WebController extends \Component\AdminController
{
	public function liuyan()
	{
		if (!IS_POST) {
			echo '<script language=\'javascript\'>alert(\'页面不存在！\');history.go(-1)</script>';
		}
		else {
			$wangzhan = m('wangzhan');
			$wangzhan = $wangzhan->where('wangzhan_ID=' . i('post.ly-id'))->getField('wangzhan_url');
			$wangzhan = '192.168.0.88';
			$lywangzhan = parse_url($_SERVER['HTTP_REFERER']);
			$lywangzhan = 'bh' . strtolower($lywangzhan['host']);

			if (strpos($lywangzhan, $wangzhan) == false) {
				echo '<script language=\'javascript\'>alert(\'网站不匹配！\');history.go(-1)</script>';
			}
			else {
				echo '<script language=\'javascript\'>alert(\'提交成功！\');history.go(-1)</script>';
			}
		}
	}

	public function website_liuyan()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$wangzhan = m('wangzhan');
		$liuyan = m('liuyan');
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->display('');
	}
}


?>
