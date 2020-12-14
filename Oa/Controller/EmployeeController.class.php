<?php

namespace OA\Controller;



class EmployeeController extends \Component\AdminController
{
	public function employee_gonggao()
	{
		$this->display();
	}

	public function employee_paiban()
	{
		$this->display();
	}

	public function employee_xiangmu()
	{
		$this->display();
	}

	public function employee_rizhi()
	{
		$canshu = i('request.');
		$xingqi = date('N', time());

		switch ($xingqi) {
		case 1:
			$xingqiji = '周一';
			break;

		case 2:
			$xingqiji = '周二';
			break;

		case 3:
			$xingqiji = '周三';
		case 4:
			$xingqiji = '周四';
			break;

		case 5:
			$xingqiji = '周五';
			break;

		case 6:
			$xingqiji = '周六';
			break;

		default:
			$xingqiji = '周日';
		}

		$this->assign('xingqiji', $xingqiji);
		$today = date('Y-m-d');
		$this->assign('today', $today);
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$this->assign('tomorrow', $tomorrow);
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

		$this->assign('ssyy', $ssyy);
		$this->assign('zixunyuan1', $zixunyuan1);
		$this->assign('dqzxy', $canshu['userID']);
		$gongzuozu1 = m('role');
		$gongzuozu = $gongzuozu1->where('role_del=0')->getField('role_ID,role_name,role_sort');
		$this->assign('gongzuozu', $gongzuozu);

		if ($canshu['yy_ID'] != '') {
			$tiaojian = ' and yy_ID=' . $canshu['yy_ID'] . ' ';
			$this->assign('dqyyID', $canshu['yy_ID']);
		}

		if ($canshu['gongzuozu'] != '') {
			$tiaojian .= ' and role_ID=' . $canshu['gongzuozu'] . ' ';
			$this->assign('dqrole_ID', $canshu['gongzuozu']);
		}

		if ((session('user_role') == 1) || (session('user_role') == 2)) {
			if ($canshu['userID'] == '') {
				$tiaojian .= '  ';
			}
			else {
				$tiaojian .= 'and user_ID=' . $canshu['userID'] . '';
			}
		}
		else {
			$tiaojian .= ' and user_ID=' . session('username_lf') . ' ';
		}

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-d', strtotime('-1 days'));
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (2764800 < ($endtime - $starttime)) {
			js_alert('', '日期不支持超过一个月，请分段查询！');
			exit();
		}

		$yuangong = $gongzuozu1->query('select user_ID,userchinaname from oa_useradmin where user_del=0 ' . $tiaojian . ' order by role_ID');
		$week = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);
		$x = 0;

		for ($i = 0; $i <= count($arrDate) - 1; $i++) {
			for ($y = 0; $y <= count($yuangong) - 1; $y++) {
				$x++;
				$sql = 'select rizhi_ID,rizhi_content,add_time,last_time from oa_gongzuorizhi where rizhi_date=\'' . $arrDate[$i] . '\' and userID=' . $yuangong[$y]['user_ID'] . '';
				$rizhiInfo = $user->query($sql);
				$list[$x]['rizhi_date'] = $arrDate[$i];
				$list[$x]['rizhi_content'] = $rizhiInfo[0]['rizhi_content'];
				$list[$x]['last_time'] = $rizhiInfo[0]['last_time'];
				$list[$x]['rizhi_ID'] = $rizhiInfo[0]['rizhi_ID'];
				$list[$x]['add_time'] = $rizhiInfo[0]['add_time'];
				$sql = 'select jihua_ID,jihua_content,add_time,last_time from oa_gongzuojihua where jihua_date=\'' . $arrDate[$i] . '\' and userID=' . $yuangong[$y]['user_ID'] . '';
				$rizhiInfo = $user->query($sql);
				$list[$x]['jihua_content'] = $rizhiInfo[0]['jihua_content'];
				$list[$x]['jihua_last_time'] = $rizhiInfo[0]['last_time'];
				$list[$x]['jihua_ID'] = $rizhiInfo[0]['jihua_ID'];
				$list[$x]['jihua_add_time'] = $rizhiInfo[0]['add_time'];
				$list[$x]['userchinaname'] = $yuangong[$y]['userchinaname'];
				$list[$x]['xingqi'] = wk($arrDate[$i]);
				$list[$x]['userID'] = $yuangong[$y]['user_ID'];
			}
		}

		$heji = count($list);
		$diyitianarr = array();

		for ($i = 0; $i < count($yuangong); $i++) {
			$biaoge[$i]['userchinaname'] = $yuangong[$i]['userchinaname'];

			for ($x = 0; $x < count($arrDate); $x++) {
				for ($z = 0; $z <= count($list); $z++) {
					if (($list[$z]['userchinaname'] == $yuangong[$i]['userchinaname']) && ($list[$z]['rizhi_date'] == $arrDate[$x])) {
						if (empty($list[$z]['rizhi_content'])) {
							$diyitian = '<div class="panel panel-warning" style="text-align:center;margin-bottom:5px; min-height:48px;"><div class="panel-heading">';
						}
						else if (empty($list[$z]['jihua_content'])) {
							$diyitian = '<div class="panel panel-info" style="text-align:center;margin-bottom:5px; min-height:48px;"><div class="panel-heading">';
						}
						else {
							$diyitian = '<div class="panel panel-default" style="text-align:center;margin-bottom:5px; min-height:48px;"><div class="panel-heading">';
						}

						$diyitian .= $list[$z]['rizhi_date'];
						$diyitian .= '</div>';

						if (empty($list[$z]['rizhi_content'])) {
							$diyitian .= 'X日志';
						}

						if (empty($list[$z]['jihua_content'])) {
							$diyitian .= ' X计划';
						}

						$diyitian .= '</div>';
						array_push($diyitianarr, $diyitian);
						unset($diyitian);
					}
				}
			}

			$biaoge[$i]['code'] = $diyitianarr;
			$diyitianarr = array();
		}

		for ($i = 0; $i <= $heji; $i++) {
			if (empty($list[$i]['rizhi_content']) && empty($list[$i]['jihua_content'])) {
				unset($list[$i]);
			}
		}

		if ($canshu['tian_geshi'] == '2') {
			$this->assign('active_tj', 'active');
			$this->assign('checked_tj', 'checked');
			$this->assign('panuan_lb', '2');
		}
		else {
			$this->assign('active_lb', 'active');
		}

		$this->assign('yestoday', $canshu['zx_timeStart']);
		$this->assign('tomorrow', $canshu['zx_timeEnd']);
		$this->assign('list', $list);
		$this->assign('biaoge', $biaoge);
		$this->assign('arrDate', $arrDate);
		$this->display();
	}

	public function yuangongliandong()
	{
		$zixunyuan1 = htmlspecialchars(trim($_POST['zixunyuan']));

		if (!empty($zixunyuan1)) {
			$sql = 'SELECT user_ID,userchinaname FROM oa_useradmin WHERE user_rizhi=0 and  find_in_set(' . $zixunyuan1 . ', yy_ID)    and  user_del = \'0\'';
			$data = m('useradmin');
			$syid1 = $data->query($sql);
			echo '<option value=\'\'>所有员工</option>';

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
			echo '<option value=\'\'>所有员工</option>';

			for ($i = 0; $i <= count($yyarr) - 1; $i++) {
				$zixunyuan = $user->query('select user_ID,userchinaname,role_ID from oa_useradmin where user_del=0  and find_in_set(' . $yyarr[$i] . ',yy_ID)');

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

	public function rizhi_add()
	{
		$canshu = i('request.');
		$rizhi = d('gongzuorizhi');
		$rizhi_ID = i('post.rizhi_ID');
		$rizhi_content = i('post.rizhi_content');

		if (empty($rizhi_ID)) {
			if ($rizhi->create()) {
				$rizhi->rizhi_content = str_replace("\n", '<br>', $rizhi->rizhi_content);
				$chongfu = $rizhi->query('select count(rizhi_ID) as total from oa_gongzuorizhi where userID=' . session('username_lf') . ' and ' . "\r\n" . '' . "\r\n" . 'rizhi_date=\'' . date('Y-m-d') . '\'');

				if (0 < $chongfu[0]['total']) {
					js_alert2('', '添加失败，因为您今日的工作日志已提交过！');
					exit();
				}

				$qian30tian = date('Y-m-d', strtotime('-30 day'));
				$xiangsidu = $rizhi->query('select rizhi_content  from oa_gongzuorizhi where userID=' . session('username_lf') . ' and ' . "\r\n" . '' . "\r\n" . ' rizhi_date>=\'' . $qian30tian . '\'  and rizhi_date<\'' . date('Y-m-d') . '\'');

				foreach ($xiangsidu as $k => $v ) {
					similar_text($v['rizhi_content'], $rizhi_content, $percent);
					$arr[] = $percent;
				}

				$a = max($arr);

				if (80 < $a) {
					js_alert2('', '今日工作日志与前面的内容相似度达到80%以上，请重新填写！');
					exit();
				}

				$rizhi->rizhi_date = date('Y-m-d');
				$rizhi->userID = session('username_lf');
				$rizhi->add_time = date('Y-m-d H:i:s');
				$rizhi->last_time = date('Y-m-d H:i:s');
				$rizhi->add();
				echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('Employee/employee_rizhi') . '\';</script>';
			}
			else {
				$this->error($rizhi->getError());
			}
		}
		else {
			$qian30tian = date('Y-m-d', strtotime('-30 day'));
			$xiangsidu = $rizhi->query('select rizhi_content  from oa_gongzuorizhi where userID=' . session('username_lf') . ' and ' . "\r\n" . '' . "\r\n" . ' rizhi_date>=\'' . $qian30tian . '\'  and rizhi_date<\'' . date('Y-m-d') . '\'');

			foreach ($xiangsidu as $k => $v ) {
				similar_text($v['rizhi_content'], $rizhi_content, $percent);
				$arr[] = $percent;
			}

			$a = max($arr);

			if (80 < $a) {
				js_alert2('', '今日工作日志与前面的内容相似度达到80%以上，请重新填写！');
				exit();
			}

			if ($rizhi->create()) {
				$rizhi->rizhi_content = str_replace("\n", '<br>', $rizhi->rizhi_content);
				$rizhi->last_time = date('Y-m-d H:i:s');
				$rizhi->save();
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改完成\');' . "\r\n" . '					   location.href=\'' . u('Employee/employee_rizhi') . '\';</script>';
			}
			else {
				js_alert2('', '修改发生错误');
			}
		}

		echo $rizhi->getlastsql();
		exit();
	}

	public function jihua_add()
	{
		$jihua = d('gongzuojihua');
		$jihua_ID = i('post.jihua_ID');

		if (empty($jihua_ID)) {
			if ($jihua->create()) {
				$jihuatime = $jihua->jihua_date;
				$chongfu = $jihua->query('select count(jihua_ID) as total from oa_gongzuojihua where userID=' . session('username_lf') . ' and jihua_date=\'' . $jihuatime . '\'');

				if (0 < $chongfu[0]['total']) {
					js_alert2('', '添加失败，因为您' . $jihuatime . '的工作计划已提交过！');
					exit();
				}

				$jihua->jihua_date = $jihuatime;
				$jihua->userID = session('username_lf');
				$jihua->add_time = date('Y-m-d H:i:s');
				$jihua->last_time = date('Y-m-d H:i:s');
				$jihua->jihua_content = str_replace("\n", '<br>', $jihua->jihua_content);
				$jihua->add();
				echo '<script language=\'javascript\'>parent.layer.msg(\'计划添加完成\');location.href=\'' . u('Employee/employee_rizhi') . '\';</script>';
			}
			else {
				$this->error($jihua->getError());
			}
		}
		else if ($jihua->create()) {
			$jihua->jihua_content = str_replace("\n", '<br>', $jihua->jihua_content);
			$jihua->last_time = date('Y-m-d H:i:s');
			$jihua->save();
			echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('Employee/employee_rizhi') . '\';</script>';
		}
		else {
			js_alert2('', '添加发生错误');
		}
	}
}


?>
