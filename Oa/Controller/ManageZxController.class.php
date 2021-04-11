<?php

namespace OA\Controller;
use PHPExcel_IOFactory;



class ManageZxController extends \Component\AdminController
{
	public function manage()
	{
		$canshu = i('request.');

		if(empty($canshu)){
			if (empty($canshu['zx_timeStart'])) {
				$canshu['zx_timeStart'] = date('Y-m-d', strtotime('-365 days'));
			}

			if (empty($canshu['zx_timeEnd'])) {
				$canshu['zx_timeEnd'] = date('Y-m-d');
			}
		}


		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (session('user_role') != 6) {
			if (31700000 < ($endtime - $starttime)) {
				js_alert('', '日期不支持超过一年，请分段查询！');
				exit();
			}
		}

		if (session('user_role') == 6) {
			if (63072000 < ($endtime - $starttime)) {
				js_alert('', '日期不支持超过两年，请分段查询！');
				exit();
			}
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

			$zixunyuan = $user->query('select user_ID,userchinaname,role_ID from oa_useradmin where user_del=0 and role_ID in(4,5) and find_in_set(' . $yyarr[$i] . ',yy_ID)');

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

		if ($canshu['yy_ID'] == '') {
			$suoshuyy = $user->where('user_ID =' . session('username_lf') . '')->getField('yy_ID');
			$tiaojian .= ' and yy_ID in(' . $suoshuyy . ') ';
		}
		else {
			$tiaojian .= ' and yy_ID = ' . $canshu['yy_ID'] . ' ';
			$URLcanshu .= '&yy_ID=' . $canshu['yy_ID'] . '';
		}

		if ($canshu['yy_ID'] != '') {
			$this->assign('dqyyID', $canshu['yy_ID']);
			$this->assign('yanse', 'red');
		}

		if (count($yyarr) == 1) {
			$this->assign('dqyyID', $yyarr['0']);
		}

		if (($canshu['userID'] == '') && ($canshu['yy_ID'] == '')) {
			$tiaojian .= ' ';
			$this->assign('zixunyuan1', $zixunyuan1);
		}
		else {
			if (($canshu['yy_ID'] == '') && ($canshu['userID'] != '')) {
				$tiaojian .= ' and userID=' . $canshu['userID'] . ' ';
				$URLcanshu .= '&userID=' . $canshu['userID'] . '';
				$this->assign('dqzxy', $canshu['userID']);
				$this->assign('zixunyuan1', $zixunyuan1);
				$this->assign('yanse1', 'red');
			}
			else {
				if (($canshu['yy_ID'] != '') && ($canshu['userID'] == '')) {
					$tiaojian .= ' ';
					$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5) and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
					$zixunName = $user->where('user_ID=' . $canshu['userID'] . '')->select();
					$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5) and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
					$this->assign('zixunyuan1', $zixunyuan2);
				}
				else {
					if (($canshu['yy_ID'] != '') && ($canshu['userID'] != '')) {
						$tiaojian .= ' and userID=' . $canshu['userID'] . ' ';
						$URLcanshu .= '&userID=' . $canshu['userID'] . '';
						$zixunName = $user->where('user_ID=' . $canshu['userID'] . '')->select();
						$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5)  and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
						$this->assign('dqzxy', $canshu['userID']);
						$this->assign('zixunyuan1', $zixunyuan2);
						$this->assign('yanse1', 'red');
					}
				}
			}
		}

		$bingzhong = m('bingzhong');
		$bz = $bingzhong->where('ID=' . $canshu['bz_ID'] . '')->getField('bz_son');

		if ($canshu['bz_ID'] == '') {
			$tiaojian .= '';
		}
		else {
			$URLcanshu .= '&bz_ID=' . $canshu['bz_ID'] . '';
			if (($bz == '0') || ($bz == '')) {
				$tiaojian .= ' and bz_ID=' . $canshu['bz_ID'] . ' ';
			}
			else {
				$tiaojian .= ' and bz_ID in(' . $canshu['bz_ID'] . ',' . rtrim($bz, ',') . ') ';
			}

			$this->assign('yanse2', 'red');
		}

		$this->assign('morenBzID', $canshu['bz_ID']);
		$this->assign('cishu', 0);
		$zixunfangshi = m('zixunfangshi');
		$zxfs = $zixunfangshi->where('ID=' . $canshu['zxfs_ID'] . '')->getField('zxfs_son');

		if ($canshu['zxfs_ID'] == '') {
			$tiaojian .= '';
		}
		else {
			$URLcanshu .= '&zxfs_ID=' . $canshu['zxfs_ID'] . '';
			if (($zxfs == '0') || ($zxfs == '')) {
				$tiaojian .= ' and zxfs_ID=' . $canshu['zxfs_ID'] . ' ';
			}
			else {
				$tiaojian .= ' and zxfs_ID in(' . $canshu['zxfs_ID'] . ',' . $zxfs . ') ';
			}

			$this->assign('yanse3', 'red');
		}

		$this->assign('morenZxfsID', $canshu['zxfs_ID']);
		$this->assign('cishu', 0);

		if ($canshu['shifoudaozhen'] == 0) {
			$tiaojian .= '';
			$this->assign('huichuan0', 'selected');
		}
		else if ($canshu['shifoudaozhen'] == 1) {
			$tiaojian .= ' and shifouyuyue=1 ';
			$this->assign('huichuan1', 'selected');
			$this->assign('yanse4', 'red');
		}
		else if ($canshu['shifoudaozhen'] == 2) {
			$tiaojian .= ' and shifouyuyue=0 ';
			$this->assign('huichuan2', 'selected');
			$this->assign('yanse4', 'red');
		}
		else if ($canshu['shifoudaozhen'] == 3) {
			$tiaojian .= ' and shifoudaozhen=0 ';
			$this->assign('huichuan3', 'selected');
			$this->assign('yanse4', 'red');
		}
		else {
			$tiaojian .= ' and shifouyuyue=0 and shifoudaozhen=1 ';
			$this->assign('huichuan4', 'selected');
			$this->assign('yanse4', 'red');
		}

		$URLcanshu .= '&shifoudaozhen=' . $canshu['shifoudaozhen'] . '';

		if ($canshu['yy_ID'] != '') {
			$xinxilaiyuan = m('xinxilaiyuan');
			$xinxiData = $xinxilaiyuan->where('xx_del=0 AND find_in_set(\'' . $canshu['yy_ID'] . '\',yy_ID)')->select();
			$this->assign('xinxidata', $xinxiData);
			$this->assign('morenxxID', $canshu['xx_ID']);
		}

		if ($canshu['xx_ID'] != '') {
			$tiaojian .= ' and xx_ID=' . $canshu['xx_ID'] . ' ';
			$URLcanshu .= '&xx_ID=' . $canshu['xx_ID'] . '';
			$this->assign('yanse5', 'red');
		}

		if ($canshu['guanjianci'] != '') {
			$tiaojian .= ' and  guanjianci like \'%' . $canshu['guanjianci'] . '%\' ';
			$URLcanshu .= '&guanjianci=' . $canshu['guanjianci'] . '';
			$this->assign('serchguanjianci', $canshu['guanjianci']);
			$this->assign('yanse6', 'red');
		}

		if ($canshu['shouji'] != '') {
			if (is_numeric(trim($canshu['shouji']))) {
				$tiaojian .= ' and  shouji like \'%' . trim($canshu['shouji']) . '%\' ';
			}
			else {
				$tiaojian .= ' and  huanzeName like \'%' . trim($canshu['shouji']) . '%\' ';
			}

			$URLcanshu .= '&shouji=' . trim($canshu['shouji']) . '';
			$this->assign('serchshouji', trim($canshu['shouji']));
			$this->assign('yanse7', 'red');
		}

		if ($canshu['yy_ID'] != '') {
			$wangzhan = m('wangzhan');
			$wangzhanxinxi = $wangzhan->where('wangzhan_del=0 and yy_ID=\'' . $canshu['yy_ID'] . '\'')->select();
			$this->assign('wangzhandata', $wangzhanxinxi);
			$this->assign('morenwzID', $canshu['laiyuanwangzhan']);
		}

		if ($canshu['laiyuanwangzhan'] != '') {
			$tiaojian .= ' and laiyuanwangzhan=' . $canshu['laiyuanwangzhan'] . ' ';
			$URLcanshu .= '&laiyuanwangzhan=' . $canshu['laiyuanwangzhan'] . '';
			$this->assign('yanse8', 'red');
		}

		if ($canshu['yy_ID'] != '') {
			$doctor = m('doctor');
			$doctorData = $doctor->where('doctor_del=0 and yy_ID=\'' . $canshu['yy_ID'] . '\'')->select();
			$this->assign('doctorData', $doctorData);
			$this->assign('morendocID', $canshu['yuyueyishengID']);
		}

		if ($canshu['yuyueyishengID'] != '') {
			$tiaojian .= ' and yuyueyishengID=' . $canshu['yuyueyishengID'] . ' ';
			$URLcanshu .= '&yuyueyishengID=' . $canshu['yuyueyishengID'] . '';
			$this->assign('yanse9', 'red');
		}

		if ($canshu['yuyuehao'] != '') {
			$tiaojian .= ' and  yuyuehao like \'%' . $canshu['yuyuehao'] . '%\' ';
			$URLcanshu .= '&yuyuehao=' . $canshu['yuyuehao'] . '';
			$this->assign('serchyuyuehao', $canshu['yuyuehao']);
			$this->assign('yanse10', 'red');
		}

		if ($canshu['IPdizhi'] != '') {
			$tiaojian .= ' and  (IPdizhi like \'%' . trim($canshu['IPdizhi']) . '%\' or yongjiushenfen like \'%' . trim($canshu['IPdizhi']) . '%\')';
			$URLcanshu .= '&IPdizhi=' . trim($canshu['IPdizhi']) . '';
			$this->assign('serchIPdizhi', trim($canshu['IPdizhi']));
			$this->assign('yanse12', 'red');
		}

		if ($canshu['QQhaoma'] != '') {
			$tiaojian .= ' and (QQhaoma like \'%' . trim($canshu['QQhaoma']) . '%\' or weixinhao like \'%' . trim($canshu['QQhaoma']) . '%\')';
			$URLcanshu .= '&QQhaoma=' . trim($canshu['QQhaoma']) . '';
			$this->assign('serchQQhaoma', trim($canshu['QQhaoma']));
			$this->assign('yanse18', 'red');
		}

		if ($canshu['shifouzhuyuan'] == '1') {
			$tiaojian .= ' and  shifouzhuyuan = 1 ';
			$URLcanshu .= '&shifouzhuyuan=' . $canshu['shifouzhuyuan'] . '';
			$this->assign('huichuanzy0', 'selected');
			$this->assign('yanse15', 'red');
		}

		if ($canshu['shifouzhuyuan'] == '2') {
			$tiaojian .= ' and  shifouzhuyuan = 0 ';
			$URLcanshu .= '&shifouzhuyuan=' . $canshu['shifouzhuyuan'] . '';
			$this->assign('huichuanzy1', 'selected');
			$this->assign('yanse15', 'red');
		}

		if ($canshu['xiaofei'] == '1') {
			$tiaojian .= ' and  xiaofei > 0 ';
			$URLcanshu .= '&xiaofei=' . $canshu['xiaofei'] . '';
			$this->assign('huichuanxf0', 'selected');
			$this->assign('yanse16', 'red');
		}

		if ($canshu['xiaofei'] == '2') {
			$tiaojian .= ' and  xiaofei = 0 ';
			$URLcanshu .= '&xiaofei=' . $canshu['xiaofei'] . '';
			$this->assign('huichuanxf1', 'selected');
			$this->assign('yanse16', 'red');
		}

		if (($canshu['zx_timeStart'] == '') && ($canshu['zx_timeEnd'] == '')) {
		}
		else {
			if (($canshu['zx_timeStart'] != '') && ($canshu['zx_timeEnd'] != '') && ($canshu['zx_timeStart'] == $canshu['zx_timeEnd'])) {
				$tiaojian .= ' and zx_time between \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and \'' . $canshu['zx_timeStart'] . ' 23:59:59\' ';
				$URLcanshu .= '&zx_timeStart=' . $canshu['zx_timeStart'] . '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '';
			}
			else {
				if ($canshu['zx_timeStart'] != '') {
					$tiaojian .= ' and zx_time >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' ';
					$URLcanshu .= '&zx_timeStart=' . $canshu['zx_timeStart'] . '';
				}

				if ($canshu['zx_timeEnd'] != '') {
					$tiaojian .= ' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ';
					$URLcanshu .= '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '';
				}
			}

			$this->assign('yanse13', 'red');
		}

		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		if (($canshu['yuyueTimeStart'] == '') && ($canshu['yuyueTimeEnd'] == '')) {
		}
		else {
			if (($canshu['yuyueTimeStart'] != '') && ($canshu['yuyueTimeEnd'] != '') && ($canshu['yuyueTimeStart'] == $canshu['yuyueTimeEnd'])) {
				$tiaojian .= ' and yuyueTime between \'' . $canshu['yuyueTimeStart'] . ' 00:00:00\' and \'' . $canshu['yuyueTimeStart'] . ' 23:59:59\' and shifouyuyue=0 ';
				$URLcanshu .= '&yuyueTimeStart=' . $canshu['yuyueTimeStart'] . '&yuyueTimeEnd=' . $canshu['yuyueTimeEnd'] . '';
			}
			else {
				if ($canshu['yuyueTimeStart'] != '') {
					$tiaojian .= ' and yuyueTime >= \'' . $canshu['yuyueTimeStart'] . ' 00:00:00\' ';
					$URLcanshu .= '&yuyueTimeStart=' . $canshu['yuyueTimeStart'] . '';
				}

				if ($canshu['yuyueTimeEnd'] != '') {
					$tiaojian .= ' and yuyueTime <= \'' . $canshu['yuyueTimeEnd'] . ' 23:59:59\' ';
					$URLcanshu .= '&yuyueTimeEnd=' . $canshu['yuyueTimeEnd'] . '';
				}

				$tiaojian .= ' and shifouyuyue=0 ';
			}

			$this->assign('yanse14', 'red');
		}

		$this->assign('yuyueTimeStart', $canshu['yuyueTimeStart']);
		$this->assign('yuyueTimeEnd', $canshu['yuyueTimeEnd']);
		if (($canshu['daozhen_timeStart'] == '') && ($canshu['daozhen_timeEnd'] == '')) {
		}
		else {
			if (($canshu['daozhen_timeStart'] != '') && ($canshu['daozhen_timeEnd'] != '') && ($canshu['daozhen_timeStart'] == $canshu['daozhen_timeEnd'])) {
				$tiaojian .= ' and daozhen_time between \'' . $canshu['daozhen_timeStart'] . ' 00:00:00\' and \'' . $canshu['daozhen_timeStart'] . ' 23:59:59\' and shifoudaozhen=0 ';
				$URLcanshu .= '&daozhen_timeStart=' . $canshu['daozhen_timeStart'] . '&daozhen_timeEnd=' . $canshu['daozhen_timeEnd'] . '';
			}
			else {
				if ($canshu['daozhen_timeStart'] != '') {
					$tiaojian .= ' and daozhen_time >= \'' . $canshu['daozhen_timeStart'] . ' 00:00:00\' ';
					$URLcanshu .= '&daozhen_timeStart=' . $canshu['daozhen_timeStart'] . '';
				}

				if ($canshu['daozhen_timeEnd'] != '') {
					$tiaojian .= ' and daozhen_time <= \'' . $canshu['daozhen_timeEnd'] . ' 23:59:59\' ';
					$URLcanshu .= '&daozhen_timeEnd=' . $canshu['daozhen_timeEnd'] . '';
				}

				$tiaojian .= ' and shifoudaozhen=0 ';
			}

			$this->assign('yanse14', 'red');
		}

		$this->assign('daozhen_timeStart', $canshu['daozhen_timeStart']);
		$this->assign('daozhen_timeEnd', $canshu['daozhen_timeEnd']);
		$shoujixianshi = array();

		for ($i = 0; $i <= count($ssyy) - 1; $i++) {
			$shoujihao = $user->query('select * from oa_quanjushezhi where qj_ID=5 and find_in_set(' . $ssyy[$i]['yyid'] . ',qj_yyid)');

			if ($shoujihao[0]['qj_yyid'] != '') {
				array_push($shoujixianshi, $ssyy[$i]['yyid']);
			}
		}

		$this->assign('shoujixianshi', $shoujixianshi);
		$chakanxiangqing = array();

		for ($i = 0; $i <= count($ssyy) - 1; $i++) {
			$shoujihao = $user->query('select * from oa_quanjushezhi where qj_ID=2 and find_in_set(' . $ssyy[$i]['yyid'] . ',qj_yyid)');

			if ($shoujihao[0]['qj_yyid'] != '') {
				array_push($chakanxiangqing, $ssyy[$i]['yyid']);
			}
		}

		$this->assign('chakanxiangqing', $chakanxiangqing);

		if ($canshu['paixu'] == '') {
			$canshu['paixu'] = 'zx_time';
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx0', 'selected');
			$this->assign('yanse17', 'red');
		}

		if (($canshu['paixu'] == 'zx_time') || ($canshu['paixu'] == '')) {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx0', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'yuyueTime') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx1', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'userID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx2', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'huifangcishu') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx3', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'guanjianci') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx4', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'bz_ID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx5', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'zxfs_ID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx6', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'xx_ID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx7', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'xiaofei') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx8', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'daozhen_time') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx9', 'selected');
			$this->assign('yanse17', 'red');
		}

		if (session('user_role') == 6) {
			$tiaojian .= ' and shifouyuyue=0 order by ' . $canshu['paixu'] . ' desc ';
		}
		else {
			$tiaojian .= ' order by ' . $canshu['paixu'] . ' desc ';
		}

		$page = new \Component\PageLF();
		$info = $page->fenye1('oa_managezx', $tiaojian, '', '');
		$fenye5 = $page->fenye2('oa_managezx', $tiaojian, '', '', $URLcanshu);
		$this->assign('list', $info);
		$this->assign('fenye5', $fenye5);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('role_ID', $data['role_ID']);
		$this->assign('dquser_ID', $data['user_ID']);
		$this->assign('tiaojian', $tiaojian);
		$allshow = $this->show_menu();
		$usershow = explode(',', $data['show_menu']);

		foreach ($allshow as $k => $v ) {
			if (in_array($k, $usershow)) {
				$chenked = 'checked';
			}

			$show_list[] = array('showname' => $v, 'bianhao' => $k, 'checked' => $chenked);
			unset($chenked);
		}

		$this->assign('show_list', $show_list);
		$this->display('manage');
	}

	public function managexq()
	{
		$zx_ID = htmlspecialchars(trim($_POST['zxid']));
		$page = htmlspecialchars(trim($_POST['page']));
		$page_size = htmlspecialchars(trim($_POST['page_size']));
		$shifoudaozhen = htmlspecialchars(trim($_POST['shifoudaozhen']));
		$zx_timeStart = htmlspecialchars(trim($_POST['zx_timeStart']));
		$zx_timeEnd = htmlspecialchars(trim($_POST['zx_timeEnd']));
		$paixu = htmlspecialchars(trim($_POST['paixu']));

		if (!empty($zx_ID)) {
			$User = m('managezx');
			$data = $User->where('zx_ID=' . $zx_ID)->find();
			$htmldm = '<td class=\'shoujixs\' id=\'tdd1\' style=\'white-space:nowrap\'>';
			$htmldm .= ' <div class=\'checkbox-inline b-label\'>';
			$htmldm .= ' <input id=\'quanxuan\' name=\'quanxuan[]\' type=\'checkbox\' value=\'' . $zx_ID . '\'>';
			$htmldm .= ' </div>';
			$htmldm .= $zx_ID . '</td>';
			$htmldm .= '<td id=\'tdd2\'>';

			if ($data['shifoudaozhen'] == 0) {
				$htmldm .= '<span class=\'badge badge-info\'>已到诊</span>';
			}
			else if ($data['shifouyuyue'] == 0) {
				$htmldm .= '<span class=\'badge badge-warning\'>有预约</span>';
			}
			else {
				$htmldm .= '<span class=\'badge\'>仅咨询</span>';
			}

			$htmldm .= '</td>';
			$htmldm .= '<td id=\'tdd3\'>';
			$a1 = date('Y-m-d');
			$a2 = substr($data['zx_time'], 0, 10);
			$a3 = strtotime($a1) - strtotime($a2);
			$a4 = substr($data['zx_time'], 5, 11);

			if ($a1 == $a2) {
				$htmldm .= '今天' . $a4;
			}
			else if ($a3 == 86400) {
				$htmldm .= '昨天' . $a4;
			}
			else {
				$htmldm .= substr($data['zx_time'], 0, 16);
			}

			$htmldm .= '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd4\'>' . $data['yy_name'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd5\' title=\'' . $data['huanzeName'] . '\'>' . $data['huanzeName'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd6\'>';
			$a1 = date('Y-m-d');
			$a2 = substr($data['yuyueTime'], 0, 10);
			$a3 = strtotime($a2) - strtotime($a1);
			$a4 = substr($data['yuyueTime'], 5, 11);

			if ($a1 == $a2) {
				$htmldm .= '<font color=\'#FF0000\'>今天' . $a4 . '</font>';
			}
			else if ($a3 == 86400) {
				$htmldm .= '<font color=\'#CC6600\'>明天' . $a4 . '</font>';
			}
			else if (strtotime($a2) == '') {
				$htmldm .= '';
			}
			else if ($data['shifoudaozhen'] == 0) {
				$htmldm .= '<font color=\'#00CC00\'>' . substr($data['yuyueTime'], 0, 16) . '</font>';
			}
			else {
				$htmldm .= substr($data['yuyueTime'], 0, 16);
			}

			$htmldm .= '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd7\'>';

			if ($data['shouji'] != '') {
				$htmldm .= $data['shouji'];
			}

			$htmldm .= '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd8\'>' . $data['zxfs_name'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd9\'>' . $data['bz_name'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd10\'>' . $data['xx_name'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd11\'><a title=\'' . $data['guanjianci'] . '\'>' . $data['guanjianci'] . '</a></td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd12\'>' . $data['daozhen_time'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd13\'>' . $data['userchinaname'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd14\'><a href=\'###\' onClick="hfjl(\'' . $data['zx_ID'] . '\',\'' . $data['hfjh_ID'] . '\')" >回访<font color=\'#FF0000\'>' . $data['huifangcishu'] . '</font>次</a></td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd15\'>' . $data['xiaofei'] . '</td>';
			$htmldm .= '<td class=\'shoujixs\' id=\'tdd16\'>';

			if ($data['shifouzhuyuan'] == 0) {
				$htmldm .= '是';
			}
			else {
				$htmldm .= '否';
			}

			$htmldm .= '</td>';
			$htmldm .= '<td id=\'tdd17\' class=\'shoujixs\'>';

			if ($data['shifoudaozhen'] == 0) {
				$htmldm .= '<span class=\'badge badge-info\'>已经到诊</span>';
			}
			else {
				if ((($_SESSION['user_role'] == 1) || ($_SESSION['user_role'] == 2) || ($_SESSION['user_role'] == 6)) && ($data['shifouyuyue'] == 0)) {
					$htmldm .= '<a href="javascript:querdz(\'' . $zx_ID . '\',\'' . $page . '\',\'' . $page_size . '&shifoudaozhen=' . $shifoudaozhen . '&zx_timeStart=' . $zx_timeStart . '&zx_timeEnd=' . $zx_timeEnd . '&paixu=' . $paixu . '\',\'' . $data['huanzeName'] . '\',\'' . substr($data[shouji], 7, 11) . '\');"><span class=\'badge badge-danger\'>确认到诊</span></a>';
				}
				else {
					$htmldm .= '<span class=\'badge\'>尚未到诊</span>';
				}
			}

			$htmldm .= '</td>';
			$htmldm .= '<td id=\'tdd19\'>';
			$htmldm .= '<a href=\'javascript:void(0);\' onClick="duoyong(\'' . $zx_ID . '\',\'' . $page . '\',\'' . $page_size . '\',\'&shifoudaozhen=' . $shifoudaozhen . '&zx_timeStart=' . $zx_timeStart . '&zx_timeEnd=' . $zx_timeEnd . '&paixu=' . $paixu . '\',\'/index.php/Oa/Updatezixun/update?page=' . $page . '&page_size=' . $page_size . '&zx_ID=' . $zx_ID . '&shifoudaozhen=' . $shifoudaozhen . '&zx_timeStart=' . $zx_timeStart . '&zx_timeEnd=' . $zx_timeEnd . '&paixu=' . $paixu . '\')" class=\'btn btn-white btn-sm\' title=\'' . $data['yuyueBeizhu'] . '\'>查看详情</a>&nbsp;';
			$htmldm .= '<a href=\'javascript:void(0);\' onClick="javascript:duanxin(\'' . $zx_ID . '\');" class=\'btn btn-white btn-sm\'>短信</a>';
			$htmldm .= '</td>';
			echo $htmldm;
		}
	}

	public function managexqa()
	{
		$zx_ID = htmlspecialchars(trim($_POST['zxid']));

		if (!empty($zx_ID)) {
			$User = m('managezx');
			$data = $User->where('zx_ID=' . $zx_ID)->find();
			$htmldm = '<td colspan=\'18\'>';
			$htmldm .= '<span id=\'tdd5\'>' . $data['huanzeName'] . '</span>';
			$htmldm .= '<span id=\'tdd6\'>预约：';
			$a1 = date('Y-m-d');
			$a2 = substr($data['yuyueTime'], 0, 10);
			$a3 = strtotime($a2) - strtotime($a1);
			$a4 = substr($data['yuyueTime'], 5, 11);

			if ($a1 == $a2) {
				$htmldm .= '<font color=\'#FF0000\'>今天' . $a4 . '</font>';
			}
			else if ($a3 == 86400) {
				$htmldm .= '<font color=\'#CC6600\'>明天' . $a4 . '</font>';
			}
			else if (strtotime($a2) == '') {
				$htmldm .= '';
			}
			else if ($vo[shifoudaozhen] == 0) {
				$htmldm .= '<font color=\'#00CC00\'>' . substr($data['yuyueTime'], 0, 16) . '</font>';
			}
			else {
				$htmldm .= substr($data['yuyueTime'], 0, 16);
			}

			$htmldm .= '</span>';
			$htmldm .= '<span id=\'tdd7\'>电话：';

			if ($data['shouji'] != '') {
				$htmldm .= $data['shouji'];
			}

			$htmldm .= '</span>';
			$htmldm .= '</td>';
			echo $htmldm;
		}
	}

	public function managexqb()
	{
		$zx_ID = htmlspecialchars(trim($_POST['zxid']));

		if (!empty($zx_ID)) {
			$User = m('managezx');
			$data = $User->where('zx_ID=' . $zx_ID)->find();
			$htmldm = '<td id=\'tdd18\' colspan=\'18\'>';
			$htmldm .= '<small>最后回访:' . $data['lasthuifang'] . ' ' . substr($data['lasthuifang_time'], 0, 16) . '</small>';
			$htmldm .= '</td>';
			echo $htmldm;
		}
	}

	public function zixunyuanLianDong()
	{
		$zixunyuan1 = htmlspecialchars(trim($_POST['zixunyuan']));

		if (!empty($zixunyuan1)) {
			$sql = 'SELECT user_ID,userchinaname FROM oa_useradmin WHERE find_in_set(' . $zixunyuan1 . ', yy_ID) and role_ID in(4,5)   and  user_del = \'0\'';
			$data = m('useradmin');
			$syid1 = $data->query($sql);
			echo '<option value=\'\'>所有咨询员</option>';

			for ($i = 0; $i <= count($syid1) - 1; $i++) {
				if ($syid1[$i]['user_ID'] != session('username_lf')) {
					echo '<option value=' . $syid1[$i]['user_ID'] . '>' . $syid1[$i]['userchinaname'] . '</option>';
				}
				else {
					echo '<option value=' . $syid1[$i]['user_ID'] . ' selected=\'selected\'>' . $syid1[$i]['userchinaname'] . '</option>';
				}
			}
		}
		else {
			$user = m('useradmin');
			$data = $user->where('user_ID = \'' . session('username_lf') . '\'')->find();
			$yyarr = explode(',', $data['yy_ID']);
			$yyname = m('yiyuan');
			$ssyy = array();
			$zixunpanduan = array();
			echo '<option value=\'\'>所有咨询员</option>';

			for ($i = 0; $i <= count($yyarr) - 1; $i++) {
				$zixunyuan = $user->query('select user_ID,userchinaname,role_ID from oa_useradmin where user_del=0 and role_ID in(4,5) and find_in_set(' . $yyarr[$i] . ',yy_ID)');

				for ($num = 0; $num <= count($zixunyuan) - 1; $num++) {
					if (!in_array($zixunyuan[$num]['user_ID'], $zixunpanduan)) {
						if ($zixunyuan[$num]['user_ID'] != session('username_lf')) {
							echo '<option value=' . $zixunyuan[$num]['user_ID'] . '>' . $zixunyuan[$num]['userchinaname'] . '</option>';
						}
						else {
							echo '<option value=' . $zixunyuan[$num]['user_ID'] . ' selected>' . $zixunyuan[$num]['userchinaname'] . '</option>';
						}
					}

					array_push($zixunpanduan, $zixunyuan[$num]['user_ID']);
				}

				unset($zixunyuan);
			}
		}
	}

	public function delzixun()
	{
		$data = i('post.quanxuan');

		if ($data == '') {
			js_alert('', '请选择要删除的对象');
			exit();
		}

		$huanze = m('huanze');
		$huanzecaozuo = m('huanzecaozuo');
		$huanzejingjia = m('huanzejingjia');
		$huanzeinfo = m('huanzeinfo');
		$huanzeyuyue = m('huanzeyuyue');
		$huifangjihua = m('huifangjihua');
		$huanzejihua = m('huanzehuijihua');
		$managezx = m('managezx');

		for ($i = 0; $i <= count($data) - 1; $i++) {
			$huanze->where('zx_ID=' . $data[$i] . '')->delete();
			$huanzecaozuo->where('zx_ID=' . $data[$i] . '')->delete();
			$huanzejingjia->where('zx_ID=' . $data[$i] . '')->delete();
			$huanzeinfo->where('zx_ID=' . $data[$i] . '')->delete();
			$huanzeyuyue->where('zx_ID=' . $data[$i] . '')->delete();
			$huifangjihua->where('zx_ID=' . $data[$i] . '')->delete();
			$managezx->where('zx_ID=' . $data[$i] . '')->delete();
		}

		js_alert('ManageZx/manage?&page=' . $_REQUEST['page'] . '' . $_REQUEST['URLcanshu'] . '', '删除完成！');
	}

	public function SendMessage()
	{
		$zx_ID = i('get.zx_ID');
		$userInfo = m('huanzeyuyue');
		$rows = $userInfo->where('zx_ID=' . $zx_ID . '')->find();
		$userInfo1 = m('huanze');
		$rows1 = $userInfo1->where('zx_ID=' . $zx_ID . '')->find();
		$foot = m('duanxinset');
		$rows2 = $foot->where('yy_ID=' . $rows1['yy_ID'] . '')->find();
		$this->assign('huanzeName', $rows['huanzeName']);
		$this->assign('huanzeyuyue', $rows1['yuyueTime']);
		$this->assign('shouji', $rows['shouji']);
		$this->assign('yyid', $rows1['yy_ID']);
		$moban = m('duanxin');
		$moban1 = $moban->select();

		for ($i = 0; $i <= count($moban1) - 1; $i++) {
			$moban1[$i]['message_content'] = str_replace('[姓名]', $rows['huanzeName'], $moban1[$i]['message_content']);
			$moban1[$i]['message_content'] = str_replace('[预约日期]', substr($rows1['yuyueTime'], 0, 10), $moban1[$i]['message_content']);
			$moban1[$i]['message_content'] = str_replace('[预约号]', $rows['yuyuehao'], $moban1[$i]['message_content']);

			if (!empty($rows2['dx_footContent'])) {
				$moban1[$i]['message_content'] .= ' [' . $rows2['dx_footContent'] . ']';
			}
		}

		$this->assign('duanxinmoban', $moban1);
		$this->display();
	}

	public function manage_Excle()
	{
		$canshu = i('get.');
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

			$zixunyuan = $user->query('select user_ID,userchinaname,role_ID from oa_useradmin where user_del=0 and role_ID in(4,5) and find_in_set(' . $yyarr[$i] . ',yy_ID)');

			for ($num = 0; $num <= count($zixunyuan) - 1; $num++) {
				if (!in_array($zixunyuan[$num]['user_ID'], $zixunpanduan)) {
					$zixunyuan1[] = array('user_ID' => $zixunyuan[$num]['user_ID'], 'userchinaname' => $zixunyuan[$num]['userchinaname'], 'role_ID' => $zixunyuan[$num]['role_ID']);
				}

				array_push($zixunpanduan, $zixunyuan[$num]['user_ID']);
			}

			unset($zixunyuan);
		}

		$this->assign('ssyy', $ssyy);

		if ($canshu['yy_ID'] == '') {
			$suoshuyy = $user->where('user_ID =' . session('username_lf') . '')->getField('yy_ID');
			$tiaojian .= ' and yy_ID in(' . $suoshuyy . ') ';
		}
		else {
			$tiaojian .= ' and yy_ID = ' . $canshu['yy_ID'] . ' ';
			$URLcanshu .= '&yy_ID=' . $canshu['yy_ID'] . '';
		}

		if ($canshu['yy_ID'] != '') {
			$this->assign('dqyyID', $canshu['yy_ID']);
			$this->assign('yanse', 'red');
		}

		if (($canshu['userID'] == '') && ($canshu['yy_ID'] == '')) {
			$tiaojian .= ' ';
			$this->assign('zixunyuan1', $zixunyuan1);
		}
		else {
			if (($canshu['yy_ID'] == '') && ($canshu['userID'] != '')) {
				$tiaojian .= ' and userID=' . $canshu['userID'] . ' ';
				$URLcanshu .= '&userID=' . $canshu['userID'] . '';
				$this->assign('dqzxy', $canshu['userID']);
				$this->assign('zixunyuan1', $zixunyuan1);
				$this->assign('yanse1', 'red');
			}
			else {
				if (($canshu['yy_ID'] != '') && ($canshu['userID'] == '')) {
					$tiaojian .= ' ';
					$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5) and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
					$zixunName = $user->where('user_ID=' . $canshu['userID'] . '')->select();
					$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5) and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
					$this->assign('zixunyuan1', $zixunyuan2);
				}
				else {
					if (($canshu['yy_ID'] != '') && ($canshu['userID'] != '')) {
						$tiaojian .= ' and userID=' . $canshu['userID'] . ' ';
						$URLcanshu .= '&userID=' . $canshu['userID'] . '';
						$zixunName = $user->where('user_ID=' . $canshu['userID'] . '')->select();
						$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5)  and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
						$this->assign('dqzxy', $canshu['userID']);
						$this->assign('zixunyuan1', $zixunyuan2);
						$this->assign('yanse1', 'red');
					}
				}
			}
		}

		$bingzhong = m('bingzhong');
		$bz = $bingzhong->where('ID=' . $canshu['bz_ID'] . '')->getField('bz_son');

		if ($canshu['bz_ID'] == '') {
			$tiaojian .= '';
		}
		else {
			$URLcanshu .= '&bz_ID=' . $canshu['bz_ID'] . '';
			if (($bz == '0') || ($bz == '')) {
				$tiaojian .= ' and bz_ID=' . $canshu['bz_ID'] . ' ';
			}
			else {
				$tiaojian .= ' and bz_ID in(' . $canshu['bz_ID'] . ',' . rtrim($bz, ',') . ') ';
			}

			$this->assign('yanse2', 'red');
		}

		$this->assign('morenBzID', $canshu['bz_ID']);
		$this->assign('cishu', 0);
		$zixunfangshi = m('zixunfangshi');
		$zxfs = $zixunfangshi->where('ID=' . $canshu['zxfs_ID'] . '')->getField('zxfs_son');

		if ($canshu['zxfs_ID'] == '') {
			$tiaojian .= '';
		}
		else {
			$URLcanshu .= '&zxfs_ID=' . $canshu['zxfs_ID'] . '';
			if (($zxfs == '0') || ($zxfs == '')) {
				$tiaojian .= ' and zxfs_ID=' . $canshu['zxfs_ID'] . ' ';
			}
			else {
				$tiaojian .= ' and zxfs_ID in(' . $canshu['zxfs_ID'] . ',' . $zxfs . ') ';
			}

			$this->assign('yanse3', 'red');
		}

		$this->assign('morenZxfsID', $canshu['zxfs_ID']);
		$this->assign('cishu', 0);

		if ($canshu['shifoudaozhen'] == 0) {
			$tiaojian .= '';
			$this->assign('huichuan0', 'selected');
		}
		else if ($canshu['shifoudaozhen'] == 1) {
			$tiaojian .= ' and shifouyuyue=1 ';
			$this->assign('huichuan1', 'selected');
			$this->assign('yanse4', 'red');
		}
		else if ($canshu['shifoudaozhen'] == 2) {
			$tiaojian .= ' and shifouyuyue=0 ';
			$this->assign('huichuan2', 'selected');
			$this->assign('yanse4', 'red');
		}
		else if ($canshu['shifoudaozhen'] == 3) {
			$tiaojian .= ' and shifoudaozhen=0 ';
			$this->assign('huichuan3', 'selected');
			$this->assign('yanse4', 'red');
		}
		else {
			$tiaojian .= ' and shifouyuyue=0 and shifoudaozhen=1 ';
			$this->assign('huichuan4', 'selected');
			$this->assign('yanse4', 'red');
		}

		$URLcanshu .= '&shifoudaozhen=' . $canshu['shifoudaozhen'] . '';

		if ($canshu['yy_ID'] != '') {
			$xinxilaiyuan = m('xinxilaiyuan');
			$xinxiData = $xinxilaiyuan->where('xx_del=0 AND find_in_set(\'' . $canshu['yy_ID'] . '\',yy_ID)')->select();
			$this->assign('xinxidata', $xinxiData);
			$this->assign('morenxxID', $canshu['xx_ID']);
		}

		if ($canshu['xx_ID'] != '') {
			$tiaojian .= ' and xx_ID=' . $canshu['xx_ID'] . ' ';
			$URLcanshu .= '&xx_ID=' . $canshu['xx_ID'] . '';
			$this->assign('yanse5', 'red');
		}

		if ($canshu['guanjianci'] != '') {
			$tiaojian .= ' and  guanjianci like \'%' . $canshu['guanjianci'] . '%\' ';
			$URLcanshu .= '&guanjianci=' . $canshu['guanjianci'] . '';
			$this->assign('serchguanjianci', $canshu['guanjianci']);
			$this->assign('yanse6', 'red');
		}

		if ($canshu['shouji'] != '') {
			if (is_numeric($canshu['shouji'])) {
				$tiaojian .= ' and  shouji like \'%' . $canshu['shouji'] . '%\' ';
			}
			else {
				$tiaojian .= ' and  huanzeName like \'%' . $canshu['shouji'] . '%\' ';
			}

			$URLcanshu .= '&shouji=' . $canshu['shouji'] . '';
			$this->assign('serchshouji', $canshu['shouji']);
			$this->assign('yanse7', 'red');
		}

		if ($canshu['yy_ID'] != '') {
			$wangzhan = m('wangzhan');
			$wangzhanxinxi = $wangzhan->where('wangzhan_del=0 and yy_ID=\'' . $canshu['yy_ID'] . '\'')->select();
			$this->assign('wangzhandata', $wangzhanxinxi);
			$this->assign('morenwzID', $canshu['laiyuanwangzhan']);
		}

		if ($canshu['laiyuanwangzhan'] != '') {
			$tiaojian .= ' and laiyuanwangzhan=' . $canshu['laiyuanwangzhan'] . ' ';
			$URLcanshu .= '&laiyuanwangzhan=' . $canshu['laiyuanwangzhan'] . '';
			$this->assign('yanse8', 'red');
		}

		if ($canshu['yy_ID'] != '') {
			$doctor = m('doctor');
			$doctorData = $doctor->where('doctor_del=0 and yy_ID=\'' . $canshu['yy_ID'] . '\'')->select();
			$this->assign('doctorData', $doctorData);
			$this->assign('morendocID', $canshu['yuyueyishengID']);
		}

		if ($canshu['yuyueyishengID'] != '') {
			$tiaojian .= ' and yuyueyishengID=' . $canshu['yuyueyishengID'] . ' ';
			$URLcanshu .= '&yuyueyishengID=' . $canshu['yuyueyishengID'] . '';
			$this->assign('yanse9', 'red');
		}

		if ($canshu['yuyuehao'] != '') {
			$tiaojian .= ' and  yuyuehao like \'%' . $canshu['yuyuehao'] . '%\' ';
			$URLcanshu .= '&yuyuehao=' . $canshu['yuyuehao'] . '';
			$this->assign('serchyuyuehao', $canshu['yuyuehao']);
			$this->assign('yanse10', 'red');
		}

		if ($canshu['IPdizhi'] != '') {
			$tiaojian .= ' and  IPdizhi like \'%' . $canshu['IPdizhi'] . '%\' ';
			$URLcanshu .= '&IPdizhi=' . $canshu['IPdizhi'] . '';
			$this->assign('serchIPdizhi', $canshu['IPdizhi']);
			$this->assign('yanse12', 'red');
		}

		if ($canshu['shifouzhuyuan'] == '1') {
			$tiaojian .= ' and  shifouzhuyuan = 1 ';
			$URLcanshu .= '&shifouzhuyuan=' . $canshu['shifouzhuyuan'] . '';
			$this->assign('huichuanzy0', 'selected');
			$this->assign('yanse15', 'red');
		}

		if ($canshu['shifouzhuyuan'] == '2') {
			$tiaojian .= ' and  shifouzhuyuan = 0 ';
			$URLcanshu .= '&shifouzhuyuan=' . $canshu['shifouzhuyuan'] . '';
			$this->assign('huichuanzy1', 'selected');
			$this->assign('yanse15', 'red');
		}

		if ($canshu['xiaofei'] == '1') {
			$tiaojian .= ' and  xiaofei > 0 ';
			$URLcanshu .= '&xiaofei=' . $canshu['xiaofei'] . '';
			$this->assign('huichuanxf0', 'selected');
			$this->assign('yanse16', 'red');
		}

		if ($canshu['xiaofei'] == '2') {
			$tiaojian .= ' and  xiaofei = 0 ';
			$URLcanshu .= '&xiaofei=' . $canshu['xiaofei'] . '';
			$this->assign('huichuanxf1', 'selected');
			$this->assign('yanse16', 'red');
		}

		if (($canshu['zx_timeStart'] == '') && ($canshu['zx_timeEnd'] == '')) {
		}
		else {
			if (($canshu['zx_timeStart'] != '') && ($canshu['zx_timeEnd'] != '') && ($canshu['zx_timeStart'] == $canshu['zx_timeEnd'])) {
				$tiaojian .= ' and zx_time between \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and \'' . $canshu['zx_timeStart'] . ' 23:59:59\' ';
				$URLcanshu .= '&zx_timeStart=' . $canshu['zx_timeStart'] . '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '';
			}
			else {
				if ($canshu['zx_timeStart'] != '') {
					$tiaojian .= ' and zx_time >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' ';
					$URLcanshu .= '&zx_timeStart=' . $canshu['zx_timeStart'] . '';
				}

				if ($canshu['zx_timeEnd'] != '') {
					$tiaojian .= ' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ';
					$URLcanshu .= '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '';
				}
			}

			$this->assign('yanse13', 'red');
		}

		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		if (($canshu['yuyueTimeStart'] == '') && ($canshu['yuyueTimeEnd'] == '')) {
		}
		else {
			if (($canshu['yuyueTimeStart'] != '') && ($canshu['yuyueTimeEnd'] != '') && ($canshu['yuyueTimeStart'] == $canshu['yuyueTimeEnd'])) {
				$tiaojian .= ' and yuyueTime between \'' . $canshu['yuyueTimeStart'] . ' 00:00:00\' and \'' . $canshu['yuyueTimeStart'] . ' 23:59:59\' and shifouyuyue=0 ';
				$URLcanshu .= '&yuyueTimeStart=' . $canshu['yuyueTimeStart'] . '&yuyueTimeEnd=' . $canshu['yuyueTimeEnd'] . '';
			}
			else {
				if ($canshu['yuyueTimeStart'] != '') {
					$tiaojian .= ' and yuyueTime >= \'' . $canshu['yuyueTimeStart'] . ' 00:00:00\' ';
					$URLcanshu .= '&yuyueTimeStart=' . $canshu['yuyueTimeStart'] . '';
				}

				if ($canshu['yuyueTimeEnd'] != '') {
					$tiaojian .= ' and yuyueTime <= \'' . $canshu['yuyueTimeEnd'] . ' 23:59:59\' ';
					$URLcanshu .= '&yuyueTimeEnd=' . $canshu['yuyueTimeEnd'] . '';
				}

				$tiaojian .= ' and shifouyuyue=0 ';
			}

			$this->assign('yanse14', 'red');
		}

		$this->assign('yuyueTimeStart', $canshu['yuyueTimeStart']);
		$this->assign('yuyueTimeEnd', $canshu['yuyueTimeEnd']);
		if (($canshu['daozhen_timeStart'] == '') && ($canshu['daozhen_timeEnd'] == '')) {
		}
		else {
			if (($canshu['daozhen_timeStart'] != '') && ($canshu['daozhen_timeEnd'] != '') && ($canshu['daozhen_timeStart'] == $canshu['daozhen_timeEnd'])) {
				$tiaojian .= ' and daozhen_time between \'' . $canshu['daozhen_timeStart'] . ' 00:00:00\' and \'' . $canshu['daozhen_timeStart'] . ' 23:59:59\' and shifoudaozhen=0 ';
				$URLcanshu .= '&daozhen_timeStart=' . $canshu['daozhen_timeStart'] . '&daozhen_timeEnd=' . $canshu['daozhen_timeEnd'] . '';
			}
			else {
				if ($canshu['daozhen_timeStart'] != '') {
					$tiaojian .= ' and daozhen_time >= \'' . $canshu['daozhen_timeStart'] . ' 00:00:00\' ';
					$URLcanshu .= '&daozhen_timeStart=' . $canshu['daozhen_timeStart'] . '';
				}

				if ($canshu['daozhen_timeEnd'] != '') {
					$tiaojian .= ' and daozhen_time <= \'' . $canshu['daozhen_timeEnd'] . ' 23:59:59\' ';
					$URLcanshu .= '&daozhen_timeEnd=' . $canshu['daozhen_timeEnd'] . '';
				}

				$tiaojian .= ' and shifoudaozhen=0 ';
			}

			$this->assign('yanse14', 'red');
		}

		$this->assign('daozhen_timeStart', $canshu['daozhen_timeStart']);
		$this->assign('daozhen_timeEnd', $canshu['daozhen_timeEnd']);
		$shoujixianshi = array();

		for ($i = 0; $i <= count($ssyy) - 1; $i++) {
			$shoujihao = $user->query('select * from oa_quanjushezhi where qj_ID=5 and find_in_set(' . $ssyy[$i]['yyid'] . ',qj_yyid)');

			if ($shoujihao[0]['qj_yyid'] != '') {
				array_push($shoujixianshi, $ssyy[$i]['yyid']);
			}
		}

		$this->assign('shoujixianshi', $shoujixianshi);
		$chakanxiangqing = array();

		for ($i = 0; $i <= count($ssyy) - 1; $i++) {
			$shoujihao = $user->query('select * from oa_quanjushezhi where qj_ID=2 and find_in_set(' . $ssyy[$i]['yyid'] . ',qj_yyid)');

			if ($shoujihao[0]['qj_yyid'] != '') {
				array_push($chakanxiangqing, $ssyy[$i]['yyid']);
			}
		}

		$this->assign('chakanxiangqing', $chakanxiangqing);

		if ($canshu['paixu'] == '') {
			$canshu['paixu'] = 'zx_time';
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx0', 'selected');
			$this->assign('yanse17', 'red');
		}

		if (($canshu['paixu'] == 'zx_time') || ($canshu['paixu'] == '')) {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx0', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'yuyueTime') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx1', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'userID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx2', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'huifangcishu') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx3', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'guanjianci') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx4', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'bz_ID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx5', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'zxfs_ID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx6', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'xx_ID') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx7', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'xiaofei') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx8', 'selected');
			$this->assign('yanse17', 'red');
		}

		if ($canshu['paixu'] == 'daozhen_time') {
			$URLcanshu .= '&paixu=' . $canshu['paixu'] . '';
			$this->assign('huichuanpx9', 'selected');
			$this->assign('yanse17', 'red');
		}

		if (session('user_role') == 6) {
			$tiaojian .= ' and shifouyuyue=0 order by ' . $canshu['paixu'] . ' desc ';
		}
		else {
			$tiaojian .= ' order by ' . $canshu['paixu'] . ' desc ';
		}

		$maxsql = 'select count(zx_ID) as total from oa_managezx  where 1=1 ' . $tiaojian . '';
		$maxtiaoshu = $user->query($maxsql);

		if (6000 < $maxtiaoshu[0]['total']) {
			js_alert('', '最多导出行数6000行，请分段查询');
			exit();
		}

		$sqlstr = 'select * from oa_managezx  left outer join  oa_huanzeinfo on oa_managezx.zx_ID=oa_huanzeinfo.zx_ID where 1=1 ' . $tiaojian . '';
		$arr = $user->query($sqlstr);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		/* $objPHPExcel = new \Think\PHPExcel(); */
		$objPHPExcel->getProperties()->setCreator('liufeng')->setLastModifiedBy('Maarten Balliauw')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Test result file');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '咨询时间')->setCellValue('B1', '所属门店')->setCellValue('C1', '咨询客服')->setCellValue('D1', '患者姓名')->setCellValue('E1', '预约日期')->setCellValue('F1', '联系方式')->setCellValue('G1', '咨询方式')->setCellValue('H1', '咨询病种')->setCellValue('I1', '来源渠道')->setCellValue('J1', '搜索关键词')->setCellValue('K1', '匹配关键词') /* ->setCellValue('L1', '住院') */ ->setCellValue('M1', '到诊状态')->setCellValue('N1', '到诊时间')->setCellValue('O1', 'QQ号码')->setCellValue('P1', '年龄')->setCellValue('Q1', '生日')->setCellValue('R1', '职业')->setCellValue('S1', '性别')->setCellValue('T1', '消费')->setCellValue('U1', 'IP地址')->setCellValue('V1', '预约备注')->setCellValue('W1', '预约号');

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . ($i + 2), $arr[$i]['zx_time']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . ($i + 2), $arr[$i]['yy_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . ($i + 2), $arr[$i]['userchinaname']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . ($i + 2), $arr[$i]['huanzeName']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . ($i + 2), $arr[$i]['yuyueTime']);

			if ($arr[$i]['shouji'] != '') {
				if (in_array($arr[$i]['yy_ID'], $shoujixianshi) || ($arr[$i]['userID'] == $_SESSION['username_lf']) || ($_SESSION['user_role'] == 1) || ($_SESSION['user_role'] == 2) || ($_SESSION['user_role'] == 4) || ($_SESSION['user_role'] == 6)) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . ($i + 2), $arr[$i]['shouji']);
				}
				else {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . ($i + 2), substr_replace($arr[$i]['shouji'], '****', 3, 4));
				}
			}
			else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . ($i + 2), $arr[$i]['shouji']);
			}

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . ($i + 2), $arr[$i]['zxfs_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . ($i + 2), $arr[$i]['bz_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . ($i + 2), $arr[$i]['xx_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . ($i + 2), $arr[$i]['guanjianci']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . ($i + 2), $arr[$i]['ppguanjianci']);

			if ($arr[$i]['shifouzhuyuan'] == 0) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . ($i + 2), '是');
			}
			else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . ($i + 2), '否');
			}

			if ($arr[$i]['shifoudaozhen'] == 0) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . ($i + 2), '是');
			}
			else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . ($i + 2), '否');
			}

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . ($i + 2), $arr[$i]['daozhen_time']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . ($i + 2), $arr[$i]['QQhaoma']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . ($i + 2), $arr[$i]['age']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . ($i + 2), $arr[$i]['shengri']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . ($i + 2), $arr[$i]['zhiye']);

			if ($arr[$i]['xingbie'] == 2) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . ($i + 2), '女');
			}
			else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . ($i + 2), '男');
			}

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . ($i + 2), $arr[$i]['xiaofei']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . ($i + 2), $arr[$i]['IPdizhi']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . ($i + 2), $arr[$i]['yuyueBeizhu']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . ($i + 2), $arr[$i]['yuyuehao']);
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="(' . date('Y-m-d') . ')咨询详情明细.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		saveviatempfile($objWriter);
		exit();
	}

	public function show_menu()
	{
		$arr = array('1' => '编号', '4' => '所属门店', '5' => '患者姓名', '6' => '预约日期', '7' => '联系方式', '8' => '咨询方式', '9' => '咨询病种', '10' => '来源渠道', '11' => '搜索关键词', '12' => '到诊时间', '13' => '客服', '14' => '回访次数', '15' => '消费', /* '16' => '住院', */'17' => '到诊状态', '19' => '预约备注', '20' => '性别', '21' => '年龄', '22' => '预约号', '23' => '匹配关键词', '24' => 'QQ号', '25' => '微信号', '26' => '最后回访');
		return $arr;
	}

	public function user_showmenu()
	{
		$canshu = i('post.');

		if ($canshu['submit_usershow'] != '') {
			$arr = implode(',', $canshu['show_list']);

			if (mysql_query('update oa_useradmin set show_menu=\'' . $arr . '\' where user_ID = ' . session('username_lf') . '')) {
				js_alert('', '修改成功');
			}
			else {
				js_alert('', '修改失败');
			}
		}
	}
}


?>
