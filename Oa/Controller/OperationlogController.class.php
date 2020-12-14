<?php

namespace OA\Controller;


class OperationLogController extends \Component\AdminController
{
	public function denglu()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		}

		$user = m('useradmin');
		$data = $user->where('user_ID = \'' . session('username_lf') . '\'')->find();
		$yyarr = explode(',', $data['yy_ID']);
		$yyname = m('yiyuan');
		$ssyy = array();
		$zixunpanduan = array();

		for ($i = 0; $i <= count($yyarr) - 1; $i++) {
			$ssyy1 = array('yyid' => $yyarr[$i], 'yyname' => $yyname->where('yy_ID=' . $yyarr[$i] . ' and  yy_del=0')->getField('yy_name'));

			if ($ssyy1['yyname'] != '') {
				array_push($ssyy, $ssyy1);
			}

			$zixunyuan = $user->query('select user_ID,userchinaname,role_ID from oa_useradmin where user_del=0 and user_rizhi=0  and find_in_set(' . $yyarr[$i] . ',yy_ID)');

			for ($num = 0; $num <= count($zixunyuan) - 1; $num++) {
				if (!in_array($zixunyuan[$num]['user_ID'], $zixunpanduan)) {
					$zixunyuan1[] = array('user_ID' => $zixunyuan[$num]['user_ID'], 'userchinaname' => $zixunyuan[$num]['userchinaname'], 'role_ID' => $zixunyuan[$num]['role_ID']);
				}

				array_push($zixunpanduan, $zixunyuan[$num]['user_ID']);
			}

			unset($zixunyuan);
		}

		$this->assign('countyy', count($ssyy));
		$this->assign('ssyy', $ssyy);
		$this->assign('zixunyuan1', $zixunyuan1);
		$this->assign('dqzxy', $canshu['userID']);
		$gongzuozu1 = m('role');
		$gongzuozu = $gongzuozu1->where('role_del=0')->getField('role_ID,role_name,role_sort');
		$this->assign('gongzuozu', $gongzuozu);

		if ($canshu['yy_ID'] != '') {
			$tiaojian = ' and find_in_set(' . $canshu['yy_ID'] . ',b.yy_ID)';
			$this->assign('dqyyID', $canshu['yy_ID']);
		}

		if ($canshu['gongzuozu'] != '') {
			$tiaojian .= ' and b.role_ID=' . $canshu['gongzuozu'] . ' ';
			$this->assign('dqrole_ID', $canshu['gongzuozu']);
		}

		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$sql = 'select a.dr_time,a.user_IP,a.diqu,b.userchinaname,a.user_ID,b.username  ' . "\r\n" . '		from  oa_dengrurizhi as a inner join oa_useradmin as b  on a.user_ID = b.user_ID  ' . "\r\n" . '		where a.dr_time>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' and a.dr_time<= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . $tiaojian . ' order by dr_ID desc';
		$list = $user->query($sql);
		$this->assign('list', $list);
		$this->display();
	}

	public function updateyuyuetime()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-d');
			$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		}

		$this->assign('active_yuyue', 'active');
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
				exit();
			}

			$this->assign('zx_timeStart', $canshu['zx_timeStart']);
			$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
			$datas = m('yuyue_timeupdate');
			if (($canshu['shijianleixing'] == '预约时间') || ($canshu['shijianleixing'] == '')) {
				$sql = 'select zx_ID,count(zx_ID) as total from oa_yuyue_timeupdate where zx_ID in (select zx_ID from oa_yuyue_timeupdate where yuyuetime >=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyuetime<=\'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ) group  by zx_ID  order by total desc';
				$rows = $datas->query($sql);

				foreach ($rows as $k => $v ) {
					$zx_IDrows .= $v['zx_ID'] . ',';
				}

				$zx_IDrows = trim($zx_IDrows, ',');
				$sql = 'select zx_ID,count(zx_ID) as total from oa_yuyue_timeupdate where zx_ID in (' . $zx_IDrows . ') group by zx_ID order by total desc';
				$rows = $datas->query($sql);

				foreach ($rows as $k => $v ) {
					if (1 < $v['total']) {
						$zx_IDrows1 .= $v['zx_ID'] . ',';
					}
				}

				$zx_IDrows1 = trim($zx_IDrows1, ',');
				$sql = 'select a.yuyuetime,a.updatetime,b.huanzeName,b.shouji,b.zx_ID,b.bz_name,b.userchinaname,b.daozhen_time ' . "\r\n" . '						   from oa_yuyue_timeupdate as a inner join oa_managezx as b on a.zx_ID = b.zx_ID where   a.zx_ID in(' . $zx_IDrows1 . ') ' . $tiaojian . '  order by a.zx_ID,a.up_ID';
				$list = $datas->query($sql);
			}
			else {
				$sql = 'select zx_ID,count(zx_ID) as total from oa_yuyue_timeupdate where zx_ID in (select zx_ID from oa_yuyue_timeupdate where updatetime>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' and updatetime<=\'' . $canshu['zx_timeEnd'] . ' 23:59:59\'' . "\r\n" . ')  group by zx_ID';
				$rows = $datas->query($sql);

				foreach ($rows as $k => $v ) {
					if (1 < $v['total']) {
						$zx_IDrows1 .= $v['zx_ID'] . ',';
					}
				}

				$zx_IDrows1 = trim($zx_IDrows1, ',');
				$sql = 'select a.yuyuetime,a.updatetime,b.huanzeName,b.shouji,b.zx_ID,b.bz_name,b.userchinaname,b.daozhen_time ' . "\r\n" . '						   from oa_yuyue_timeupdate as a inner join oa_managezx as b on a.zx_ID = b.zx_ID where   a.zx_ID in(' . $zx_IDrows1 . ') ' . $tiaojian . '  order by a.zx_ID,a.up_ID';
				$list = $datas->query($sql);
			}

			$this->assign('list', $list);
		}

		$this->display();
	}

	public function buyuyuecaozuo()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['submit1'] != '') {
			if (8035200 < ($endtime - $starttime)) {
				js_alert('', '日期不支持超过三个月');
				exit();
			}

			$sql = 'select b.userchinaname,b.huanzeName,b.bz_name,b.shouji,b.zx_time,a.buyuyue_caozuotime,b.yuyuetime,b.daozhen_time from oa_huanzeyuyue as a inner join oa_managezx as b on a.zx_ID=b.zx_ID and b.zx_time>=\'' . $canshu['zx_timeStart'] . ' 00:00:00\' and b.zx_time<=\'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . $tiaojian . ' and a.shifoubuyuyue=0';
			$datas = m('huanzeyuyue');
			$list = $datas->query($sql);
			$this->assign('list', $list);
			$this->assign('zx_timeStart', $canshu['zx_timeStart']);
			$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		}

		$this->display();
	}
}


?>
