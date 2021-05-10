<?php

namespace OA\Controller;

class IndexController extends \Component\AdminController
{
	public function main()
	{
		$user = m('Useradmin');
		$userid = session('username_lf');
		$sql = 'select oa_useradmin.user_ID,oa_useradmin.role_ID,oa_useradmin.userchinaname,oa_role.role_auth_ids,oa_role.role_name ' . "\r\n" . '		 from oa_useradmin,oa_role ' . "\r\n" . '		 where  oa_useradmin.role_ID = oa_role.role_ID and oa_useradmin.user_ID = \'' . $userid . '\'';
		$topinfo = $user->query($sql);
		$auth_ids = explode(',', $topinfo[0]['role_auth_ids']);
		$this->assign('username', $topinfo[0]['userchinaname']);
		$this->assign('userrolename', $topinfo[0]['role_name']);
		$this->assign('userid', $topinfo[0]['role_ID']);
		$this->assign('auth_ids', $auth_ids);
		$this->assign('today', date('Y-m-d'));
		$today = date('Y-m-d');
		$yestoday = date('Y-m-d', strtotime('-1 day'));
		$sunday = date('Y-m-d', strtotime('-7 day'));
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$houtian = date('Y-m-d', strtotime('+2 day'));
		$bannianQian = date('Y-m-d', strtotime('-182 day'));
		$yinianqian = date('Y-m-d', strtotime('-365 day'));
		$liangyueqian = date('Y-m-d', strtotime('-60 day'));
		$this->assign('yestoday', $yestoday);
		$this->assign('sunday', $sunday);
		$this->assign('tomorrow', $tomorrow);

		if (session('user_role') == 6) {
			$this->assign('sunday', $bannianQian);
		}
		else {
			$this->assign('sunday', $sunday);
		}

		$this->assign('bannianQian', $bannianQian);
		$this->assign('yinianqian', $yinianqian);
		$this->assign('liangyueqian', $liangyueqian);
		$qq = $user->where('user_ID = \'' . session('username_lf') . '\'')->getField('QQhaoma');
		$this->assign('qq', $qq);
		$this->assign('user_img', '' . 'http://q1.qlogo.cn/g?b=qq&nk=' . $qq . '&s=100&t=' . time() . '');
		$this->display();
	}

	public function safe()
	{
		session('username_lf', NULL);
		session('user_role', NULL);
		session('shouji_verify', NULL);
		session_destroy();
		echo '<script language=\'javascript\'>;parent.window.location.href=\'' . DQURL . 'Login/welcome\';</script>';
	}

	public function home()
	{
		$info = array('运行环境' => $_SERVER['SERVER_SOFTWARE'], '上传附件限制' => ini_get('upload_max_filesize'), '服务器时间' => date('Y年n月j日 H:i:s'), '北京时间' => gmdate('Y年n月j日 H:i:s', time() + (8 * 3600)), '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]', '剩余空间' => round(disk_free_space('.') / (1024 * 1024), 2) . 'M');
		$this->info = $info;
		$this->assign('today', date('Y-m-d'));
		$today = date('Y-m-d');
		$yestoday = date('Y-m-d', strtotime('-1 day'));
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$houtian = date('Y-m-d', strtotime('+2 day'));
		$nianyueri = date('Y年m月d日', time());
		$xingqinum = date('N', time());
		$tianshu = date('d');

		switch ($xingqinum) {
		case 1:
			$xingqiji = '周一';
			break;

		case 2:
			$xingqiji = '周二';
			break;

		case 3:
			$xingqiji = '周三';
			break;

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

		$this->assign('nianyueri', $nianyueri);
		$this->assign('xingqiji', $xingqiji);
		$this->assign('yestoday', $yestoday);
		$this->assign('tomorrow', $tomorrow);
		$this->assign('houtian', $houtian);
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-d', strtotime('-9 days'));
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);
		$reportyy_ID = i('get.yy_ID');

		if (empty($reportyy_ID)) {
			$fenyiyuan = ' ';
		}
		else {
			$fenyiyuan = ' and yy_ID=' . $reportyy_ID . ' ';
		}

		for ($i = 0; $i <= count($arrDate) - 1; $i++) {
			$zixunliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' ' . $fenyiyuan . $tiaojian . '';
			$zixunliang = mysql_query($zixunliangSql);

			while ($aaa = mysql_fetch_array($zixunliang)) {
				$zixun = $aaa['total'];
			}

			$zixun_heji = $zixun_heji + $zixun;
			$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '		  where zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' and shifouyuyue=0' . $fenyiyuan . $tiaojian . '';
			$yuyueliang = mysql_query($yuyueliangSql);

			while ($aaa = mysql_fetch_array($yuyueliang)) {
				$yuyue = $aaa['total'];
			}

			$yuyue_heji = $yuyue_heji + $yuyue;
			$daozhenliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '		  where daozhen_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and daozhen_time <= \'' . $arrDate[$i] . ' 23:59:59\' and shifoudaozhen=0' . $fenyiyuan . $tiaojian . '';
			$daozhenliang = mysql_query($daozhenliangSql);

			while ($aaa = mysql_fetch_array($daozhenliang)) {
				$daozhen = $aaa['total'];
			}

			$daozhen_heji = $daozhen_heji + $daozhen;
			$daozhenliangSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '		  where yuyueTime  >= \'' . $arrDate[$i] . ' 00:00:00\' and yuyueTime <= \'' . $arrDate[$i] . ' 23:59:59\' and shifouyuyue=0' . $fenyiyuan . $tiaojian . '';
			$daozhenliang1 = mysql_query($daozhenliangSql1);

			while ($aaa = mysql_fetch_array($daozhenliang1)) {
				$daozhen1 = $aaa['total'];
			}

			$ydaozhen_heji = $ydaozhen_heji + $daozhen1;
			$yuyuelv = round(($yuyue / $zixun) * 100) . '%';
			$daozhenlv = round(($daozhen / $yuyue) * 100) . '%';
			$zhuanhualv = round(($daozhen / $zixun) * 100) . '%';
			$rows[] = array('riqi' => $arrDate[$i], 'zixun' => $zixun, 'yuyue' => $yuyue, 'daozhen' => $daozhen, 'ydaozhen' => $daozhen1, 'yuyuelv' => $yuyuelv, 'daozhenlv' => $daozhenlv, 'zhuanhualv' => $zhuanhualv);
		}

		$yuyuelv_z = round(($yuyue_heji / $zixun_heji) * 100) . '%';
		$daozhenlv_z = round(($daozhen_heji / $yuyue_heji) * 100) . '%';
		$zhuanhualv_z = round(($daozhen_heji / $zixun_heji) * 100) . '%';
		$rows_hj[] = array('riqi' => '按日期合计', 'zixun' => $zixun_heji, 'yuyue' => $yuyue_heji, 'daozhen' => $daozhen_heji, 'ydaozhen' => $ydaozhen_heji, 'yuyuelv' => $yuyuelv_z, 'daozhenlv' => $daozhenlv_z, 'zhuanhualv' => $zhuanhualv_z);
		$this->assign('list', array_reverse($rows));
		$this->assign('list1', $rows_hj);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);

		for ($i = 0; $i <= count($rows) - 1; $i++) {
			$tubiao_riqi .= '\'' . $rows[$i]['riqi'] . '\',';
			$tubiao_zixun .= $rows[$i]['zixun'] . ',';
			$tubiao_yuyue .= $rows[$i]['yuyue'] . ',';
			$tubiao_daozhen .= $rows[$i]['daozhen'] . ',';
		}

		$tubiao_riqi = rtrim($tubiao_riqi, ',');
		$tubiao_zixun = rtrim($tubiao_zixun, ',');
		$tubiao_yuyue = rtrim($tubiao_yuyue, ',');
		$tubiao_daozhen = rtrim($tubiao_daozhen, ',');
		$this->assign('tubiao_riqi', $tubiao_riqi);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('tubiao_zixunhj', $rows_hj[0]['zixun']);
		$this->assign('tubiao_yuyuehj', $rows_hj[0]['yuyue']);
		$this->assign('tubiao_daozhenhj', $rows_hj[0]['daozhen']);
		$today = date('Y-m-d');
		$yestoday = date('Y-m-d', strtotime('-1 day'));
		$benyue = date('Y-m');
		$timestamp = strtotime($today);
		$shangyueFirstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
		$shangyueLastday = last_month_today($timestamp);
		$shangyueLastday = substr($shangyueLastday, 0, 10);
		$shengyutian = date('t', time()) - date('d');
		$benyueFirstday = date('Y-m-01');
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$this->assign('shangyueFirstday', $shangyueFirstday);
		$this->assign('shangyueLastday', $shangyueLastday);
		$this->assign('benyueFirstday', $benyueFirstday);
		$this->assign('tomorrow', $tomorrow);
		$this->assign('shengyutian', $shengyutian);
		$sql = 'SELECT user_ID,userchinaname FROM oa_useradmin WHERE find_in_set(' . $zixunyuan1 . ', yy_ID) and role_ID=5 and  user_del = \'0\'';
		$user = m('Useradmin');
		$userid = session('username_lf');
		$yy_ID = $user->where('user_ID=' . $userid . '')->getField('yy_ID');
		$yyarr = explode(',', $yy_ID);
		$yyname = m('yiyuan');
		$useryiyuan = $yyname->where('yy_del=0')->getField('yy_name,yy_ID');
		$useryiyuan = array_values($useryiyuan);

		foreach ($yyarr as $k => $v ) {
			if (!in_array($v, $useryiyuan)) {
				unset($yyarr[$k]);
			}
		}

		$yyarr = array_values($yyarr);

		for ($i = 0; $i <= count($yyarr) - 1; $i++) {
			$yychianname = $yyname->where('yy_ID=' . $yyarr[$i] . ' and  yy_del=0')->getField('yy_name');
			$yydaozhenmubiao = $yyname->where('yy_ID=' . $yyarr[$i] . ' and  yy_del=0')->getField('yy_daozhenmubiao');
			$today_zixun1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   zx_time  >= \'' . $today . ' 00:00:00\' and zx_time <= \'' . $today . ' 23:59:59\' ';
			$today_zixun2 = mysql_query($today_zixun1);

			while ($tzx = mysql_fetch_array($today_zixun2)) {
				$today_zixun = $tzx['total'];
			}

			$today_yuyue1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   zx_time  >= \'' . $today . ' 00:00:00\' and zx_time <= \'' . $today . ' 23:59:59\' and shifouyuyue=0';
			$today_yuyue2 = mysql_query($today_yuyue1);

			while ($tzx = mysql_fetch_array($today_yuyue2)) {
				$today_yuyue = $tzx['total'];
			}

			$today_daozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   daozhen_time  >= \'' . $today . ' 00:00:00\' and daozhen_time <= \'' . $today . ' 23:59:59\' and shifoudaozhen=0';
			$today_daozhen2 = mysql_query($today_daozhen1);

			while ($tzx = mysql_fetch_array($today_daozhen2)) {
				$today_daozhen = $tzx['total'];
			}

			$today_ydaozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   yuyueTime  >= \'' . $today . ' 00:00:00\' and yuyueTime <= \'' . $today . ' 23:59:59\' and shifouyuyue=0';
			$today_ydaozhen2 = mysql_query($today_ydaozhen1);

			while ($tzx = mysql_fetch_array($today_ydaozhen2)) {
				$today_ydaozhen = $tzx['total'];
			}

			$yestoday_zixun1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   zx_time  >= \'' . $yestoday . ' 00:00:00\' and zx_time <= \'' . $yestoday . ' 23:59:59\' ';
			$yestoday_zixun2 = mysql_query($yestoday_zixun1);

			while ($tzx = mysql_fetch_array($yestoday_zixun2)) {
				$yestoday_zixun = $tzx['total'];
			}

			$yestoday_yuyue1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   zx_time  >= \'' . $yestoday . ' 00:00:00\' and zx_time <= \'' . $yestoday . ' 23:59:59\' and shifouyuyue=0';
			$yestoday_yuyue2 = mysql_query($yestoday_yuyue1);

			while ($tzx = mysql_fetch_array($yestoday_yuyue2)) {
				$yestoday_yuyue = $tzx['total'];
			}

			$yestoday_daozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and  daozhen_time  >= \'' . $yestoday . ' 00:00:00\' and daozhen_time <= \'' . $yestoday . ' 23:59:59\' and shifoudaozhen=0';
			$yestoday_daozhen2 = mysql_query($yestoday_daozhen1);

			while ($tzx = mysql_fetch_array($yestoday_daozhen2)) {
				$yestoday_daozhen = $tzx['total'];
			}

			$yestoday_ydaozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and   yuyueTime  >= \'' . $yestoday . ' 00:00:00\' and yuyueTime <= \'' . $yestoday . ' 23:59:59\' and shifouyuyue=0';
			$yestoday_ydaozhen2 = mysql_query($yestoday_ydaozhen1);

			while ($tzx = mysql_fetch_array($yestoday_ydaozhen2)) {
				$yestoday_ydaozhen = $tzx['total'];
			}

			$benyue_zixun1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and  DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $benyue . '\'';
			$benyue_zixun2 = mysql_query($benyue_zixun1);

			while ($tzx = mysql_fetch_array($benyue_zixun2)) {
				$benyue_zixun = $tzx['total'];
			}

			$benyue_yuyue1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and shifouyuyue=0 and DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $benyue . '\'';
			$benyue_yuyue2 = mysql_query($benyue_yuyue1);

			while ($tzx = mysql_fetch_array($benyue_yuyue2)) {
				$benyue_yuyue = $tzx['total'];
			}

			$benyue_daozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and shifoudaozhen=0 and  DATE_FORMAT(daozhen_time,\'%Y-%m\') =\'' . $benyue . '\'';
			$benyue_daozhen2 = mysql_query($benyue_daozhen1);

			while ($tzx = mysql_fetch_array($benyue_daozhen2)) {
				$benyue_daozhen = $tzx['total'];
			}

			$benyue_ydaozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '				  where yy_ID=' . $yyarr[$i] . ' and yuyueTime  >= \'' . $benyueFirstday . ' 00:00:00\' and yuyueTime <= \'' . $today . ' 23:59:59\' and shifouyuyue=0';
			$benyue_ydaozhen2 = mysql_query($benyue_ydaozhen1);

			while ($tzx = mysql_fetch_array($benyue_ydaozhen2)) {
				$benyue_ydaozhen = $tzx['total'];
			}

			$shangyue_zixun1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where yy_ID=' . $yyarr[$i] . ' and zx_time  >= \'' . $shangyueFirstday . ' 00:00:00\' and zx_time <= \'' . $shangyueLastday . ' 23:59:59\'';
			$shangyue_zixun2 = mysql_query($shangyue_zixun1);

			while ($tzx = mysql_fetch_array($shangyue_zixun2)) {
				$shangyue_zixun = $tzx['total'];
			}

			$shangyue_yuyue1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where yy_ID=' . $yyarr[$i] . ' and zx_time  >= \'' . $shangyueFirstday . ' 00:00:00\' and zx_time <= \'' . $shangyueLastday . ' 23:59:59\' and shifouyuyue=0';
			$shangyue_yuyue2 = mysql_query($shangyue_yuyue1);

			while ($tzx = mysql_fetch_array($shangyue_yuyue2)) {
				$shangyue_yuyue = $tzx['total'];
			}

			$shangyue_daozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where yy_ID=' . $yyarr[$i] . ' and daozhen_time  >= \'' . $shangyueFirstday . ' 00:00:00\' and daozhen_time <= \'' . $shangyueLastday . ' 23:59:59\' and shifoudaozhen=0';
			$shangyue_daozhen2 = mysql_query($shangyue_daozhen1);

			while ($tzx = mysql_fetch_array($shangyue_daozhen2)) {
				$shangyue_daozhen = $tzx['total'];
			}

			$shangyue_ydaozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where yy_ID=' . $yyarr[$i] . ' and yuyueTime  >= \'' . $shangyueFirstday . ' 00:00:00\' and yuyueTime <= \'' . $shangyueLastday . ' 23:59:59\' and shifouyuyue=0';
			$shangyue_ydaozhen2 = mysql_query($shangyue_ydaozhen1);

			while ($tzx = mysql_fetch_array($shangyue_ydaozhen2)) {
				$shangyue_ydaozhen = $tzx['total'];
			}

			$mingri_ydaozhen1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where yy_ID=' . $yyarr[$i] . ' and yuyueTime  >= \'' . $tomorrow . ' 00:00:00\' and yuyueTime <= \'' . $tomorrow . ' 23:59:59\' and shifouyuyue=0';
			$mingri_ydaozhen2 = mysql_query($mingri_ydaozhen1);

			while ($tzx = mysql_fetch_array($mingri_ydaozhen2)) {
				$mingri_ydaozhen = $tzx['total'];
			}

			$yuehuanbi = $benyue_daozhen - $shangyue_daozhen;

			if (0 < $yuehuanbi) {
				$yuehuanbi = '<font color="red"><i class="icon icon-long-arrow-up"></i>+' . $yuehuanbi . '</font>';
			}
			else if ($yuehuanbi < 0) {
				$yuehuanbi = '<font color="#00AB0E"><i class="icon icon-long-arrow-down"></i>' . $yuehuanbi . '</font>';
			}
			else {
				$yuehuanbi = '持平 ';
			}

			$benyuedaozhenlv = round(($benyue_daozhen / $benyue_yuyue) * 100) . '%';
			$shangyeudaozhenlv = round(($shangyue_daozhen / $shangyue_yuyue) * 100) . '%';
			$rijundaozhen = round($benyue_daozhen / $tianshu, 2);
			$xuyaodao = $yydaozhenmubiao - $benyue_daozhen;
			$haixudao = round($xuyaodao / $shengyutian, 2);
			$wanchenglv = round($benyue_daozhen / $yydaozhenmubiao, 4) * 100;
			$wanchenglvv = $wanchenglv;

			if ($wanchenglv < 60) {
				$jindutiao = 'danger';
			}
			else if (99 < $wanchenglv) {
				$jindutiao = 'info';
				$wanchenglvv = 100;
			}
			else {
				$jindutiao = 'warning';
			}

			$yujidaozhen = ($rijundaozhen * $shengyutian) + $benyue_daozhen;
			$jianbao[] = array('yyname' => $yychianname, 'today_zixun' => $today_zixun, 'today_yuyue' => $today_yuyue, 'today_daozhen' => $today_daozhen, 'today_ydaozhen' => $today_ydaozhen, 'yestoday_zixun' => $yestoday_zixun, 'yestoday_yuyue' => $yestoday_yuyue, 'yestoday_daozhen' => $yestoday_daozhen, 'yestoday_ydaozhen' => $yestoday_ydaozhen, 'benyue_zixun' => $benyue_zixun, 'benyue_yuyue' => $benyue_yuyue, 'benyue_daozhen' => $benyue_daozhen, 'benyue_ydaozhen' => $benyue_ydaozhen, 'shangyue_zixun' => $shangyue_zixun, 'shangyue_yuyue' => $shangyue_yuyue, 'shangyue_daozhen' => $shangyue_daozhen, 'shangyue_ydaozhen' => $shangyue_ydaozhen, 'yy_ID' => $yyarr[$i], 'mingri_ydaozhen' => $mingri_ydaozhen, 'yuehuanbi' => $yuehuanbi, 'benyuedaozhenlv' => $benyuedaozhenlv, 'rijundaozhen' => $rijundaozhen, 'daozhenmubiao' => $yydaozhenmubiao, 'shengyurijun' => $haixudao, 'xuyaodao' => $xuyaodao, 'wanchenglv' => $wanchenglv, 'wanchenglvv' => $wanchenglvv, 'jindutiao' => $jindutiao, 'yujidaozhen' => round($yujidaozhen));
		}

		for ($i = 0; $i <= count($jianbao) - 1; $i++) {
			$jinriyudaozhen = $jinriyudaozhen + $jianbao[$i]['today_ydaozhen'];
			$jinriyidaozhen = $jinriyidaozhen + $jianbao[$i]['today_daozhen'];
			$jinriyuyue = $jinriyuyue + $jianbao[$i]['today_yuyue'];
			$jinrizixun = $jinrizixun + $jianbao[$i]['today_zixun'];
		}

		$this->assign('jinriyudaozhen', $jinriyudaozhen);
		$this->assign('jinriyidaozhen', $jinriyidaozhen);
		$this->assign('jinriyuyue', $jinriyuyue);
		$this->assign('jinrizixun', $jinrizixun);
		$this->assign('jianbao', $jianbao);
		$this->assign('yyarr', count($yyarr));
		$hfjh = m('huifangjihua');
		$huanzehuifang = m('huanzehuifang');
		$user = m('useradmin');
		$suoshuyy = $user->where('user_ID = \'' . session('username_lf') . '\'')->getField('yy_ID');

		if (session('user_role') == 5) {
			$sql = 'select * from oa_huifangjihua where ' . "\r\n" . '	   user_ID=' . session('username_lf') . ' and hfjh_time between \'' . $yestoday . ' 00:00:00\' and \'' . $today . ' 23:59:59\' order by hfjh_time desc';
		}
		else {
			$sql = 'select * from oa_huifangjihua where ' . "\r\n" . '	    hfjh_time between \'' . $yestoday . ' 00:00:00\' and \'' . $today . ' 23:59:59\' and yy_ID in(' . $suoshuyy . ') order by hfjh_time desc';
		}

		$hfjhArr = $hfjh->query($sql);

		if (count($hfjhArr) == 0) {
			$this->assign('nodata', 'nodata');
		}

		for ($i = 0; $i <= count($hfjhArr) - 1; $i++) {
			$schfsj = $huanzehuifang->where('zx_ID=' . $hfjhArr[$i]['zx_ID'] . '')->order('hf_time desc')->getField('hf_time');
			$xinxi = $huanzehuifang->query('select huifangcishu,yy_ID,bz_ID,userID from oa_huanze where zx_ID=' . $hfjhArr[$i]['zx_ID'] . '');
			$huanzeinfo = $huanzehuifang->query('select shouji,huanzeName from oa_huanzeyuyue where zx_ID=' . $hfjhArr[$i]['zx_ID'] . '');
			$yy_name = $huanzehuifang->query('select yy_name from oa_yiyuan where yy_ID=' . $xinxi[0]['yy_ID'] . '');
			$bz_name = $huanzehuifang->query('select bz_name from oa_bingzhong where ID=' . $xinxi[0]['bz_ID'] . '');
			$userchinaname = $huanzehuifang->query('select userchinaname from oa_useradmin where user_ID=' . $xinxi[0]['userID'] . '');

			if ($hfjhArr[$i]['zxhf_ID'] == '') {
				$shifou = '<span class=\'badge badge-warning\'>待回访</span>';
				$yanshi = '1';
			}
			else {
				$shifou = '<span class=\'badge badge-primary\'>已回访</span>';
				$yanshi = '0';
			}

			$hftx[] = array('schfsj' => $schfsj, 'xingming' => $huanzeinfo[0]['huanzeName'], 'shouji' => $huanzeinfo[0]['shouji'], 'yy_name' => $yy_name[0]['yy_name'], 'bz_name' => $bz_name[0]['bz_name'], 'hfjh_zhuti' => $hfjhArr[$i]['hfjh_zhuti'], 'hfjh_time' => $hfjhArr[$i]['hfjh_time'], 'userchinaname' => $userchinaname['0']['userchinaname'], 'shifou' => $shifou, 'hfjh_ID' => $hfjhArr[$i]['hfjh_ID'], 'yanshi' => $yanshi, 'zx_ID' => $hfjhArr[$i]['zx_ID'], 'hfjh_ID' => $hfjhArr[$i]['hfjh_ID'], 'huifangcishu' => $xinxi[0]['huifangcishu'], 'zxhf_ID' => $hfjhArr[$i]['zxhf_ID']);
		}

		$this->assign('hfjharr', $hftx);
		$this->display();
	}

	public function homedel()
	{
		$dirs = array('./Runtime/');

		foreach ($dirs as $value ) {
			$this->rmdirr($value);
		}

		js_alert('', '已删除缓存文件');
	}

	public function rmdirr($dirname)
	{
		if (!file_exists($dirname)) {
			return false;
		}

		if (is_file($dirname) || is_link($dirname)) {
			return unlink($dirname);
		}

		$dir = dir($dirname);

		if ($dir) {
			while (false !== $entry = $dir->read()) {
				if (($entry == '.') || ($entry == '..')) {
					continue;
				}

				$this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
			}
		}

		$dir->close();
		return rmdir($dirname);
	}

	public function UserStop()
	{
		$this->display();
	}
}


?>
