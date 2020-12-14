<?php

namespace OA\Controller;
use Think;

class ReturnvisitController extends \Component\AdminController
{
	public function returnvisit_list()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);
		$hfstarttime = strtotime($canshu['hfTimeStart']);
		$hfendtime = strtotime($canshu['hfTimeEnd']);

		if (31600000 < ($endtime - $starttime)) {
			js_alert('', '咨询日期不支持超过一年');
			exit();
		}

		if (8035200 < ($hfendtime - $hfstarttime)) {
			js_alert('', '日期不支持超过三个月');
			exit();
		}

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-d', strtotime('-365 days'));
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		if (empty($canshu['hfTimeStart'])) {
			$canshu['hfTimeStart'] = date('Y-m-d');
		}

		if (empty($canshu['hfTimeEnd'])) {
			$canshu['hfTimeEnd'] = date('Y-m-d');
		}

		$URLcanshu = '&bz_ID=' . $canshu['bz_ID'] . '&userID=' . $canshu['userID'] . '&yy_ID=' . $canshu['yy_ID'] . '&zx_timeStart=' . $canshu['zx_timeStart'] . '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '&hfTimeStart=' . $canshu['hfTimeStart'] . '&hfTimeEnd=' . $canshu['hfTimeEnd'] . '';
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$tiaojian = explode('and', $tiaojian);
		unset($tiaojian[0]);

		foreach ($tiaojian as $k => $v ) {
			$tiaojian1 .= ' and  a.' . trim($v);
		}

		if ($canshu['shifoudaozhen'] == 0) {
			$tiaojian1 .= '';
			$this->assign('huichuan0', 'selected');
			$URLcanshu .= '';
		}
		else if ($canshu['shifoudaozhen'] == 1) {
			$tiaojian1 .= ' and a.shifouyuyue=1 ';
			$this->assign('huichuan1', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=1';
		}
		else if ($canshu['shifoudaozhen'] == 2) {
			$tiaojian1 .= ' and a.shifouyuyue=0 ';
			$this->assign('huichuan2', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=2';
		}
		else if ($canshu['shifoudaozhen'] == 3) {
			$tiaojian1 .= ' and a.shifoudaozhen=0 ';
			$this->assign('huichuan3', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=3';
		}
		else {
			$tiaojian1 .= ' and a.shifouyuyue=0 and a.shifoudaozhen=1 ';
			$this->assign('huichuan4', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=4';
		}

		$sql = 'select ' . "\r\n" . '		b.hf_time,b.hf_zhuti,b.user_ID as hfuserID,b.hf_content,' . "\r\n" . '		a.zx_time,a.shifouyuyue,a.shifoudaozhen,a.huanzeName,a.bz_name,a.shouji,a.userchinaname,a.yuyueTime,a.daozhen_time ' . "\r\n" . '		from oa_managezx as a right join  oa_huanzehuifang as b on a.zx_ID = b.zx_ID  ' . "\r\n" . '		where  a.zx_time>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' ' . "\r\n" . '		and a.zx_time<= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '		and b.hf_time>=\'' . $canshu['hfTimeStart'] . ' 00:00:00\' and  b.hf_time<= \'' . $canshu['hfTimeEnd'] . ' 23:59:59\' ' . $tiaojian1 . ' order by hf_time desc';
		$sql2 = 'select count(*) as total ' . "\r\n" . '		from oa_managezx as a right join  oa_huanzehuifang as b on a.zx_ID = b.zx_ID  ' . "\r\n" . '		where  a.zx_time>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' ' . "\r\n" . '		and a.zx_time<= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '		and b.hf_time>\'' . $canshu['hfTimeStart'] . ' 00:00:00\' and  b.hf_time<= \'' . $canshu['hfTimeEnd'] . ' 23:59:59\' ' . $tiaojian1 . ' order by hf_time desc';
		$page = new \Component\PageLF();
		$info = $page->fenye_lhcx1($sql, '', '');
		$fenye5 = $page->fenye_lhcx2($sql2, '', '', $URLcanshu);
		$useradmin = m('useradmin');
		$userlist = $useradmin->getField('user_ID,userchinaname');

		foreach ($info as $k => $v ) {
			$info[$k]['hfuserID'] = $userlist[$v['hfuserID']];
		}

		$this->assign('info', $info);
		$this->assign('fenye5', $fenye5);
		$this->assign('hfTimeStart', $canshu['hfTimeStart']);
		$this->assign('hfTimeEnd', $canshu['hfTimeEnd']);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->display();
	}

	public function huifangjihua_list()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);
		$hfstarttime = strtotime($canshu['hfTimeStart']);
		$hfendtime = strtotime($canshu['hfTimeEnd']);

		if (31600000 < ($endtime - $starttime)) {
			js_alert('', '咨询日期不支持超过一年');
			exit();
		}

		if (8035200 < ($hfendtime - $hfstarttime)) {
			js_alert('', '日期不支持超过三个月');
			exit();
		}

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-d', strtotime('-365 days'));
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		if (empty($canshu['hfTimeStart'])) {
			$canshu['hfTimeStart'] = date('Y-m-d');
		}

		if (empty($canshu['hfTimeEnd'])) {
			$canshu['hfTimeEnd'] = date('Y-m-d');
		}

		$URLcanshu = '&bz_ID=' . $canshu['bz_ID'] . '&userID=' . $canshu['userID'] . '&yy_ID=' . $canshu['yy_ID'] . '&zx_timeStart=' . $canshu['zx_timeStart'] . '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '&hfTimeStart=' . $canshu['hfTimeStart'] . '&hfTimeEnd=' . $canshu['hfTimeEnd'] . '';
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$tiaojian = explode('and', $tiaojian);
		unset($tiaojian[0]);

		foreach ($tiaojian as $k => $v ) {
			$tiaojian1 .= ' and  a.' . trim($v);
		}

		if ($canshu['shifoudaozhen'] == 0) {
			$tiaojian1 .= '';
			$this->assign('huichuan0', 'selected');
			$URLcanshu .= '';
		}
		else if ($canshu['shifoudaozhen'] == 1) {
			$tiaojian1 .= ' and a.shifouyuyue=1 ';
			$this->assign('huichuan1', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=1';
		}
		else if ($canshu['shifoudaozhen'] == 2) {
			$tiaojian1 .= ' and a.shifouyuyue=0 ';
			$this->assign('huichuan2', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=2';
		}
		else if ($canshu['shifoudaozhen'] == 3) {
			$tiaojian1 .= ' and a.shifoudaozhen=0 ';
			$this->assign('huichuan3', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=3';
		}
		else {
			$tiaojian1 .= ' and a.shifouyuyue=0 and a.shifoudaozhen=1 ';
			$this->assign('huichuan4', 'selected');
			$this->assign('yanse4', 'red');
			$URLcanshu .= ' &shifouyuyue=4';
		}

		$sql = 'select ' . "\r\n" . '		b.hfjh_time,b.hfjh_zhuti,b.hfjh_content,b.hfzuser_ID,b.adduser_ID,b.zxhf_ID, ' . "\r\n" . '		a.zx_time,a.shifouyuyue,a.shifoudaozhen,a.huanzeName,a.bz_name,a.shouji,a.userchinaname,a.yuyueTime,a.daozhen_time ' . "\r\n" . '		from oa_managezx as a right join  oa_huifangjihua as b on a.zx_ID = b.zx_ID  ' . "\r\n" . '		where  a.zx_time>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' ' . "\r\n" . '		and a.zx_time<= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '		and b.hfjh_time>=\'' . $canshu['hfTimeStart'] . ' 00:00:00\' and  b.hfjh_time<= \'' . $canshu['hfTimeEnd'] . ' 23:59:59\' ' . $tiaojian1 . ' order by hfjh_time desc';
		$sql2 = 'select count(*) as total ' . "\r\n" . '		from oa_managezx as a right join  oa_huifangjihua as b on a.zx_ID = b.zx_ID  ' . "\r\n" . '		where  a.zx_time>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' ' . "\r\n" . '		and a.zx_time<= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '		and b.hfjh_time>=\'' . $canshu['hfTimeStart'] . ' 00:00:00\' and  b.hfjh_time<= \'' . $canshu['hfTimeEnd'] . ' 23:59:59\' ' . $tiaojian1 . ' order by hfjh_time desc';
		$page = new \Component\PageLF();
		$info = $page->fenye_lhcx1($sql, '', '');
		$fenye5 = $page->fenye_lhcx2($sql2, '', '', $URLcanshu);
		$useradmin = m('useradmin');
		$userlist = $useradmin->getField('user_ID,userchinaname');

		foreach ($info as $k => $v ) {
			$info[$k]['hfzuser_ID'] = $userlist[$v['hfzuser_ID']];
			$info[$k]['adduser_ID'] = $userlist[$v['adduser_ID']];
		}

		$this->assign('info', $info);
		$this->assign('fenye5', $fenye5);
		$this->assign('hfTimeStart', $canshu['hfTimeStart']);
		$this->assign('hfTimeEnd', $canshu['hfTimeEnd']);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->display();
	}
}


?>
