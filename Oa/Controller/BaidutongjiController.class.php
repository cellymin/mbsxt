<?php

namespace OA\Controller;
use PHPExcel_IOFactory;



class BaidutongjiController extends \Component\AdminController
{
	public function baiduzhanghu_yiyuan($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$yyidinfo = m('useradmin');
		$yyid = $yyidinfo->query('select yy_ID from oa_useradmin where user_ID=\'' . session('username_lf') . '\'');

		if (!empty($yy_ID)) {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and yy_ID=' . $yy_ID . ' order by yy_ID');
		}
		else {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and yy_ID in(' . $yyid[0]['yy_ID'] . ') order by yy_ID');
		}

		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();

		for ($i = 0; $i <= count($zhanghuInfo) - 1; $i++) {
			$newheader->setUsername($zhanghuInfo[$i]['zhanghuming']);
			$newheader->setPassword(base64_decode($zhanghuInfo[$i]['zhanghumima']));
			$newheader->setToken($zhanghuInfo[$i]['zhanghutoken']);
			$testService->setAuthHeader($newheader);
			$testzhanghu = $testService->getRealTimeDataTest($StartDate, $setEndDate, $setdevice, $levelOfDetails, $setUnitOfTime);

			if (!empty($testzhanghu)) {
				$datas[$i] = $testzhanghu;
			}
			else {
				$errorzhanghu .= $zhanghuInfo[$i]['zhanghuming'] . ',';
			}
		}

		$datas = array_values($datas);

		for ($i = 0; $i <= count($datas) - 1; $i++) {
			$zhanghuarray[$i] = array('zhanxian' => $datas[$i][0]->kpis[0], 'dianji' => $datas[$i][0]->kpis[1], 'xiaofei' => $datas[$i][0]->kpis[3], 'riqi' => $datas[$i][0]->date, 'dianjidanjia' => round($datas[$i][0]->kpis[4], 2), 'jihuaName' => $datas[$i][0]->name[1], 'zhanghuName' => $datas[$i][0]->name[0]);
		}

		return $zhanghuarray;
	}

	public function baiduzhanghu_fenriqi($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		if (!empty($yy_ID) && ($zhanghuID == '')) {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and yy_ID=' . $yy_ID . ' order by yy_ID');
		}
		else if ($zhanghuID != '') {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		}

		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();

		for ($i = 0; $i <= count($zhanghuInfo) - 1; $i++) {
			$newheader->setUsername($zhanghuInfo[$i]['zhanghuming']);
			$newheader->setPassword(base64_decode($zhanghuInfo[$i]['zhanghumima']));
			$newheader->setToken($zhanghuInfo[$i]['zhanghutoken']);
			$testService->setAuthHeader($newheader);
			$testzhanghu = $testService->getRealTimeDataTest($StartDate, $setEndDate, $setdevice, $levelOfDetails, $setUnitOfTime);

			if (!empty($testzhanghu)) {
				$datas[$i] = $testzhanghu;
			}
			else {
				$errorzhanghu .= $zhanghuInfo[$i]['zhanghuming'] . ',';
			}
		}

		$datas = array_values($datas);

		for ($i = 0; $i <= count($datas) - 1; $i++) {
			for ($y = 0; $y <= count($datas[$i]) - 1; $y++) {
				$zhanghuarray[$i][$y] = array('zhanxian' => $datas[$i][$y]->kpis[0], 'dianji' => $datas[$i][$y]->kpis[1], 'xiaofei' => $datas[$i][$y]->kpis[3], 'riqi' => $datas[$i][$y]->date, 'dianjidanjia' => round($datas[$i][$y]->kpis[4], 2), 'zhanghuName' => $datas[$i][$y]->name[0], 'jihuaName' => $datas[$i][$y]->name[1]);
			}
		}

		return $zhanghuarray;
	}

	public function baiduzhanghu_shijianduan($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		if (!empty($yy_ID) && ($zhanghuID == '')) {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and yy_ID=' . $yy_ID . ' order by yy_ID');
		}
		else if ($zhanghuID != '') {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		}
		else {
			$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 order by yy_ID');
		}

		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();

		for ($i = 0; $i <= count($zhanghuInfo) - 1; $i++) {
			$newheader->setUsername($zhanghuInfo[$i]['zhanghuming']);
			$newheader->setPassword(base64_decode($zhanghuInfo[$i]['zhanghumima']));
			$newheader->setToken($zhanghuInfo[$i]['zhanghutoken']);
			$testService->setAuthHeader($newheader);
			$testzhanghu = $testService->getRealTimeDataTest($StartDate, $setEndDate, $setdevice, $levelOfDetails, $setUnitOfTime);

			if (!empty($testzhanghu)) {
				$datas[$i] = $testzhanghu;
			}
			else {
				$errorzhanghu .= $zhanghuInfo[$i]['zhanghuming'] . ',';
			}
		}

		$datas = array_values($datas);

		for ($i = 0; $i <= count($datas) - 1; $i++) {
			for ($y = 0; $y <= count($datas[$i]) - 1; $y++) {
				$zhanghuarray[$i][$y] = array('zhanxian' => $datas[$i][$y]->kpis[0], 'dianji' => $datas[$i][$y]->kpis[1], 'xiaofei' => $datas[$i][$y]->kpis[3], 'riqi' => $datas[$i][$y]->date, 'dianjidanjia' => round($datas[$i][$y]->kpis[4], 2), 'zhanghuName' => $datas[$i][$y]->name[0]);
			}
		}

		return $zhanghuarray;
	}

	public function baiduzhanghu_sousuoci($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->getRealTimeQueryDataTest($StartDate, $setEndDate, $setdevice, $levelOfDetails, $setUnitOfTime);
		$datasheji = count($datas);

		for ($i = 0; $i < $datasheji; $i++) {
			$zhanghuarray[] = array('zhanxian' => $datas[$i]->kpis[0], 'dianji' => $datas[$i]->kpis[1], 'pipeici' => $datas[$i]->queryInfo[3], 'riqi' => $datas[$i]->date, 'sousuoci' => $datas[$i]->query, 'zhanghuName' => $datas[$i]->queryInfo[0]);
		}

		return $zhanghuarray;
	}

	public function baiduzhanghu_guanjianci($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->getRealTimePairDataTest($StartDate, $setEndDate, $setdevice, $levelOfDetails, $setUnitOfTime);
		$datesheji = count($datas);

		for ($i = 0; $i < $datesheji; $i++) {
			$zhanghuarray[$i] = array('zhanxian' => $datas[$i]->kpis[0], 'dianji' => $datas[$i]->kpis[1], 'xiaofei' => $datas[$i]->kpis[2], 'danjia' => round($datas[$i]->kpis[3], 2), 'pipeici' => $datas[$i]->pairInfo[3], 'riqi' => $datas[$i]->date, 'zhanghuName' => $datas[$i]->pairInfo[0], 'jihuaName' => $datas[$i]->pairInfo[1]);
		}

		return $zhanghuarray;
	}

	public function yibubaogao_ID($StartDate, $setEndDate, $arrfanhui, $LevelOfDetails, $ReportType, $setDevice, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->getProfessionalReportIdTest($StartDate, $setEndDate, $arrfanhui, $LevelOfDetails, $ReportType, $setDevice);
		$reportId = (string) $datas[0]->reportId;
		return $reportId;
	}

	public function yibubaogao_ID_jh($StartDate, $setEndDate, $arrfanhui, $LevelOfDetails, $ReportType, $setDevice, $zhanghuID, $unitOfTime = NULL)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->getProfessionalReportIdTest($StartDate, $setEndDate, $arrfanhui, $LevelOfDetails, $ReportType, $setDevice, $unitOfTime);
		$reportId = (string) $datas[0]->reportId;
		return $reportId;
	}

	public function getReportUrl($reportId, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->getReportFileUrlTest($reportId);
		$reportUrl = $datas[0]->reportFilePath;
		return $reportUrl;
	}

	public function getReportState($reportId, $zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new \Component\ReportServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->getReportStateTest($reportId);
		$state = $datas[0]->isGenerated;
		return $state;
	}

	public function baiduliandong()
	{
		$yyid = htmlspecialchars(trim($_POST['yy_ID']));

		if (!empty($yyid)) {
			$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $yyid . ' and zhanghudel=0 order by yy_ID desc';
			$data = m('baiduzhanghu');
			$syid1 = mysql_query($sql);
			echo '<option value=\'\'>所有账户</option>';

			while ($syid = mysql_fetch_array($syid1)) {
				echo '<option value=' . $syid['zhanghu_ID'] . '>' . $syid['zhanghuming'] . '</option>';
			}
		}
		else {
			echo '<option value=\'\'>请先选择门店</option>';
		}
	}

	public function report_baiduzong()
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

		if (8035200 < ($endtime - $starttime)) {
			js_alert('', '日期不支持超过三个月');
			exit();
		}

		$user = m('useradmin');
		$data = $user->where('user_ID = \'' . session('username_lf') . '\'')->find();
		$yyarr = explode(',', $data['yy_ID']);
		$yyname = m('yiyuan');
		$ssyy = array();

		for ($i = 0; $i <= count($yyarr) - 1; $i++) {
			$ssyy1 = array('yyid' => $yyarr[$i], 'yyname' => $yyname->where('yy_ID=' . $yyarr[$i] . ' and  yy_del=0')->getField('yy_name'));

			if ($ssyy1['yyname'] != '') {
				array_push($ssyy, $ssyy1);
			}
		}

		$this->assign('countyy', count($ssyy));
		$this->assign('ssyy', $ssyy);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$canshu['zx_timeStart2'] = $canshu['zx_timeStart'];
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$canshu['zx_timeEnd2'] = $canshu['zx_timeEnd'];
		}

		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);

		if (!empty($canshu['submit1'])) {
			$user = m('useradmin');
			$data = $user->where('user_ID = \'' . session('username_lf') . '\'')->find();
			$yyarr = explode(',', $data['yy_ID']);
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setdevice = $canshu['zhanghushebei'];
			$levelOfDetails = 2;
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 8;
			$tiaojian = $this->baiduzhanghu_yiyuan($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime);

			for ($i = 0; $i <= count($tiaojian) - 1; $i++) {
				if ($tiaojian[$i]['zhanxian'] == '') {
					unset($tiaojian[$i]);
					break;
				}

				$zhanghu = m('baiduzhanghu');
				$zhanghuInfo_yyID = $zhanghu->where('zhanghuming=\'' . $tiaojian[$i]['zhanghuName'] . '\'')->getField('yy_ID');
				$yyname = m('yiyuan');
				$yynameInfo = $yyname->where('yy_ID=' . $zhanghuInfo_yyID . '')->getField('yy_name');
				$tiaojian[$i]['yyname'] = $yynameInfo;
				$diff = diffbetweentwodays($canshu['zx_timeStart'], $canshu['zx_timeEnd']);
				$tiaojian[$i]['rijunzhanxian'] = round($tiaojian[$i]['zhanxian'] / $diff, 0);
				$tiaojian[$i]['rijundianji'] = round($tiaojian[$i]['dianji'] / $diff, 0);
				$tiaojian[$i]['rijunxiaofei'] = round($tiaojian[$i]['xiaofei'] / $diff, 2);
				$zhanxianheji = $zhanxianheji + $tiaojian[$i]['zhanxian'];
				$xiaofeiheji = $xiaofeiheji + $tiaojian[$i]['xiaofei'];
				$dianjiheji = $dianjiheji + $tiaojian[$i]['dianji'];

				if (empty($tiaojian[$i]['zhanxian'])) {
					unset($tiaojian[$i]);
				}
			}

			$this->assign('dqyyID', $canshu['yy_ID']);

			if ($canshu['zhanghushebei'] == 0) {
				$this->assign('selectd1', 'selected');
				$this->assign('duankou', '全部设备');
			}

			if ($canshu['zhanghushebei'] == 1) {
				$this->assign('selectd2', 'selected');
				$this->assign('duankou', 'PC端');
			}

			if ($canshu['zhanghushebei'] == 2) {
				$this->assign('selectd3', 'selected');
				$this->assign('duankou', '手机端');
			}

			$tiaojianheji = array();
			$tiaojianheji[0]['zhanxian'] = $zhanxianheji;
			$tiaojianheji[0]['xiaofei'] = $xiaofeiheji;
			$tiaojianheji[0]['dianji'] = $dianjiheji;
			$this->assign('list1', $tiaojianheji);
			$this->assign('list', $tiaojian);
		}

		$this->display();
	}

	public function report_oneline()
	{
		$canshu = i('request.');

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
			}
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$canshu['zx_timeStart2'] = $canshu['zx_timeStart'];
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$canshu['zx_timeEnd2'] = $canshu['zx_timeEnd'];
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd_shebeipc', 'selected');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd_shebeiyd', 'selected');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['submit1'] != '') {
			$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
			$zhanghu = m('baiduzhanghu');
			$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
			$this->assign('zhanghurow', $zhanghurow);
			$this->assign('zhanghuID', $canshu['zhanghuID']);
			if (($canshu['tian_geshi'] == '天') || ($canshu['tian_geshi'] == '')) {
				if (8035200 < ($endtime - $starttime)) {
					js_alert('', '日期不支持超过三个月');
					exit();
				}

				$this->assign('active_tian', 'active');
				$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

				if (!empty($canshu['yy_ID'])) {
					$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
					$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
					$setdevice = $canshu['zhanghushebei'];
					$levelOfDetails = 2;
					$yy_ID = $canshu['yy_ID'];
					$setUnitOfTime = 5;
					$zhanghuID = $canshu['zhanghuID'];
					$baidufanhui = $this->baiduzhanghu_fenriqi($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);

					for ($d = 0; $d <= count($arrDate) - 1; $d++) {
						$baiduheji = 0;
						$baidudianji = 0;
						$baiduzhanxian1 = 0;

						for ($i = 0; $i <= count($baidufanhui) - 1; $i++) {
							for ($y = 0; $y <= count($baidufanhui[$i]) - 1; $y++) {
								if ($baidufanhui[$i][$y]['riqi'] == $arrDate[$d]) {
									$baiduheji = $baiduheji + $baidufanhui[$i][$y]['xiaofei'];
									$baidudianji = $baidudianji + $baidufanhui[$i][$y]['dianji'];
									$baiduzhanxian1 = $baiduzhanxian1 + $baidufanhui[$i][$y]['zhanxian'];
									break;
								}
							}
						}

						$baidu[$arrDate[$d]] = $baiduheji;
						$baiduclick[$arrDate[$d]] = $baidudianji;
						$baiduzhanxian[$arrDate[$d]] = $baiduzhanxian1;
						unset($baiduheji);
						unset($baidudianji);
						unset($baiduzhanxian1);
					}
				}

				if ($canshu['zixunlaiyuan'] == 0) {
					if (($canshu['swtsj'] == 1) && empty($canshu['zhanghuID'])) {
						$tiaojian_zhanghu = '';
						$this->assign('selectd_swtzong', 'selected');
						$this->assign('sm_zxly', '说明：<font color=red>所有账户-商务通导入-商务通总数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入的总数据，包含自然搜索;没有做任何账户、推广端(PC.WAP)的区分；<br>' . "\r\n" . '						 <font color=red>预约量</font> 来源为商务通导入的总数据的预约量,包含自然搜索;<br>' . "\r\n" . '						 <font color=red>到诊量</font> 来源为商务通导入的总数据的到诊量,包含自然搜索;<br>' . "\r\n" . '							如果选中PC 或移动设备，咨询预约到诊量只包含纯竞价数据');
					}
					else {
						if (($canshu['swtsj'] == 0) && empty($canshu['zhanghuID'])) {
							$jingjiatongpeifu = $zhanghu->where('yy_ID=' . $canshu['yy_ID'] . '')->getField('jingjiatongpeifu');
							$tiaojian_zhanghu = 'and locate(\'' . $jingjiatongpeifu . '\',chucifangwenURL)';
							$this->assign('selectd_swtbaidu', 'selected');
							$this->assign('sm_zxly', '说明：<font color=red>所有账户-商务通导入-纯百度竞价咨询数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入包含竞价通配符（' . $jingjiatongpeifu . '）的纯百度竞价咨询;没有做任何账户的区分；<br>' . "\r\n" . '						 <font color=red>预约量</font>  来源为商务通导入包含竞价通配符（' . $jingjiatongpeifu . '）的预约量;<br>' . "\r\n" . '						 <font color=red>到诊量</font>  来源为商务通导入包含竞价通配符（' . $jingjiatongpeifu . '）的到诊量;<br>' . "\r\n" . '							如再选中PC 或移动设备，咨询预约到诊量只包含纯竞价PC或移动 数据');
						}
						else {
							if (($canshu['swtsj'] == 1) && !empty($canshu['zhanghuID'])) {
								$tuiguang_url = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('tuiguang_url');
								$tiaojian_zhanghu = 'and locate(\'' . $tuiguang_url . '\',chucifangwenURL)';
								$this->assign('selectd_swtzong', 'selected');
								$this->assign('sm_zxly', '说明：<font color=red>单一账户-商务通导入-商务通总数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入包含推广URL（' . $tuiguang_url . '）的商务通总数据;<br>' . "\r\n" . '						  <font color=red>预约量</font>  来源为商务通导入包含推广URL（' . $tuiguang_url . '）的预约量;<br>' . "\r\n" . '						  <font color=red>到诊量</font>  来源为商务通导入包含推广URL（' . $tuiguang_url . '）的到诊量;<br>' . "\r\n" . '							如再选中PC 或移动设备，咨询预约到诊量只包含纯竞价PC或移动 数据');
							}
							else {
								if (($canshu['swtsj'] == 0) && !empty($canshu['zhanghuID'])) {
									$weiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');
									$tiaojian_zhanghu = 'and locate(\'' . $weiyifu . '\',chucifangwenURL)';
									$this->assign('selectd_swtbaidu', 'selected');
									$this->assign('sm_zxly', '说明：<font color=red>单一账户-商务通导入-纯百度竞价咨询数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入包含推广URL（' . $weiyifu . '）的商务通总数据;<br>' . "\r\n" . '						  <font color=red>预约量</font>  来源为商务通导入包含推广URL（' . $weiyifu . '）的预约量;<br>' . "\r\n" . '						  <font color=red>到诊量</font>  来源为商务通导入包含推广URL（' . $weiyifu . '）的到诊量;<br>' . "\r\n" . '							如再选中PC 或移动设备，咨询预约到诊量只包含纯竞价PC或移动 数据');
								}
								else {
									echo 'error';
								}
							}
						}
					}

					if (($canshu['zhanghushebei'] == 1) && !empty($canshu['zhanghuID'])) {
						$diannaotongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . ' and zhanghudel=0')->getField('pcweiyifu');
						$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',chucifangwenURL) ';
						$this->assign('sm_pcorydcx', '<br>当前选中了计算机端,URL中还需包含PC端唯一符(' . $diannaotongpeifu . ')');
					}

					if (($canshu['zhanghushebei'] == 2) && !empty($canshu['zhanghuID'])) {
						$yidongweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . ' and zhanghudel=0')->getField('yidongweiyifu');
						$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',chucifangwenURL) ';
						$this->assign('sm_pcorydcx', '<br>当前选中了移动端,URL中还需包含PC端唯一符(' . $yidongweiyifu . ')');
					}

					if (($canshu['zhanghushebei'] == 1) && empty($canshu['zhanghuID'])) {
						$diannaotongpeifu = $zhanghu->where('zhanghudel=0')->getField('pcweiyifu');
						$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',chucifangwenURL) ';
						$this->assign('sm_pcorydcx', '<br>当前选中了计算机端,URL中还需包含PC端唯一符(' . $diannaotongpeifu . ')');
					}

					if (($canshu['zhanghushebei'] == 2) && empty($canshu['zhanghuID'])) {
						$yidongweiyifu = $zhanghu->where('zhanghudel=0')->getField('yidongweiyifu');
						$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',chucifangwenURL) ';
						$this->assign('sm_pcorydcx', '<br>当前选中了移动端,URL中还需包含PC端唯一符(' . $yidongweiyifu . ')');
					}

					$this->assign('selectd_swtdr', 'selected');
				}
				else {
					$this->assign('selectd_zxy', 'selected');
					$this->assign('sm_zxly', ' 说明：<font color=red>咨询员录入模式下：咨询、预约、到诊量</font>均来自咨询员录入的所有数据；1.没有区分PC和移动端 2.无法区分百度账户来源;<br>可根据查询条件自行筛选；匹配自己需要的报表;');
				}

				for ($i = 0; $i <= count($arrDate) - 1; $i++) {
					if ($canshu['zixunlaiyuan'] == 0) {
						$zixunliangSql = 'select count(swtID) as total from oa_swtdaoru' . "\r\n" . '								  where  yy_ID=' . $canshu['yy_ID'] . ' and zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' ' . $tiaojian_zhanghu . '';
						$yuyueliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . '	 where b.yy_ID=' . $canshu['yy_ID'] . ' and a.zx_time>=\'' . $arrDate[$i] . ' 00:00:00\' and a.zx_time<=\'' . $arrDate[$i] . ' 23:59:59\' and b.shifouyuyue = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . '';
						$daozhenliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . '	 where b.yy_ID=' . $canshu['yy_ID'] . ' and b.daozhen_time>=\'' . $arrDate[$i] . ' 00:00:00\' and b.daozhen_time<=\'' . $arrDate[$i] . ' 23:59:59\' and b.shifoudaozhen = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . '';
					}
					else {
						$zixunliangSql = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '							  where zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' ' . $tiaojian . '';
						$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '							  where zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' and shifouyuyue=0' . $tiaojian . '';
						$daozhenliangSql = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '							  where daozhen_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and daozhen_time <= \'' . $arrDate[$i] . ' 23:59:59\' and shifoudaozhen=0' . $tiaojian . '';
					}

					$zixunliang = mysql_query($zixunliangSql);

					while ($aaa = mysql_fetch_array($zixunliang)) {
						$zixun = $aaa['total'];
					}

					$zixun_heji = $zixun_heji + $zixun;
					$yuyueliang = mysql_query($yuyueliangSql);

					while ($aaa = mysql_fetch_array($yuyueliang)) {
						$yuyue = $aaa['total'];
					}

					$yuyue_heji = $yuyue_heji + $yuyue;
					$daozhenliang = mysql_query($daozhenliangSql);

					while ($aaa = mysql_fetch_array($daozhenliang)) {
						$daozhen = $aaa['total'];
					}

					$daozhen_heji = $daozhen_heji + $daozhen;
					$daozhenliangSql1 = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '			  where yuyueTime  >= \'' . $arrDate[$i] . ' 00:00:00\' and yuyueTime <= \'' . $arrDate[$i] . ' 23:59:59\' and shifouyuyue=0' . $tiaojian . '';
					$daozhenliang1 = mysql_query($daozhenliangSql1);

					while ($aaa = mysql_fetch_array($daozhenliang1)) {
						$daozhen1 = $aaa['total'];
					}

					$ydaozhen_heji = $ydaozhen_heji + $daozhen1;
					$yuyuelv = round(($yuyue / $zixun) * 100) . '%';
					$daozhenlv = round(($daozhen / $yuyue) * 100) . '%';
					$zhuanhualv = round(($daozhen / $zixun) * 100) . '%';
					$zixunchengben = round($baidu[$arrDate[$i]] / $zixun, 2);
					$yuyuechengben = round($baidu[$arrDate[$i]] / $yuyue, 2);
					$daozhenchengben = round($baidu[$arrDate[$i]] / $daozhen, 2);
					$baiduxiaofeiheji = $baiduxiaofeiheji + $baidu[$arrDate[$i]];
					$baidudianjiheji = $baidudianjiheji + $baiduclick[$arrDate[$i]];
					$baiduzhanxianheji = $baiduzhanxianheji + $baiduzhanxian[$arrDate[$i]];
					$dianjizixun1 = round($zixun / $baiduclick[$arrDate[$i]], 4);
					$dianjizixunlv = ($dianjizixun1 * 100) . '%';
					$dianjilv1 = round($baiduclick[$arrDate[$i]] / $baiduzhanxian[$arrDate[$i]], 4);
					$dianjilv = ($dianjilv1 * 100) . '%';
					$rows[] = array('riqi' => $arrDate[$i], 'zixun' => $zixun, 'yuyue' => $yuyue, 'daozhen' => $daozhen, 'ydaozhen' => $daozhen1, 'yuyuelv' => $yuyuelv, 'daozhenlv' => $daozhenlv, 'zhuanhualv' => $zhuanhualv, 'zhanxian' => $baiduzhanxian[$arrDate[$i]], 'dianji' => $baiduclick[$arrDate[$i]], 'dianjilv' => $dianjilv, 'xiaofei' => $baidu[$arrDate[$i]], 'zixunchengben' => $zixunchengben, 'yuyuechengben' => $yuyuechengben, 'daozhenchengben' => $daozhenchengben, 'dianjizixunlv' => $dianjizixunlv);
				}

				$yuyuelv_z = round(($yuyue_heji / $zixun_heji) * 100) . '%';
				$daozhenlv_z = round(($daozhen_heji / $yuyue_heji) * 100) . '%';
				$zhuanhualv_z = round(($daozhen_heji / $zixun_heji) * 100) . '%';
				$daozhenchengbenheji = round($baiduxiaofeiheji / $daozhen_heji, 2);
				$zixunchengbenheji = round($baiduxiaofeiheji / $zixun_heji, 2);
				$yuyuechengbenheji = round($baiduxiaofeiheji / $yuyue_heji, 2);
				$dianjizixun1_z = round($zixun_heji / $baidudianjiheji, 4);
				$dianjizixunlv_z = ($dianjizixun1_z * 100) . '%';
				$dianjilvheji1_z = round($baidudianjiheji / $baiduzhanxianheji, 4);
				$dianjilv_z = ($dianjilvheji1_z * 100) . '%';
				$rows_hj[] = array('riqi' => '按日期合计', 'zixun' => $zixun_heji, 'yuyue' => $yuyue_heji, 'daozhen' => $daozhen_heji, 'ydaozhen' => $ydaozhen_heji, 'yuyuelv' => $yuyuelv_z, 'daozhenlv' => $daozhenlv_z, 'zhuanhualv' => $zhuanhualv_z, 'baiduxiaofeiheji' => $baiduxiaofeiheji, 'baiduzhanxianheji' => $baiduzhanxianheji, 'dianjilvheji' => $dianjizixunlv_z, 'dianjiheji' => $baiduclick[$arrDate[$i]], 'dianjilvheji' => $dianjilv_z, 'baidudianjiheji' => $baidudianjiheji, 'daozhenchengbenheji' => $daozhenchengbenheji, 'zixunchengbenheji' => $zixunchengbenheji, 'yuyuechengbenheji' => $yuyuechengbenheji, 'dianjizixunlvheji' => $dianjizixunlv_z);
			}

			if ($canshu['tian_geshi'] == '月') {
				$starttime = strtotime($canshu['zx_timeStart2']);
				$endtime = strtotime($canshu['zx_timeEnd2']);

				if (31536000 < ($endtime - $starttime)) {
					js_alert('', '百度API接口只提供跨度一年的消费数据！');
					exit();
				}

				$this->assign('checked_yue', 'checked');
				$this->assign('active_yue', 'active');
				$arrDate = monthlist($starttime, $endtime);

				if ($starttime == $endtime) {
					js_alert('', '月份跨度请大于一个月!');
					exit();
				}

				if ($canshu['zx_timeEnd2'] == date('Y-m')) {
					$bigriqi = date('d');
				}
				else {
					$bigriqi = date('t', strtotime($canshu['zx_timeEnd2']));
				}

				if (!empty($canshu['yy_ID'])) {
					$Columns = a('Baidutongji');
					$StartDate = $canshu['zx_timeStart2'] . '-01 00:00:01';
					$setEndDate = $canshu['zx_timeEnd2'] . '-' . $bigriqi . ' 23:59:59';
					$setdevice = $canshu['zhanghushebei'];
					$levelOfDetails = 2;
					$yy_ID = $canshu['yy_ID'];
					$setUnitOfTime = 3;
					$zhanghuID = $canshu['zhanghuID'];
					$baidufanhui = $this->baiduzhanghu_fenriqi($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);

					for ($d = 0; $d <= count($arrDate) - 1; $d++) {
						$baiduheji = 0;
						$baidudianji = 0;
						$baiduzhanxian1 = 0;

						for ($i = 0; $i <= count($baidufanhui) - 1; $i++) {
							for ($y = 0; $y <= count($baidufanhui[$i]) - 1; $y++) {
								if (substr($baidufanhui[$i][$y]['riqi'], 0, 7) == $arrDate[$d]) {
									$baiduheji = $baiduheji + $baidufanhui[$i][$y]['xiaofei'];
									$baidudianji = $baidudianji + $baidufanhui[$i][$y]['dianji'];
									$baiduzhanxian1 = $baiduzhanxian1 + $baidufanhui[$i][$y]['zhanxian'];
									break;
								}
							}
						}

						$baidu[$arrDate[$d]] = $baiduheji;
						$baiduclick[$arrDate[$d]] = $baidudianji;
						$baiduzhanxian[$arrDate[$d]] = $baiduzhanxian1;
						unset($baiduheji);
						unset($baidudianji);
						unset($baiduzhanxian1);
					}
				}

				for ($i = 0; $i <= count($arrDate) - 1; $i++) {
					if ($canshu['zixunlaiyuan'] == 0) {
						if (($canshu['swtsj'] == 1) && empty($canshu['zhanghuID'])) {
							$tiaojian_zhanghu = '';
							$this->assign('selectd_swtzong', 'selected');
							$this->assign('sm_zxly', '说明：<font color=red>所有账户-商务通导入-商务通总数据</font>模式下：<br>' . "\r\n" . '							  <font color=red>咨询量</font> 来源为商务通导入的总数据，包含自然搜索;没有做任何账户、推广端(PC.WAP)的区分；<br>' . "\r\n" . '							 <font color=red>预约量</font> 来源为商务通导入的总数据的预约量,包含自然搜索;<br>' . "\r\n" . '							 <font color=red>到诊量</font> 来源为商务通导入的总数据的到诊量,包含自然搜索;<br>' . "\r\n" . '								如果选中PC 或移动设备，咨询预约到诊量只包含纯竞价数据');
						}
						else {
							if (($canshu['swtsj'] == 0) && empty($canshu['zhanghuID'])) {
								$jingjiatongpeifu = $zhanghu->where('yy_ID=' . $canshu['yy_ID'] . '')->getField('jingjiatongpeifu');
								$tiaojian_zhanghu = 'and locate(\'' . $jingjiatongpeifu . '\',chucifangwenURL)';
								$this->assign('selectd_swtbaidu', 'selected');
								$this->assign('sm_zxly', '说明：<font color=red>所有账户-商务通导入-纯百度竞价咨询数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入包含竞价通配符（' . $jingjiatongpeifu . '）的纯百度竞价咨询;没有做任何账户的区分；<br>' . "\r\n" . '						 <font color=red>预约量</font>  来源为商务通导入包含竞价通配符（' . $jingjiatongpeifu . '）的预约量;<br>' . "\r\n" . '						 <font color=red>到诊量</font>  来源为商务通导入包含竞价通配符（' . $jingjiatongpeifu . '）的到诊量;<br>' . "\r\n" . '							如再选中PC 或移动设备，咨询预约到诊量只包含纯竞价PC或移动 数据');
							}
							else {
								if (($canshu['swtsj'] == 1) && !empty($canshu['zhanghuID'])) {
									$tuiguang_url = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('tuiguang_url');
									$tiaojian_zhanghu = 'and locate(\'' . $tuiguang_url . '\',chucifangwenURL)';
									$this->assign('selectd_swtzong', 'selected');
									$this->assign('sm_zxly', '说明：<font color=red>单一账户-商务通导入-商务通总数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入包含推广URL（' . $tuiguang_url . '）的商务通总数据;<br>' . "\r\n" . '						  <font color=red>预约量</font>  来源为商务通导入包含推广URL（' . $tuiguang_url . '）的预约量;<br>' . "\r\n" . '						  <font color=red>到诊量</font>  来源为商务通导入包含推广URL（' . $tuiguang_url . '）的到诊量;<br>' . "\r\n" . '							如再选中PC 或移动设备，咨询预约到诊量只包含纯竞价PC或移动 数据');
								}
								else {
									if (($canshu['swtsj'] == 0) && !empty($canshu['zhanghuID'])) {
										$weiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');
										$tiaojian_zhanghu = 'and locate(\'' . $weiyifu . '\',chucifangwenURL)';
										$this->assign('selectd_swtbaidu', 'selected');
										$this->assign('sm_zxly', '说明：<font color=red>单一账户-商务通导入-纯百度竞价咨询数据</font>模式下：<br>' . "\r\n" . '						  <font color=red>咨询量</font> 来源为商务通导入包含推广URL（' . $weiyifu . '）的商务通总数据;<br>' . "\r\n" . '						  <font color=red>预约量</font>  来源为商务通导入包含推广URL（' . $weiyifu . '）的预约量;<br>' . "\r\n" . '						  <font color=red>到诊量</font>  来源为商务通导入包含推广URL（' . $weiyifu . '）的到诊量;<br>' . "\r\n" . '							如再选中PC 或移动设备，咨询预约到诊量只包含纯竞价PC或移动 数据');
									}
									else {
										echo 'error';
									}
								}
							}
						}

						if (($canshu['zhanghushebei'] == 1) && !empty($canshu['zhanghuID'])) {
							$diannaotongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . ' and zhanghudel=0')->getField('pcweiyifu');
							$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',chucifangwenURL) ';
							$this->assign('sm_pcorydcx', '<br>当前选中了计算机端,URL中还需包含PC端唯一符(' . $diannaotongpeifu . ')');
						}

						if (($canshu['zhanghushebei'] == 2) && !empty($canshu['zhanghuID'])) {
							$yidongweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . ' and zhanghudel=0')->getField('yidongweiyifu');
							$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',chucifangwenURL) ';
							$this->assign('sm_pcorydcx', '<br>当前选中了移动端,URL中还需包含PC端唯一符(' . $yidongweiyifu . ')');
						}

						if (($canshu['zhanghushebei'] == 1) && empty($canshu['zhanghuID'])) {
							$diannaotongpeifu = $zhanghu->where('zhanghudel=0')->getField('pcweiyifu');
							$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',chucifangwenURL) ';
							$this->assign('sm_pcorydcx', '<br>当前选中了计算机端,URL中还需包含PC端唯一符(' . $diannaotongpeifu . ')');
						}

						if (($canshu['zhanghushebei'] == 2) && empty($canshu['zhanghuID'])) {
							$yidongweiyifu = $zhanghu->where('zhanghudel=0')->getField('yidongweiyifu');
							$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',chucifangwenURL) ';
							$this->assign('sm_pcorydcx', '<br>当前选中了移动端,URL中还需包含PC端唯一符(' . $yidongweiyifu . ')');
						}

						$zixunliangSql = 'select count(swtID) as  total  from oa_swtdaoru  where yy_ID=' . $canshu['yy_ID'] . ' and  DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' ' . $tiaojian_zhanghu . '';
						$yuyueliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . ' where b.yy_ID=' . $canshu['yy_ID'] . ' and DATE_FORMAT(a.zx_time,\'%Y-%m\')=\'' . $arrDate[$i] . '\' and b.shifouyuyue = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . '';
						$daozhenliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . ' where b.yy_ID=' . $canshu['yy_ID'] . ' and DATE_FORMAT(b.daozhen_time,\'%Y-%m\')=\'' . $arrDate[$i] . '\' and b.shifoudaozhen = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . '';
						$this->assign('selectd_swtdr', 'selected');
					}
					else {
						$zixunliangSql = 'select count(zx_ID) as  total  from oa_huanze  where  DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' ' . $tiaojian . '';
						$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '					  where DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifouyuyue=0' . $tiaojian . '';
						$daozhenliangSql = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '					  where DATE_FORMAT(daozhen_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifoudaozhen=0' . $tiaojian . '';
						$daozhenliangSql1 = 'select count(zx_ID) as total from oa_huanze' . "\r\n" . '					  where DATE_FORMAT(yuyueTime,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifouyuyue=0' . $tiaojian . '';
						$this->assign('sm_zxly', ' 说明：<font color=red>咨询员录入模式下：咨询、预约、到诊量</font>均来自咨询员录入的所有数据；1.没有区分PC和移动端 2.无法区分百度账户来源;<br>可根据查询条件自行筛选；匹配自己需要的报表;');
						$this->assign('selectd_zxy', 'selected');
					}

					$zixunliang = mysql_query($zixunliangSql);

					while ($aaa = mysql_fetch_array($zixunliang)) {
						$zixun = $aaa['total'];
					}

					$zixun_heji = $zixun_heji + $zixun;
					$yuyueliang = mysql_query($yuyueliangSql);

					while ($aaa = mysql_fetch_array($yuyueliang)) {
						$yuyue = $aaa['total'];
					}

					$yuyue_heji = $yuyue_heji + $yuyue;
					$daozhenliang = mysql_query($daozhenliangSql);

					while ($aaa = mysql_fetch_array($daozhenliang)) {
						$daozhen = $aaa['total'];
					}

					$daozhen_heji = $daozhen_heji + $daozhen;
					$daozhenliang1 = mysql_query($daozhenliangSql1);

					while ($aaa = mysql_fetch_array($daozhenliang1)) {
						$daozhen1 = $aaa['total'];
					}

					$ydaozhen_heji = $ydaozhen_heji + $daozhen1;
					$yuyuelv = round(($yuyue / $zixun) * 100) . '%';
					$daozhenlv = round(($daozhen / $yuyue) * 100) . '%';
					$zhuanhualv = round(($daozhen / $zixun) * 100) . '%';
					$dianjizixun1 = round($zixun / $baiduclick[$arrDate[$i]], 4);
					$dianjizixunlv = ($dianjizixun1 * 100) . '%';
					$dianjilv1 = round($baiduclick[$arrDate[$i]] / $baiduzhanxian[$arrDate[$i]], 4);
					$dianjilv = ($dianjilv1 * 100) . '%';
					$zixunchengben = round($baidu[$arrDate[$i]] / $zixun, 2);
					$yuyuechengben = round($baidu[$arrDate[$i]] / $yuyue, 2);
					$daozhenchengben = round($baidu[$arrDate[$i]] / $daozhen, 2);
					$baiduxiaofeiheji = $baiduxiaofeiheji + $baidu[$arrDate[$i]];
					$baidudianjiheji = $baidudianjiheji + $baiduclick[$arrDate[$i]];
					$baiduzhanxianheji = $baiduzhanxianheji + $baiduzhanxian[$arrDate[$i]];
					$rows[] = array('riqi' => $arrDate[$i], 'zixun' => $zixun, 'yuyue' => $yuyue, 'daozhen' => $daozhen, 'ydaozhen' => $daozhen1, 'yuyuelv' => $yuyuelv, 'daozhenlv' => $daozhenlv, 'zhuanhualv' => $zhuanhualv, 'dianji' => $baiduclick[$arrDate[$i]], 'xiaofei' => $baidu[$arrDate[$i]], 'zhanxian' => $baiduzhanxian[$arrDate[$i]], 'dianjilv' => $dianjilv, 'daozhenchengben' => $daozhenchengben, 'zixunchengben' => $zixunchengben, 'yuyuechengben' => $yuyuechengben, 'dianjizixunlv' => $dianjizixunlv);
				}

				$yuyuelv_z = round(($yuyue_heji / $zixun_heji) * 100) . '%';
				$daozhenlv_z = round(($daozhen_heji / $yuyue_heji) * 100) . '%';
				$zhuanhualv_z = round(($daozhen_heji / $zixun_heji) * 100) . '%';
				$daozhenchengbenheji = round($baiduxiaofeiheji / $daozhen_heji, 2);
				$zixunchengbenheji = round($baiduxiaofeiheji / $zixun_heji, 2);
				$yuyuechengbenheji = round($baiduxiaofeiheji / $yuyue_heji, 2);
				$dianjizixun1_z = round($zixun_heji / $baidudianjiheji, 4);
				$dianjizixunlv_z = ($dianjizixun1_z * 100) . '%';
				$dianjilv1_z = round($baidudianjiheji / $baiduzhanxianheji, 4);
				$dianjilv_z = ($dianjilv1_z * 100) . '%';
				$rows_hj[] = array('riqi' => '按月份 合计', 'zixun' => $zixun_heji, 'yuyue' => $yuyue_heji, 'daozhen' => $daozhen_heji, 'ydaozhen' => $ydaozhen_heji, 'yuyuelv' => $yuyuelv_z, 'daozhenlv' => $daozhenlv_z, 'zhuanhualv' => $zhuanhualv_z, 'baiduxiaofeiheji' => $baiduxiaofeiheji, 'baiduzhanxianheji' => $baiduzhanxianheji, 'dianjilvheji' => $dianjilv_z, 'baidudianjiheji' => $baidudianjiheji, 'daozhenchengbenheji' => $daozhenchengbenheji, 'zixunchengbenheji' => $zixunchengbenheji, 'yuyuechengbenheji' => $yuyuechengbenheji, 'dianjizixunlvheji' => $dianjizixunlv_z);
			}
		}

		if (!empty($canshu['yy_ID'])) {
			$this->assign('list', array_reverse($rows));
		}

		$this->assign('list1', $rows_hj);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('zx_timeStart2', $canshu['zx_timeStart2']);
		$this->assign('zx_timeEnd2', $canshu['zx_timeEnd2']);

		for ($i = 0; $i <= count($rows) - 1; $i++) {
			$tubiao_riqi .= '\'' . $rows[$i]['riqi'] . '\',';
			$tubiao_toufang .= '' . $rows[$i]['xiaofei'] . ',';
			$tubiao_zixun .= $rows[$i]['zixun'] . ',';
			$tubiao_yuyue .= $rows[$i]['yuyue'] . ',';
			$tubiao_daozhen .= $rows[$i]['daozhen'] . ',';
			$tubiao_zixunchengben .= $rows[$i]['zixunchengben'] . ',';
		}

		$tubiao_riqi = rtrim($tubiao_riqi, ',');
		$tubiao_zixun = rtrim($tubiao_zixun, ',');
		$tubiao_yuyue = rtrim($tubiao_yuyue, ',');
		$tubiao_daozhen = rtrim($tubiao_daozhen, ',');
		$tubiao_toufang = rtrim($tubiao_toufang, ',');
		$tubiao_zixunchengben = rtrim($tubiao_zixunchengben, ',');
		$this->assign('tubiao_riqi', $tubiao_riqi);
		$this->assign('tubiao_toufang', $tubiao_toufang);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('tubiao_zixunchengben', $tubiao_zixunchengben);
		$this->assign('tubiao_zixunhj', $rows_hj[0]['zixun']);
		$this->assign('tubiao_yuyuehj', $rows_hj[0]['yuyue']);
		$this->assign('tubiao_daozhenhj', $rows_hj[0]['daozhen']);
		$daochu = serialize($rows);
		$daochu_heji = serialize($rows_hj);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);

		if ($canshu['zhanghushebei'] == 0) {
			$this->assign('selectd1', 'selected');
			$this->assign('duankou', '全部设备');
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd2', 'selected');
			$this->assign('duankou', 'PC端');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd3', 'selected');
			$this->assign('duankou', '手机端');
		}

		$this->display();
	}

	public function ExcleDC_oneline()
	{
		$arr = array_reverse(unserialize($_POST['excle']));
		$arr_heji = unserialize($_POST['excle1']);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator('liufeng')->setLastModifiedBy('Maarten Balliauw')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Test result file');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '日期')->setCellValue('B1', '百度投放')->setCellValue('C1', '百度点击')->setCellValue('D1', '咨询量')->setCellValue('E1', '预约量')->setCellValue('F1', '应到诊')->setCellValue('G1', '实际到诊')->setCellValue('H1', '点击咨询率')->setCellValue('I1', '预约率')->setCellValue('J1', '到诊率')->setCellValue('K1', '转化率')->setCellValue('L1', '咨询成本')->setCellValue('M1', '预约成本')->setCellValue('N1', '到诊成本')->setCellValue('O1', '展现')->setCellValue('P1', '点击率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['riqi']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['baiduxiaofeiheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['baidudianjiheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['dianjizixunlvheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $num, $arr_heji[0]['zhuanhualv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $num, $arr_heji[0]['zixunchengbenheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $num, $arr_heji[0]['yuyuechengbenheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $num, $arr_heji[0]['daozhenchengbenheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $num, $arr_heji[0]['baiduzhanxianheji']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $num, $arr_heji[0]['dianjilvheji']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['riqi']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['xiaofei']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['dianji']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['zixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['yuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['ydaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['dianjizixunlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $num, $arr[$i]['zhuanhualv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $num, $arr[$i]['zixunchengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $num, $arr[$i]['yuyuechengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $num, $arr[$i]['daozhenchengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $num, $arr[$i]['zhanxian']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $num, $arr[$i]['dianjilv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		header('Content-Disposition:inline;filename="数据汇总报表-(' . date('Y-m-d') . ')导出.xls"');
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_shijianduan()
	{
		$canshu = i('request.');
		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$canshu['zx_timeStart2'] = $canshu['zx_timeStart'];
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$canshu['zx_timeEnd2'] = $canshu['zx_timeEnd'];
		}

		$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
		$zhanghu = m('baiduzhanghu');
		$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
		$this->assign('zhanghurow', $zhanghurow);
		$this->assign('zhanghuID', $canshu['zhanghuID']);
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$shijiandian = array('00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00');

		if (2592000 < ($endtime - $starttime)) {
			js_alert('', '百度竞价账户 时间段表仅支持一个月数据；请注意导出EXCLE表备份');
			exit();
		}

		$this->assign('active_tian', 'active');
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

		if (!empty($canshu['yy_ID'])) {
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setdevice = 0;
			$levelOfDetails = 2;
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 7;
			$zhanghuID = $canshu['zhanghuID'];
			$this->assign('shijianduan_keyword', 'zx_timeStart=' . $canshu['zx_timeStart'] . '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '&yy_ID=' . $canshu['yy_ID'] . '');
			$baidufanhui = $this->baiduzhanghu_shijianduan($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);

			if ($canshu['zixunlaiyuan'] == 0) {
				if (($canshu['swtsj'] == 1) && empty($canshu['zhanghuID'])) {
					$tiaojian_zhanghu = '';
					$this->assign('selectd_swtzong', 'selected');
				}
				else {
					if (($canshu['swtsj'] == 0) && empty($canshu['zhanghuID'])) {
						$jingjiatongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('jingjiatongpeifu');
						$tiaojian_zhanghu = 'and locate(\'' . $jingjiatongpeifu . '\',oa_swtdaoru.chucifangwenURL)';
						$this->assign('selectd_swtbaidu', 'selected');
					}
					else {
						if (($canshu['swtsj'] == 1) && !empty($canshu['zhanghuID'])) {
							$tiaojian_zhanghu = '';
							$this->assign('selectd_swtzong', 'selected');
						}
						else {
							if (($canshu['swtsj'] == 0) && !empty($canshu['zhanghuID'])) {
								$weiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');
								$tiaojian_zhanghu = 'and locate(\'' . $weiyifu . '\',oa_swtdaoru.chucifangwenURL)';
								$this->assign('selectd_swtbaidu', 'selected');
							}
							else {
								echo 'error';
							}
						}
					}
				}

				$zixunliangSql = 'select zx_time from oa_swtdaoru ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '									and yy_ID= ' . $canshu['yy_ID'] . ' ' . $tiaojian_zhanghu . ' order by zx_time';
				$yuyueliangSql = 'select oa_swtdaoru.zx_time ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\'   and oa_managezx.shifouyuyue = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$daozhenliangSql = 'select oa_swtdaoru.zx_time  ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\' and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$this->assign('selectd1', 'selected');
			}
			else {
				$zixunliangSql = 'select zx_ID,zx_time,shifoudaozhen from oa_huanze ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . $tiaojian . ' order by zx_time';
				$yuyueliangSql = 'select zx_ID,zx_time,shifoudaozhen from oa_huanze ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0 ' . $tiaojian . ' order by zx_time';
				$daozhenliangSql = 'select zx_ID,zx_time,shifoudaozhen from oa_huanze ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0 ' . $tiaojian . ' order by zx_time';
				$this->assign('selectd2', 'selected');
			}

			$zixunliang1 = m('huanze');
			$zixunliang = $zixunliang1->query($zixunliangSql);
			$yuyueliang = $zixunliang1->query($yuyueliangSql);
			$daozhenliang = $zixunliang1->query($daozhenliangSql);

			for ($i = 0; $i <= count($zixunliang) - 1; $i++) {
				$zixunliang[$i]['zx_time'] = substr($zixunliang[$i]['zx_time'], 11, 2);
			}

			for ($i = 0; $i <= count($yuyueliang) - 1; $i++) {
				$yuyueliang[$i]['zx_time'] = substr($yuyueliang[$i]['zx_time'], 11, 2);
			}

			for ($i = 0; $i <= count($daozhenliang) - 1; $i++) {
				$daozhenliang[$i]['zx_time'] = substr($daozhenliang[$i]['zx_time'], 11, 2);
			}

			for ($i = 0; $i <= count($shijiandian) - 1; $i++) {
				$shijianzixun = 0;
				$shijianyuyue = 0;
				$shijiandaozhen = 0;

				for ($y = 0; $y <= count($baidufanhui) - 1; $y++) {
					for ($d = 0; $d <= count($baidufanhui[$y]) - 1; $d++) {
						if (strstr($baidufanhui[$y][$d]['riqi'], $shijiandian[$i])) {
							$baiduheji = $baiduheji + $baidufanhui[$y][$d]['xiaofei'];
							$baidudianji = $baidudianji + $baidufanhui[$y][$d]['dianji'];
							$baiduzhanxian = $baiduzhanxian + $baidufanhui[$y][$d]['zhanxian'];
						}
					}
				}

				$xiaoshi = substr($shijiandian[$i], 0, 2);

				foreach ($zixunliang as $k => $v ) {
					if ($zixunliang[$k]['zx_time'] == $xiaoshi) {
						$shijianzixun++;
						unset($zixunliang[$k]);
					}
				}

				foreach ($yuyueliang as $k => $v ) {
					if ($yuyueliang[$k]['zx_time'] == $xiaoshi) {
						$shijianyuyue++;
						unset($yuyueliang[$k]);
					}
				}

				foreach ($daozhenliang as $k => $v ) {
					if ($daozhenliang[$k]['zx_time'] == $xiaoshi) {
						$shijiandaozhen++;
						unset($daozhenliang[$k]);
					}
				}

				$dianjilv1 = $baidudianji / $baiduzhanxian;
				$dianjilv2 = round($dianjilv1, 4);
				$zixunlv1 = $shijianzixun / $baidudianji;
				$zixunlv2 = round($zixunlv1, 4);
				$zixunchengben1 = $baiduheji / $shijianzixun;
				$zixunchengben = round($zixunchengben1, 2);
				$baidu[$i] = array('shijiandian' => $shijiandian[$i], 'xiaofei' => $baiduheji, 'dianji' => $baidudianji, 'shijianzixun' => $shijianzixun, 'shijianyuyue' => $shijianyuyue, 'shijiandaozhen' => $shijiandaozhen, 'zhanxian' => $baiduzhanxian, 'zixunlv' => ($zixunlv2 * 100) . '%', 'dianjilv' => ($dianjilv2 * 100) . '%', 'zixunchengben' => $zixunchengben);

				if ($baidu[$i]['zhanxian'] == '') {
					unset($baidu[$i]);
				}

				unset($baiduheji);
				unset($baidudianji);
				unset($baiduzhanxian);
			}
		}

		$this->assign('shiduanheji', $baidu);

		foreach ($baidu as $k => $v ) {
			$sdheji['zhanxian'] = $sdheji['zhanxian'] + $v['zhanxian'];
			$sdheji['dianji'] = $sdheji['dianji'] + $v['dianji'];
			$sdheji['xiaofei'] = $sdheji['xiaofei'] + $v['xiaofei'];
			$sdheji['shijianzixun'] = $sdheji['shijianzixun'] + $v['shijianzixun'];
			$sdheji['shijianyuyue'] = $sdheji['shijianyuyue'] + $v['shijianyuyue'];
			$sdheji['shijiandaozhen'] = $sdheji['shijiandaozhen'] + $v['shijiandaozhen'];
		}

		$zixunchengben1 = $sdheji['xiaofei'] / $sdheji['shijianzixun'];
		$sdheji['zixunchengben'] = round($zixunchengben1, 2);
		$dianjilv1 = $sdheji['dianji'] / $sdheji['zhanxian'];
		$sdheji['dianjilv'] = (round($dianjilv1, 4) * 100) . '%';
		$zixunlv1 = $sdheji['shijianzixun'] / $sdheji['dianji'];
		$sdheji['zixunlv'] = (round($zixunlv1, 4) * 100) . '%';
		$this->assign('sdheji', $sdheji);
		$daochu = serialize($baidu);
		$this->assign('daochu', $daochu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);

		foreach ($baidu as $k => $v ) {
			$tubiao_riqi .= '\'' . $v['shijiandian'] . '\',';
			$tubiao_toufang .= '' . $v['xiaofei'] . ',';
			$tubiao_dianji .= $v['dianji'] . ',';
			$tubiao_zixun .= $v['shijianzixun'] . ',';
			$tubiao_yuyue .= $v['shijianyuyue'] . ',';
			$tubiao_daozhen .= $v['shijiandaozhen'] . ',';
			$tubiao_zixunchengben .= $v['zixunchengben'] . ',';
		}

		$tubiao_riqi = rtrim($tubiao_riqi, ',');
		$tubiao_dianji = rtrim($tubiao_dianji, ',');
		$tubiao_zixun = rtrim($tubiao_zixun, ',');
		$tubiao_yuyue = rtrim($tubiao_yuyue, ',');
		$tubiao_daozhen = rtrim($tubiao_daozhen, ',');
		$tubiao_toufang = rtrim($tubiao_toufang, ',');
		$tubiao_zixunchengben = rtrim($tubiao_zixunchengben, ',');
		$this->assign('tubiao_riqi', $tubiao_riqi);
		$this->assign('tubiao_toufang', $tubiao_toufang);
		$this->assign('tubiao_dianji', $tubiao_dianji);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('tubiao_zixunchengben', $tubiao_zixunchengben);
		$this->assign('tubiao_zixunhj', $rows_hj[0]['zixun']);
		$this->assign('tubiao_yuyuehj', $rows_hj[0]['yuyue']);
		$this->assign('tubiao_daozhenhj', $rows_hj[0]['daozhen']);
		$this->display();
	}

	public function ExcleDC_shijianduan()
	{
		$arr = unserialize($_POST['excle']);
		$arr = array_values($arr);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator('liufeng')->setLastModifiedBy('Maarten Balliauw')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Test result file');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间段')->setCellValue('B1', '百度投放')->setCellValue('C1', '展现合计')->setCellValue('D1', '点击合计')->setCellValue('E1', '咨询量')->setCellValue('F1', '预约量')->setCellValue('G1', '到诊量')->setCellValue('H1', '咨询成本')->setCellValue('I1', '点击->咨询率')->setCellValue('J1', '点击率');
		$num = '2';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['shijiandian']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['xiaofei']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['zhanxian']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['dianji']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['shijianzixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['shijianyuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['shijiandaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zixunchengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $num, $arr[$i]['zixunlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $num, $arr[$i]['dianjilv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		header('Content-Disposition:inline;filename="时间段报表-(' . date('Y-m-d') . ')导出.xls"');
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_shishisousuoci()
	{
		$canshu = i('request.');

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
				exit();
			}

			if (empty($canshu['zhanghuID'])) {
				js_alert('', '请选择 账户');
				exit();
			}
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-d');
			$canshu['zx_timeStart2'] = $canshu['zx_timeStart'];
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$canshu['zx_timeEnd2'] = $canshu['zx_timeEnd'];
		}

		if ($canshu['zhanghushebei'] == 0) {
			$this->assign('selectd1', 'selected');
			$this->assign('duankou', '全部设备');
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd2', 'selected');
			$this->assign('duankou', 'PC端');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd3', 'selected');
			$this->assign('duankou', '手机端');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
		$zhanghu = m('baiduzhanghu');
		$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
		$this->assign('zhanghurow', $zhanghurow);
		$this->assign('zhanghuID', $canshu['zhanghuID']);
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

		if ($canshu['submit1'] != '') {
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setdevice = $canshu['zhanghushebei'];
			$levelOfDetails = 12;
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 8;
			$zhanghuID = $canshu['zhanghuID'];
			$baidu_sousuoci = $this->baiduzhanghu_sousuoci($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);
			$baidu_guanjianci = $this->baiduzhanghu_guanjianci($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);
		}

		$item = array();

		foreach ($baidu_guanjianci as $k => $v ) {
			if (!isset($item[$v['pipeici']])) {
				$item[$v['pipeici']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'xiaofei' => $v['xiaofei'], 'danjia' => $v['danjia'], 'jihuaName' => $v['jihuaName']);
			}
			else {
				$item[$v['pipeici']] = array('dianji' => $item[$v['pipeici']]['dianji'] + $v['dianji'], 'zhanxian' => $item[$v['pipeici']]['zhanxian'] + $v['zhanxian'], 'xiaofei' => $item[$v['pipeici']]['xiaofei'] + $v['xiaofei'], 'jihuaName' => $v['jihuaName'], 'danjia' => round(($item[$v['pipeici']]['xiaofei'] + $v['xiaofei']) / ($item[$v['pipeici']]['dianji'] + $v['dianji']), 2));
			}
		}

		$itemInfo = arraysort($item, 'xiaofei', 'desc');
		unset($baidu_guanjianci);
		$itemInfo_ss = array();

		foreach ($baidu_sousuoci as $k => $v ) {
			if (!isset($itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']])) {
				$itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'sousuoci' => $v['sousuoci']);
			}
			else {
				$itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']] = array('dianji' => $itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']]['dianji'] + $v['dianji'], 'zhanxian' => $itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']]['zhanxian'] + $v['zhanxian'], 'sousuoci' => $v['sousuoci']);
			}
		}

		unset($baidu_sousuoci);
		$list = array_merge_recursive($itemInfo, $itemInfo_ss);
		unset($itemInfo);
		unset($itemInfo_ss);

		foreach ($list as $k => $v ) {
			foreach ($v['sousuoci'] as $key => $val ) {
				$list[$k]['sousuoci'][$key]['xiaofei'] = $list[$k]['sousuoci'][$key]['dianji'] * $list[$k]['danjia'];
			}
		}

		if (!empty($canshu['yy_ID'])) {
			$this->assign('list', $list);
		}

		$this->assign('xuhao', 1);
		$this->assign('list1', $rows_hj);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('zx_timeStart2', $canshu['zx_timeStart2']);
		$this->assign('zx_timeEnd2', $canshu['zx_timeEnd2']);
		$daochu = serialize($rows);
		$daochu_heji = serialize($rows_hj);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->display();
	}

	public function report_lishisousuoci()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
				exit();
			}

			if (empty($canshu['zhanghuID'])) {
				js_alert('', '请选择 账户');
				exit();
			}
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$canshu['zx_timeStart2'] = $canshu['zx_timeStart'];
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$canshu['zx_timeEnd2'] = $canshu['zx_timeEnd'];
		}

		if ($canshu['zhanghushebei'] == 0) {
			$this->assign('selectd1', 'selected');
			$this->assign('duankou', '全部设备');
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd2', 'selected');
			$this->assign('duankou', 'PC端');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd3', 'selected');
			$this->assign('duankou', '手机端');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
		$zhanghu = m('baiduzhanghu');
		$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
		$this->assign('zhanghurow', $zhanghurow);
		$this->assign('zhanghuID', $canshu['zhanghuID']);
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

		if ($canshu['submit1'] != '') {
			if ($canshu['zixunlaiyuan'] == 0) {
				$shuomingtiaojian = '商务通导入模式下，商务通抓取到的关键词进行咨询量统计';
			}
			else {
				$shuomingtiaojian = '咨询员录入模式下，咨询员录入的关键词进行咨询量统计';
			}

			$this->assign('shuoming', '说明：在没有设置URL着陆页{keywordid}的前提下，可以通过商务通导入或咨询录入的搜索词，' . "\r\n" . '			  对账户搜索词及关键词报表，进行匹配！' . "\r\n" . '			  规则为:系统中（商务通导入或咨询录入的）每个搜索词咨询量，到诊量进行合计；在通过触发关键词进行匹配！<br>' . "\r\n" . '			  关键词咨询量：该关键词触发的搜索词的咨询量合计；<br>' . "\r\n" . '			  关键词到诊量：该关键词触发的搜索词的到诊量合计；<br>' . "\r\n" . '			  搜索词咨询量：' . $shuomingtiaojian . ';<br>' . "\r\n" . '			  此模块报表虽有一定误差，但在没有初期配置的前提下，任具有很高的参考价值;<br>' . "\r\n" . '			  咨询来源-咨询员录入：如果咨询员有录入，可用咨询员录入的关键词进行匹配;<br>' . "\r\n" . '			  咨询来源-商务通导入：如果咨询员没有录入，可用商务通导入的关键词进行匹配;' . "\r\n" . '			  ');
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setdevice = $canshu['zhanghushebei'];
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 8;
			$zhanghuID = $canshu['zhanghuID'];
			$fanhuiziduan = array('impression', 'click', 'cost', 'cpc');
			$ReportType = 14;
			$levelOfDetails = 11;
			$report_ID = $this->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $this->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url = $this->getReportUrl($report_ID, $zhanghuID);
			$fanhuiziduan = array('impression', 'click');
			$ReportType = 6;
			$levelOfDetails = 12;
			$report_ID = $this->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $this->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url_sousuo = $this->getReportUrl($report_ID, $zhanghuID);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $report_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
			$guanjianciInfo = explode("\n", $result);
			unset($result);
			$baidu_guanjianci = array();
			$guanjianciheji = count($guanjianciInfo);

			for ($i = 1; $i < $guanjianciheji; $i++) {
				$guanjianciInfo[$i] = explode('	', $guanjianciInfo[$i]);

				if (0 < $guanjianciInfo[$i][11]) {
					$baidu_guanjianci[$i] = array('zhanxian' => $guanjianciInfo[$i][10], 'dianji' => $guanjianciInfo[$i][11], 'xiaofei' => $guanjianciInfo[$i][12], 'danjia' => $guanjianciInfo[$i][13], 'pipeici' => $guanjianciInfo[$i][9], 'riqi' => $guanjianciInfo[$i][0], 'zhanghuName' => $guanjianciInfo[$i][2], 'jihuaName' => $guanjianciInfo[$i][4], 'keywordid' => $guanjianciInfo[$i][7], 'wordid' => $guanjianciInfo[$i][8], 'keywordidzixun' => $keyworditem[$guanjianciInfo[$i][7]]);
				}
			}

			unset($guanjianciInfo);
			curl_setopt($ch, CURLOPT_URL, $report_url_sousuo);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
			$sousuociInfo = explode("\n", $result);
			$baidu_sousuoci = array();
			$sousuociheji = count($sousuociInfo);

			for ($i = 1; $i < $sousuociheji; $i++) {
				$sousuociInfo[$i] = explode('	', $sousuociInfo[$i]);
				$baidu_sousuoci[$i] = array('zhanxian' => $sousuociInfo[$i][16], 'dianji' => $sousuociInfo[$i][17], 'pipeici' => $sousuociInfo[$i][14], 'riqi' => $sousuociInfo[$i][0], 'sousuoci' => $sousuociInfo[$i][15], 'zhanghuName' => $sousuociInfo[$i][2], 'keywordid' => $sousuociInfo[$i][12], 'wordid' => $sousuociInfo[$i][13]);
			}

			unset($sousuociInfo);
		}

		$item = array();

		foreach ($baidu_guanjianci as $k => $v ) {
			if (!isset($item[$v['pipeici']])) {
				$item[$v['pipeici']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'xiaofei' => $v['xiaofei'], 'danjia' => $v['danjia'], 'jihuaName' => $v['jihuaName'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $v['keywordidzixun']);
			}
			else {
				$item[$v['pipeici']] = array('dianji' => $item[$v['pipeici']]['dianji'] + $v['dianji'], 'zhanxian' => $item[$v['pipeici']]['zhanxian'] + $v['zhanxian'], 'xiaofei' => $item[$v['pipeici']]['xiaofei'] + $v['xiaofei'], 'jihuaName' => $v['jihuaName'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $v['keywordidzixun'], 'danjia' => round(($item[$v['pipeici']]['xiaofei'] + $v['xiaofei']) / ($item[$v['pipeici']]['dianji'] + $v['dianji']), 2));
			}
		}

		unset($baidu_guanjianci);
		$itemInfo = arraysort($item, 'xiaofei', 'desc');
		unset($item);

		if ($canshu['zixunlaiyuan'] == 0) {
			$zixunsql = 'select guanjianci,count(*) as zixunliang' . "\r\n" . '					 from oa_swtdaoru ' . "\r\n" . '			 		 where yy_ID=' . $canshu['yy_ID'] . ' and guanjianci !=\'\' and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
			$this->assign('selectd1', 'selected');
		}
		else {
			$zixunsql = 'select guanjianci,count(*) as zixunliang' . "\r\n" . '					 from oa_managezx' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
			$this->assign('selectd2', 'selected');
		}

		$yuyuesql = 'select guanjianci,count(*) as yuyueliang' . "\r\n" . '					 from oa_managezx ' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' and  shifouyuyue=0 and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
		$daozhensql = 'select guanjianci,count(*) as daozhenliang' . "\r\n" . '					 from oa_managezx' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' and shifoudaozhen=0 and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
		$managezx = m('managezx');
		$zixunarr1 = $managezx->query($zixunsql);
		$zixunarr = array();

		foreach ($zixunarr1 as $k => $v ) {
			$zixunarr[$v['guanjianci']] = array('zixun' => $v['zixunliang']);
		}

		unset($zixunarr1);
		$yuyuearr1 = $managezx->query($yuyuesql);
		$yuyuearr = array();

		foreach ($yuyuearr1 as $k => $v ) {
			$yuyuearr[$v['guanjianci']] = array('yuyue' => $v['yuyueliang']);
		}

		unset($yuyuearr1);
		$daozhenearr1 = $managezx->query($daozhensql);
		$daozhenearr = array();

		foreach ($daozhenearr1 as $k => $v ) {
			$daozhenearr[$v['guanjianci']] = array('daozhen' => $v['daozhenliang']);
		}

		unset($daozhenearr1);
		$guanjianci_biaoxian = array_merge_recursive($zixunarr, $yuyuearr, $daozhenearr);
		unset($zixunarr);
		unset($yuyuearr);
		unset($daozhenearr);
		$itemInfo_ss = array();

		foreach ($baidu_sousuoci as $k => $v ) {
			if (!isset($itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']])) {
				$itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'sousuoci' => $v['sousuoci'], 'zixun' => $guanjianci_biaoxian[$v['sousuoci']]['zixun'], 'yuyue' => $guanjianci_biaoxian[$v['sousuoci']]['yuyue'], 'daozhen' => $guanjianci_biaoxian[$v['sousuoci']]['daozhen']);
			}
			else {
				$itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']] = array('dianji' => $itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']]['dianji'] + $v['dianji'], 'zhanxian' => $itemInfo_ss[$v['pipeici']]['sousuoci'][$v['sousuoci']]['zhanxian'] + $v['zhanxian'], 'sousuoci' => $v['sousuoci'], 'zixun' => $guanjianci_biaoxian[$v['sousuoci']]['zixun'], 'yuyue' => $guanjianci_biaoxian[$v['sousuoci']]['yuyue'], 'daozhen' => $guanjianci_biaoxian[$v['sousuoci']]['daozhen']);
			}
		}

		unset($baidu_sousuoci);
		$list = array_merge_recursive($itemInfo, $itemInfo_ss);
		unset($itemInfo);
		unset($itemInfo_ss);

		foreach ($list as $k => $v ) {
			foreach ($v['sousuoci'] as $key => $val ) {
				$list[$k]['sousuoci'][$key]['xiaofei'] = $list[$k]['sousuoci'][$key]['dianji'] * $list[$k]['danjia'];
				$zixunliang = $zixunliang + $list[$k]['sousuoci'][$key]['zixun'];
				$yuyueliang = $yuyueliang + $list[$k]['sousuoci'][$key]['yuyue'];
				$daozhenliang = $daozhenliang + $list[$k]['sousuoci'][$key]['daozhen'];
			}

			$list[$k]['zixunheji'] = $zixunliang;
			$list[$k]['yuyueheji'] = $yuyueliang;
			$list[$k]['daozhenheji'] = $daozhenliang;

			if (0 < $list[$k]['zixunheji']) {
				$list[$k]['zixunchengben'] = round($list[$k]['xiaofei'] / $list[$k]['zixunheji'], 2);
			}
			else {
				$list[$k]['zixunchengben'] = $list[$k]['xiaofei'];
			}

			$list[$k]['daozhenchengben'] = round($list[$k]['xiaofei'] / $list[$k]['daozhenheji'], 2);
			unset($zixunliang);
			unset($yuyueliang);
			unset($daozhenliang);
		}

		if (!empty($canshu['yy_ID'])) {
			$this->assign('list', $list);
		}

		$this->assign('xuhao', 1);
		$this->assign('list1', $rows_hj);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('zx_timeStart2', $canshu['zx_timeStart2']);
		$this->assign('zx_timeEnd2', $canshu['zx_timeEnd2']);

		foreach ($list as $k => $v ) {
			$list_daochu[$k]['jihuaName'] = $v['jihuaName'];
			$list_daochu[$k]['pipeici'] = $k;
			$list_daochu[$k]['zhanxian'] = $v['zhanxian'];
			$list_daochu[$k]['dianji'] = $v['dianji'];
			$list_daochu[$k]['xiaofei'] = $v['xiaofei'];
			$list_daochu[$k]['danjia'] = $v['danjia'];
			$list_daochu[$k]['zixunheji'] = $v['zixunheji'];
			$list_daochu[$k]['zixunchengben'] = $v['zixunchengben'];
			$list_daochu[$k]['daozhenheji'] = $v['daozhenheji'];
			$list_daochu[$k]['daozhenchengben'] = $v['daozhenchengben'];
		}

		$daochu = json_encode($list_daochu);
		$this->assign('daochu', $daochu);
		$this->display();
	}

	public function report_lishisousuociid()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
				exit();
			}

			if (empty($canshu['zhanghuID'])) {
				js_alert('', '请选择 账户');
				exit();
			}
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
			$canshu['zx_timeStart2'] = $canshu['zx_timeStart'];
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
			$canshu['zx_timeEnd2'] = $canshu['zx_timeEnd'];
		}

		if ($canshu['zhanghushebei'] == 0) {
			$this->assign('selectd1', 'selected');
			$this->assign('duankou', '全部设备');
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd2', 'selected');
			$this->assign('duankou', 'PC端');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd3', 'selected');
			$this->assign('duankou', '手机端');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
		$zhanghu = m('baiduzhanghu');
		$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
		$this->assign('zhanghurow', $zhanghurow);
		$this->assign('zhanghuID', $canshu['zhanghuID']);
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

		if ($canshu['submit1'] != '') {
			if ($canshu['zixunlaiyuan'] == 0) {
				$shuomingtiaojian = '商务通导入模式下，商务通抓取到的搜索词进行咨询量统计';
			}
			else {
				$shuomingtiaojian = '咨询员录入模式下，咨询员录入的关搜索词进行咨询量统计';
			}

			$this->assign('shuoming', '说明：在有设置URL着陆页{keywordid}的前提下，可以通过商务通导入初次访问URL中keywordid，' . "\r\n" . '			  对账户搜索词及关键词报表keywordid，进行匹配！<br>' . "\r\n" . '			  关键词咨询量：keywordid咨询量合计；<br>' . "\r\n" . '			  关键词到诊量：keywordid到诊量合计；<br>' . "\r\n" . '			  搜索词咨询量：' . $shuomingtiaojian . ';<br>' . "\r\n" . '			  此模块报表相对很准确，但需要前期的设置；报表中搜索词的咨询量与触发关键词的咨询量没有直接关系，仅供参考;<br>' . "\r\n" . '			  咨询量来源-咨询员录入模式下：搜索词的（咨询量到诊量）来源为咨询员录入，与关键词咨询量、到诊量的没有关系;<br>' . "\r\n" . '			  咨询量来源-商务通导入模式下：搜索词的（咨询量到诊量）来源为商务通导入，与关键词咨询量、到诊量的没有关系;' . "\r\n" . '			  ');
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setDevice = $canshu['zhanghushebei'];
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 8;
			$zhanghuID = $canshu['zhanghuID'];
			$fanhuiziduan = array('impression', 'click', 'cost', 'cpc');
			$ReportType = 14;
			$levelOfDetails = 11;
			$report_ID = $this->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $this->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url = $this->getReportUrl($report_ID, $zhanghuID);
			$fanhuiziduan = array('impression', 'click');
			$ReportType = 6;
			$levelOfDetails = 12;
			$report_ID = $this->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $this->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url_sousuo = $this->getReportUrl($report_ID, $zhanghuID);
			$zhanghuweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');

			if ($canshu['zhanghushebei'] == 1) {
				$diannaotongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('pcweiyifu');
				$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',oa_swtdaoru.chucifangwenURL) ';
			}

			if ($canshu['zhanghushebei'] == 2) {
				$yidongweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('yidongweiyifu');
				$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',oa_swtdaoru.chucifangwenURL) ';
			}

			$swtsqlzixun = 'select chucifangwenURL from oa_swtdaoru' . "\r\n" . '				 where yy_ID=' . $canshu['yy_ID'] . ' and zx_time>\'' . $StartDate . '\' and zx_time<\'' . $setEndDate . '\' and locate(\'' . $zhanghuweiyifu . '\',chucifangwenURL) ' . $tiaojian_zhanghu . '';
			$swtsqldaozhen = 'select oa_swtdaoru.chucifangwenURL from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\'  and locate(\'' . $zhanghuweiyifu . '\',oa_swtdaoru.chucifangwenURL) and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
			$chucifangwenURLRow = $zhanghu->query($swtsqlzixun);
			$gjcwyf = 'bdkeyword';

			foreach ($chucifangwenURLRow as $k => $v ) {
				$keywordid = tiqu($v['chucifangwenURL'], $gjcwyf);

				if ($keywordid != '') {
					$keywordrows .= $keywordid . ',';
				}
			}

			unset($chucifangwenURLRow);
			$keywordrows = rtrim($keywordrows, ',');
			$keywordarr = explode(',', $keywordrows);
			$keyworditem = array();

			foreach ($keywordarr as $k => $v ) {
				if (!isset($keyworditem[$v])) {
					$keyworditem[$v] = 1;
				}
				else {
					$keyworditem[$v]++;
				}
			}

			unset($keywordid);
			unset($keywordrows);
			unset($keywordarr);
			$chucifangwenURLRow = $zhanghu->query($swtsqldaozhen);

			foreach ($chucifangwenURLRow as $k => $v ) {
				$keywordid = tiqu($v['chucifangwenURL'], $gjcwyf);

				if ($keywordid != '') {
					$keywordrows .= $keywordid . ',';
				}
			}

			unset($chucifangwenURLRow);
			$keywordrows = rtrim($keywordrows, ',');
			$keywordarr = explode(',', $keywordrows);
			$keyworditem_dz = array();

			foreach ($keywordarr as $k => $v ) {
				if (!isset($keyworditem_dz[$v])) {
					$keyworditem_dz[$v] = 1;
				}
				else {
					$keyworditem_dz[$v]++;
				}
			}

			unset($keywordid);
			unset($keywordrows);
			unset($keywordarr);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $report_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
			$guanjianciInfo = explode("\n", $result);
			unset($result);
			$baidu_guanjianci = array();
			$guanjianciheji = count($guanjianciInfo);

			for ($i = 1; $i < $guanjianciheji; $i++) {
				$guanjianciInfo[$i] = explode('	', $guanjianciInfo[$i]);

				if (0 < $guanjianciInfo[$i][11]) {
					$baidu_guanjianci[$i] = array('zhanxian' => $guanjianciInfo[$i][10], 'dianji' => $guanjianciInfo[$i][11], 'xiaofei' => $guanjianciInfo[$i][12], 'danjia' => $guanjianciInfo[$i][13], 'pipeici' => $guanjianciInfo[$i][9], 'riqi' => $guanjianciInfo[$i][0], 'zhanghuName' => $guanjianciInfo[$i][2], 'jihuaName' => $guanjianciInfo[$i][4], 'keywordid' => $guanjianciInfo[$i][7], 'wordid' => $guanjianciInfo[$i][8], 'keywordidzixun' => $keyworditem[$guanjianciInfo[$i][7]], 'keywordiddaozhen' => $keyworditem_dz[$guanjianciInfo[$i][7]]);
				}
			}

			unset($guanjianciInfo);
			curl_setopt($ch, CURLOPT_URL, $report_url_sousuo);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
			$sousuociInfo = explode("\n", $result);
			$baidu_sousuoci = array();
			$sousuociheji = count($sousuociInfo);

			for ($i = 1; $i < $sousuociheji; $i++) {
				$sousuociInfo[$i] = explode('	', $sousuociInfo[$i]);
				$baidu_sousuoci[$i] = array('zhanxian' => $sousuociInfo[$i][16], 'dianji' => $sousuociInfo[$i][17], 'pipeici' => $sousuociInfo[$i][14], 'riqi' => $sousuociInfo[$i][0], 'sousuoci' => $sousuociInfo[$i][15], 'zhanghuName' => $sousuociInfo[$i][2], 'keywordid' => $sousuociInfo[$i][12], 'wordid' => $sousuociInfo[$i][13]);
			}

			unset($sousuociInfo);
		}

		$item = array();

		foreach ($baidu_guanjianci as $k => $v ) {
			if (!isset($item[$v['keywordid']])) {
				$item[$v['keywordid']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'xiaofei' => $v['xiaofei'], 'danjia' => $v['danjia'], 'jihuaName' => $v['jihuaName'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $v['keywordidzixun'], 'keywordiddaozhen' => $v['keywordiddaozhen'], 'pipeici' => $v['pipeici']);
			}
			else {
				$item[$v['keywordid']] = array('dianji' => $item[$v['keywordid']]['dianji'] + $v['dianji'], 'zhanxian' => $item[$v['keywordid']]['zhanxian'] + $v['zhanxian'], 'xiaofei' => $item[$v['keywordid']]['xiaofei'] + $v['xiaofei'], 'jihuaName' => $v['jihuaName'], 'pipeici' => $v['pipeici'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $v['keywordidzixun'], 'keywordiddaozhen' => $v['keywordiddaozhen'], 'danjia' => round(($item[$v['pipeici']]['xiaofei'] + $v['xiaofei']) / ($item[$v['pipeici']]['dianji'] + $v['dianji']), 2));
			}
		}

		unset($baidu_guanjianci);
		$itemInfo = arraysort($item, 'xiaofei', 'desc');
		unset($item);

		if ($canshu['zixunlaiyuan'] == 0) {
			$zixunsql = 'select guanjianci,count(*) as zixunliang' . "\r\n" . '					 from oa_swtdaoru ' . "\r\n" . '			 		 where yy_ID=' . $canshu['yy_ID'] . ' and guanjianci !=\'\' and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
			$this->assign('selectd1', 'selected');
		}
		else {
			$zixunsql = 'select guanjianci,count(*) as zixunliang' . "\r\n" . '					 from oa_managezx' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
			$this->assign('selectd2', 'selected');
		}

		$yuyuesql = 'select guanjianci,count(*) as yuyueliang' . "\r\n" . '					 from oa_managezx ' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' and  shifouyuyue=0 and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
		$daozhensql = 'select guanjianci,count(*) as daozhenliang' . "\r\n" . '					 from oa_managezx' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' and shifoudaozhen=0 and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
		$managezx = m('managezx');
		$zixunarr1 = $managezx->query($zixunsql);
		$zixunarr = array();

		foreach ($zixunarr1 as $k => $v ) {
			$zixunarr[$v['guanjianci']] = array('zixun' => $v['zixunliang']);
		}

		unset($zixunarr1);
		$yuyuearr1 = $managezx->query($yuyuesql);
		$yuyuearr = array();

		foreach ($yuyuearr1 as $k => $v ) {
			$yuyuearr[$v['guanjianci']] = array('yuyue' => $v['yuyueliang']);
		}

		unset($yuyuearr1);
		$daozhenearr1 = $managezx->query($daozhensql);
		$daozhenearr = array();

		foreach ($daozhenearr1 as $k => $v ) {
			$daozhenearr[$v['guanjianci']] = array('daozhen' => $v['daozhenliang']);
		}

		unset($daozhenearr1);
		$guanjianci_biaoxian = array_merge_recursive($zixunarr, $yuyuearr, $daozhenearr);
		unset($zixunarr);
		unset($yuyuearr);
		unset($daozhenearr);
		$itemInfo_ss = array();

		foreach ($baidu_sousuoci as $k => $v ) {
			if (!isset($itemInfo_ss[$v['keywordid']]['sousuoci'][$v['sousuoci']])) {
				$itemInfo_ss[$v['keywordid']]['sousuoci'][$v['sousuoci']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'sousuoci' => $v['sousuoci'], 'zixun' => $guanjianci_biaoxian[$v['sousuoci']]['zixun'], 'yuyue' => $guanjianci_biaoxian[$v['sousuoci']]['yuyue'], 'daozhen' => $guanjianci_biaoxian[$v['sousuoci']]['daozhen']);
			}
			else {
				$itemInfo_ss[$v['keywordid']]['sousuoci'][$v['sousuoci']] = array('dianji' => $itemInfo_ss[$v['keywordid']]['sousuoci'][$v['sousuoci']]['dianji'] + $v['dianji'], 'zhanxian' => $itemInfo_ss[$v['keywordid']]['sousuoci'][$v['sousuoci']]['zhanxian'] + $v['zhanxian'], 'sousuoci' => $v['sousuoci'], 'zixun' => $guanjianci_biaoxian[$v['sousuoci']]['zixun'], 'yuyue' => $guanjianci_biaoxian[$v['sousuoci']]['yuyue'], 'daozhen' => $guanjianci_biaoxian[$v['sousuoci']]['daozhen']);
			}
		}

		unset($baidu_sousuoci);
		$list = array_merge_recursive($itemInfo, $itemInfo_ss);
		unset($itemInfo);
		unset($itemInfo_ss);

		foreach ($list as $k => $v ) {
			foreach ($v['sousuoci'] as $key => $val ) {
				$list[$k]['sousuoci'][$key]['xiaofei'] = $list[$k]['sousuoci'][$key]['dianji'] * $list[$k]['danjia'];
				$zixunliang = $zixunliang + $list[$k]['sousuoci'][$key]['zixun'];
				$yuyueliang = $yuyueliang + $list[$k]['sousuoci'][$key]['yuyue'];
				$daozhenliang = $daozhenliang + $list[$k]['sousuoci'][$key]['daozhen'];
			}

			$list[$k]['zixunheji'] = $list[$k]['keywordidzixun'];
			$list[$k]['yuyueheji'] = $yuyueliang;
			$list[$k]['daozhenheji'] = $list[$k]['keywordiddaozhen'];

			if (0 < $list[$k]['zixunheji']) {
				$list[$k]['zixunchengben'] = round($list[$k]['xiaofei'] / $list[$k]['zixunheji'], 2);
			}
			else {
				$list[$k]['zixunchengben'] = $list[$k]['xiaofei'];
			}

			if (0 < $list[$k]['daozhenheji']) {
				$list[$k]['daozhenchengben'] = round($list[$k]['xiaofei'] / $list[$k]['daozhenheji'], 2);
			}
			else {
				$list[$k]['daozhenchengben'] = $list[$k]['xiaofei'];
			}

			unset($zixunliang);
			unset($yuyueliang);
			unset($daozhenliang);
		}

		if (!empty($canshu['yy_ID'])) {
			$this->assign('list', $list);
		}

		$this->assign('xuhao', 1);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('zx_timeStart2', $canshu['zx_timeStart2']);
		$this->assign('zx_timeEnd2', $canshu['zx_timeEnd2']);

		foreach ($list as $k => $v ) {
			$list_daochu[$k]['jihuaName'] = $v['jihuaName'];
			$list_daochu[$k]['pipeici'] = $v['pipeici'];
			$list_daochu[$k]['zhanxian'] = $v['zhanxian'];
			$list_daochu[$k]['dianji'] = $v['dianji'];
			$list_daochu[$k]['xiaofei'] = $v['xiaofei'];
			$list_daochu[$k]['danjia'] = $v['danjia'];
			$list_daochu[$k]['zixunheji'] = $v['zixunheji'];
			$list_daochu[$k]['zixunchengben'] = $v['zixunchengben'];
			$list_daochu[$k]['daozhenheji'] = $v['daozhenheji'];
			$list_daochu[$k]['daozhenchengben'] = $v['daozhenchengben'];
		}

		$daochu = json_encode($list_daochu);
		$this->assign('daochu', $daochu);
		$this->display();
	}

	public function ExcleDC_lishisousuociid()
	{
		$arr1 = json_decode($_POST['excle']);
		$arr = get_object_vars($arr1);
		$zx_timeStart = $_POST['zx_timeStart'];
		$zx_timeEnd = $_POST['zx_timeEnd'];

		foreach ($arr as $k => $v ) {
			$arr[$k] = get_object_vars($v);
		}

		if (10000 < count($arr)) {
			echo '<script language=\'javascript\'>alert(\'最多一次导出条数10000条！请分批次导出\');history.back();</script>';
		}

		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator('liufeng')->setLastModifiedBy('Maarten Balliauw')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Test result file');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '序号')->setCellValue('B1', '计划名')->setCellValue('C1', '关键词')->setCellValue('D1', '展现')->setCellValue('E1', '点击')->setCellValue('F1', '消费')->setCellValue('G1', '单价')->setCellValue('H1', '咨询量')->setCellValue('I1', '咨询成本')->setCellValue('J1', '到诊量')->setCellValue('K1', '到诊成本');
		$num = '2';

		foreach ($arr as $k => $v ) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $num - 1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $v['jihuaName']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $v['pipeici']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $v['zhanxian']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $v['dianji']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $v['xiaofei']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $v['danjia']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $v['zixunheji']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $num, $v['zixunchengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $num, $v['daozhenheji']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $num, $v['daozhenchengben']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		header('Content-Disposition:inline;filename="关键词报表(' . $zx_timeStart . '至' . $zx_timeEnd . ').xls"');
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_bingzhongxiaofei()
	{
		$canshu = i('request.');

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
			}
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		if ($canshu['zhanghushebei'] == 0) {
			$this->assign('selectd1', 'selected');
			$this->assign('duankou', '全部设备');
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd2', 'selected');
			$this->assign('duankou', 'PC端');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd3', 'selected');
			$this->assign('duankou', '手机端');
		}

		$this->assign('tishi', 'PC端/移动端选项，仅对百度消费产生结果变化；对到诊量没有产生变化');
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
		$zhanghu = m('baiduzhanghu');
		$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
		$this->assign('zhanghurow', $zhanghurow);
		$this->assign('zhanghuID', $canshu['zhanghuID']);

		if (8035200 < ($endtime - $starttime)) {
			js_alert('', '日期不支持超过三个月');
			exit();
		}

		if ($canshu['submit1'] != '') {
			if (!empty($canshu['yy_ID'])) {
				$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
				$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
				$setdevice = $canshu['zhanghushebei'];
				$levelOfDetails = 3;
				$yy_ID = $canshu['yy_ID'];
				$setUnitOfTime = 8;
				$zhanghuID = $canshu['zhanghuID'];
				$baidufanhui = $this->baiduzhanghu_fenriqi($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);
				$qian = array(' ', '　', '	', "\n", "\r");
				$hou = array('', '', '', '', '');
				$zhanghuInfo = m('baiduzhanghu');

				if ($zhanghuID != '') {
					$zhanghuguize = $zhanghuInfo->where('zhanghu_ID=' . $zhanghuID . '')->getField('zhanghuguize');
					$zhanghuguize = str_replace('；', ';', $zhanghuguize);
					$zhanghuguize = trim($zhanghuguize, ';');
					$zhanghuguize = str_replace($qian, $hou, $zhanghuguize);
					$guizearr = explode(';', $zhanghuguize);
					$guizeok = array();
					$guizearr = array_filter($guizearr);

					foreach ($guizearr as $k => $v ) {
						$guizeok1 = explode('=', $v);
						$guizeok[$guizeok1[0]] = $guizeok1[1];
					}
				}
				else {
					$zhanghuguize = $zhanghuInfo->where('yy_ID=' . $canshu['yy_ID'] . '')->select();

					foreach ($zhanghuguize as $k => $v ) {
						$zhanghuguize1 = str_replace('；', ';', $zhanghuguize[$k]['zhanghuguize']);
						$zhanghuguize1 = trim($zhanghuguize1, ';');
						$zhanghuguize1 = str_replace($qian, $hou, $zhanghuguize1);
						$guizezifuchuan .= $zhanghuguize1 . ';';
					}

					$guizezifuchuan = trim($guizezifuchuan, ';');
					$guizearr = explode(';', $guizezifuchuan);
					$guizeok = array();

					foreach ($guizearr as $k => $v ) {
						$guizeok1 = explode('-', $v);
						$guizeok[$guizeok1[0]] = $guizeok1[1];
					}
				}

				foreach ($baidufanhui as $k2 => $v2 ) {
					foreach ($baidufanhui[$k2] as $k3 => $v3 ) {
						$xiaofeiheji = $xiaofeiheji + $v3['xiaofei'];
					}
				}

				foreach ($guizeok as $k => $v ) {
					foreach ($baidufanhui as $k2 => $v2 ) {
						foreach ($baidufanhui[$k2] as $k3 => $v3 ) {
							if (stristr($v3['jihuaName'], $k)) {
								$xiaofei = $xiaofei + $v3['xiaofei'];
								$dianji = $dianji + $v3['dianji'];
								$zhanxian = $zhanxian + $v3['zhanxian'];
							}
						}
					}

					$datas[$v]['xiaofei'] = $xiaofei;
					$datas[$v]['dianji'] = $dianji;
					$datas[$v]['zhanxian'] = $zhanxian;
					unset($xiaofei);
					unset($dianji);
					unset($zhanxian);
				}

				$bingzhongInfo = m('bingzhong');

				foreach ($datas as $k => $v ) {
					$bzsql = 'select bz_name,ID,bz_son from oa_bingzhong where bz_name = \'' . trim($k) . '\' and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)';
					$bzname = $bingzhongInfo->query($bzsql);

					if ($bzname[0]['bz_name'] != '') {
						if (($bzname[0]['bz_son'] == 0) || ($bzname[0]['bz_son'] == '')) {
							$zixunsql = 'select count(managezx_ID) as total from oa_managezx ' . "\r\n" . '										     where  zx_time>=\'' . $StartDate . '\' and  zx_time<=\'' . $setEndDate . '\' ' . $tiaojian . '   ' . "\r\n" . '										     and bz_ID =' . $bzname[0]['ID'] . '';
							$yuyuesql = 'select count(managezx_ID) as total from oa_managezx ' . "\r\n" . '										     where  zx_time>=\'' . $StartDate . '\' and  zx_time<=\'' . $setEndDate . '\' ' . $tiaojian . '  and shifouyuyue=0  ' . "\r\n" . '										     and bz_ID =' . $bzname[0]['ID'] . '';
							$daozhensql = 'select count(managezx_ID) as total from oa_managezx ' . "\r\n" . '										     where  daozhen_time>=\'' . $StartDate . '\' and  daozhen_time<=\'' . $setEndDate . '\' ' . $tiaojian . ' and shifoudaozhen=0  ' . "\r\n" . '										     and bz_ID =' . $bzname[0]['ID'] . ' ';
						}
						else {
							$zixunsql = 'select count(managezx_ID) as total from oa_managezx ' . "\r\n" . '										      where   zx_time>=\'' . $StartDate . '\' and  zx_time<=\'' . $setEndDate . '\' ' . $tiaojian . '   ' . "\r\n" . '										      and bz_ID in(' . $bzname[0]['ID'] . ',' . rtrim($bzname[0]['bz_son'], ',') . ') ';
							$yuyuesql = 'select count(managezx_ID) as total from oa_managezx ' . "\r\n" . '										      where   zx_time>=\'' . $StartDate . '\' and  zx_time<=\'' . $setEndDate . '\' ' . $tiaojian . '   and shifouyuyue=0 ' . "\r\n" . '										      and bz_ID in(' . $bzname[0]['ID'] . ',' . rtrim($bzname[0]['bz_son'], ',') . ') ';
							$daozhensql = 'select count(managezx_ID) as total from oa_managezx ' . "\r\n" . '										      where   daozhen_time>=\'' . $StartDate . '\' and  daozhen_time<=\'' . $setEndDate . '\' ' . $tiaojian . ' and shifoudaozhen=0  ' . "\r\n" . '										      and bz_ID in(' . $bzname[0]['ID'] . ',' . rtrim($bzname[0]['bz_son'], ',') . ') ';
						}

						$total = $bingzhongInfo->query($daozhensql);
						$zx_total = $bingzhongInfo->query($zixunsql);
						$yy_total = $bingzhongInfo->query($yuyuesql);
						$dingji = $bingzhongInfo->where('bz_name=\'' . $bzname[0]['bz_name'] . '\'')->getField('bz_level');

						if ($dingji == 1) {
							$beijingse = '#E0E0E0';
						}
					}

					$dianjilv1 = round($v['dianji'] / $v['zhanxian'], 4);
					$dianjilv = ($dianjilv1 * 100) . '%';
					$array = array('bz_ID' => $bzname[0]['ID'], 'bingzhong' => $k, 'xiaofei' => $v['xiaofei'], 'dianji' => $v['dianji'], 'dianjilv' => $dianjilv, 'zhanxian' => $v['zhanxian'], 'zixunliang' => $zx_total[0]['total'], 'yuyueliang' => $yy_total[0]['total'], 'daozhen' => $total[0]['total'], 'daozhenchengben' => round($v['xiaofei'] / $total[0]['total'], 2), 'xiaofeiheji' => $xiaofeiheji, 'baifenbi' => round(($v['xiaofei'] / $xiaofeiheji) * 100, 2) . '%', 'backgroundcolor' => $beijingse);
					$list[$k] = $array;
					unset($total);
					unset($zx_total);
					unset($yy_total);
					unset($beijingse);
				}
			}
		}

		$this->assign('list', $list);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->display();
	}

	public function report_zhaoluye()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');
		$urljiance = $canshu['jiance'];

		if ($canshu['submit1'] != '') {
			if (empty($canshu['yy_ID'])) {
				js_alert('', '请先选择 所属门店');
				exit();
			}

			if (empty($canshu['zhanghuID'])) {
				js_alert('', '请选择 账户');
				exit();
			}
		}

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

		if (8035200 < ($endtime - $starttime)) {
			js_alert('', '日期不支持超过三个月');
			exit();
		}

		if ($canshu['zhanghushebei'] == 1) {
			$this->assign('selectd2', 'selected');
			$this->assign('duankou', 'PC端');
		}

		if ($canshu['zhanghushebei'] == 2) {
			$this->assign('selectd3', 'selected');
			$this->assign('duankou', '手机端');
		}

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['submit1'] != '') {
			$this->assign('shuoming', '说明: <br>' . "\r\n" . '				 1， 账户和商务通导入的  着陆页URL 参数分隔符为 ? ;<br>' . "\r\n" . '				 2， 移动 PC 唯一符,推广URL等 是否在系统中设定； <br>' . "\r\n" . '				 3， keywordid 的参数名 是否为 bdkeyword;<br>' . "\r\n" . '				 4， keywordid后面的参数分隔符 是否为 &；' . "\r\n" . '				 ');
			$this->assign('zx_timeStart', $canshu['zx_timeStart']);
			$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
			$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
			$zhanghu = m('baiduzhanghu');
			$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
			$this->assign('zhanghurow', $zhanghurow);
			$this->assign('zhanghuID', $canshu['zhanghuID']);
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:00';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setDevice = $canshu['zhanghushebei'];
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 8;
			$zhanghuID = $canshu['zhanghuID'];
			$fanhuiziduan = array('impression', 'click', 'cost', 'cpc');
			$ReportType = 14;
			$levelOfDetails = 11;
			$report_ID = $this->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);
			$report_url = $this->getReportUrl($report_ID, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $this->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url = $this->getReportUrl($report_ID, $zhanghuID);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $report_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
			$guanjianciInfo = explode("\n", $result);
			unset($result);
			$baidu_guanjianci = array();
			$guanjianciheji = count($guanjianciInfo);

			for ($i = 1; $i < $guanjianciheji; $i++) {
				$guanjianciInfo[$i] = explode('	', $guanjianciInfo[$i]);

				if (0 < $guanjianciInfo[$i][11]) {
					$baidu_guanjianci[$i] = array('zhanxian' => $guanjianciInfo[$i][10], 'dianji' => $guanjianciInfo[$i][11], 'xiaofei' => $guanjianciInfo[$i][12], 'danjia' => $guanjianciInfo[$i][13], 'pipeici' => $guanjianciInfo[$i][9], 'riqi' => $guanjianciInfo[$i][0], 'zhanghuName' => $guanjianciInfo[$i][2], 'jihuaName' => $guanjianciInfo[$i][4], 'keywordid' => $guanjianciInfo[$i][7], 'wordid' => $guanjianciInfo[$i][8]);
				}
			}

			unset($guanjianciInfo);
			$zhanghuweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');

			if ($canshu['zhanghushebei'] == 1) {
				$diannaotongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('pcweiyifu');
				$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',oa_swtdaoru.chucifangwenURL) ';

				if (empty($diannaotongpeifu)) {
					js_alert('', 'PC端没有设置唯一符');
					exit();
				}
			}

			if ($canshu['zhanghushebei'] == 2) {
				$yidongweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('yidongweiyifu');
				$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',oa_swtdaoru.chucifangwenURL) ';

				if (empty($yidongweiyifu)) {
					js_alert('', '移动端没有设置唯一符');
					exit();
				}
			}

			$swtsqlzixun = 'select chucifangwenURL from oa_swtdaoru' . "\r\n" . '				 where yy_ID=' . $canshu['yy_ID'] . ' and zx_time>=\'' . $StartDate . '\' and zx_time<=\'' . $setEndDate . '\' and locate(\'' . $zhanghuweiyifu . '\',chucifangwenURL) ' . $tiaojian_zhanghu . '';
			$swtsqlyuyue = 'select oa_swtdaoru.chucifangwenURL from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>=\'' . $StartDate . '\' and oa_swtdaoru.zx_time<=\'' . $setEndDate . '\'  and locate(\'' . $zhanghuweiyifu . '\',oa_swtdaoru.chucifangwenURL) and oa_managezx.shifouyuyue = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
			$swtsqldaozhen = 'select oa_swtdaoru.chucifangwenURL from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>=\'' . $StartDate . '\' and oa_swtdaoru.zx_time<=\'' . $setEndDate . '\'  and locate(\'' . $zhanghuweiyifu . '\',oa_swtdaoru.chucifangwenURL) and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
			$chucifangwenURLRow = $zhanghu->query($swtsqlzixun);
			$gjcwyf = 'bdkeyword';

			foreach ($chucifangwenURLRow as $k => $v ) {
				$keywordid = tiqu($v['chucifangwenURL'], $gjcwyf);

				if ($keywordid != '') {
					$keywordrows .= $keywordid . ',';
				}
			}

			unset($chucifangwenURLRow);
			$keywordrows = rtrim($keywordrows, ',');
			$keywordarr = explode(',', $keywordrows);
			$keyworditem = array();

			foreach ($keywordarr as $k => $v ) {
				if (!isset($keyworditem[$v])) {
					$keyworditem[$v] = 1;
				}
				else {
					$keyworditem[$v]++;
				}
			}

			unset($keywordrows);
			unset($keywordarr);
			$chucifangwenURLRow = $zhanghu->query($swtsqlyuyue);

			foreach ($chucifangwenURLRow as $k => $v ) {
				$keywordid = tiqu($v['chucifangwenURL'], $gjcwyf);

				if ($keywordid != '') {
					$keywordrows .= $keywordid . ',';
				}
			}

			unset($chucifangwenURLRow);
			$keywordrows = rtrim($keywordrows, ',');
			$keywordarr = explode(',', $keywordrows);
			$keyworditem_dz = array();

			foreach ($keywordarr as $k => $v ) {
				if (!isset($keyworditem_yy[$v])) {
					$keyworditem_yy[$v] = 1;
				}
				else {
					$keyworditem_yy[$v]++;
				}
			}

			unset($keywordrows);
			unset($keywordarr);
			$chucifangwenURLRow = $zhanghu->query($swtsqldaozhen);

			foreach ($chucifangwenURLRow as $k => $v ) {
				$keywordid = tiqu($v['chucifangwenURL'], $gjcwyf);

				if ($keywordid != '') {
					$keywordrows .= $keywordid . ',';
				}
			}

			unset($chucifangwenURLRow);
			$keywordrows = rtrim($keywordrows, ',');
			$keywordarr = explode(',', $keywordrows);
			$keyworditem_dz = array();

			foreach ($keywordarr as $k => $v ) {
				if (!isset($keyworditem_dz[$v])) {
					$keyworditem_dz[$v] = 1;
				}
				else {
					$keyworditem_dz[$v]++;
				}
			}

			unset($keywordrows);
			unset($keywordarr);
			$item = array();

			foreach ($baidu_guanjianci as $k => $v ) {
				if (!isset($item[$v['keywordid']])) {
					$item[$v['keywordid']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'xiaofei' => $v['xiaofei'], 'danjia' => $v['danjia'], 'jihuaName' => $v['jihuaName'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $keyworditem[$v['keywordid']], 'keywordidyuyue' => $keyworditem_yy[$v['keywordid']], 'keywordiddaozhen' => $keyworditem_dz[$v['keywordid']], 'pipeici' => $v['pipeici']);
				}
				else {
					$item[$v['keywordid']] = array('dianji' => $item[$v['keywordid']]['dianji'] + $v['dianji'], 'zhanxian' => $item[$v['keywordid']]['zhanxian'] + $v['zhanxian'], 'xiaofei' => $item[$v['keywordid']]['xiaofei'] + $v['xiaofei'], 'jihuaName' => $v['jihuaName'], 'pipeici' => $v['pipeici'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $item[$v['keywordid']]['keywordidzixun'] + $keyworditem[$v['keywordid']], 'keywordidyuyue' => $item[$v['keywordid']]['keywordidyuyue'] + $keyworditem_yy[$v['keywordidyuyue']], 'keywordiddaozhen' => $item[$v['keywordid']]['keywordiddaozhen'] + $keyworditem_dz[$v['keywordiddaozhen']], 'danjia' => round(($item[$v['pipeici']]['xiaofei'] + $v['xiaofei']) / ($item[$v['pipeici']]['dianji'] + $v['dianji']), 2));
				}
			}

			unset($baidu_guanjianci);
			$itemInfo = arraysort($item, 'xiaofei', 'desc');
			unset($item);
			unset($keyworditem);
			unset($keyworditem_yy);
			unset($keyworditem_dz);

			foreach ($itemInfo as $k => $v ) {
				$arrkeyID[] = $k;
			}

			if (count($arrkeyID) < 10000) {
				$zhaoluye = $this->baiduzhanghu_getword($canshu['zhanghuID'], $arrkeyID);
			}
			else {
				js_alert('', '一次最多返回10000个有消费的关键词信息，请缩短查询时间段');
				exit();
			}
			function qucan($zifuchuan, $canshu)
			{
				$diyiciweizhi = strpos($zifuchuan, $canshu);
				$strin = substr($zifuchuan, 0, $diyiciweizhi);
				return $strin;
			}

			$canshu = '?';

			foreach ($zhaoluye as $k => $v ) {
				if ($setDevice == 2) {
					$url = qucan($v['YDURL'], $canshu);
				}
				else {
					$url = qucan($v['PCURL'], $canshu);
				}

				$zhaoluyearr[$zhaoluye[$k]['keywordid']] = array('URL' => $url, 'keywid' => $v['keywordid']);
			}

			unset($zhaoluye);
			$item = array_merge_recursive($itemInfo, $zhaoluyearr);
			$list = array();

			foreach ($item as $k => $v ) {
				if (!isset($list[$v['URL']])) {
					$list[$v['URL']] = array('dianji' => $v['dianji'], 'zhanxian' => $v['zhanxian'], 'xiaofei' => $v['xiaofei'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $v['keywordidzixun'], 'keywordidyuyue' => $v['keywordidyuyue'], 'keywordiddaozhen' => $v['keywordiddaozhen'], 'pipeici' => $v['pipeici']);
				}
				else {
					$list[$v['URL']] = array('dianji' => $list[$v['URL']]['dianji'] + $v['dianji'], 'zhanxian' => $list[$v['URL']]['zhanxian'] + $v['zhanxian'], 'xiaofei' => $list[$v['URL']]['xiaofei'] + $v['xiaofei'], 'keywordid' => $v['keywordid'], 'keywordidzixun' => $list[$v['URL']]['keywordidzixun'] + $v['keywordidzixun'], 'keywordidyuyue' => $list[$v['URL']]['keywordidyuyue'] + $v['keywordidyuyue'], 'keywordiddaozhen' => $list[$v['URL']]['keywordiddaozhen'] + $v['keywordiddaozhen'], 'pipeici' => $list[$v['URL']]['pipeici'] . '<br>' . $v['pipeici']);
				}
			}

			$this->assign('xuhao', 1);

			foreach ($list as $k => $v ) {
				$list[$k]['URL'] = $k;

				if ($list[$k]['keywordidzixun'] == '') {
					$list[$k]['keywordidzixun'] = 0;
				}

				if ($list[$k]['keywordidyuyue'] == '') {
					$list[$k]['keywordidyuyue'] = 0;
				}

				if ($list[$k]['keywordiddaozhen'] == '') {
					$list[$k]['keywordiddaozhen'] = 0;
				}

				if (0 < $list[$k]['keywordidzixun']) {
					$list[$k]['zixunchengben'] = round($list[$k]['xiaofei'] / $list[$k]['keywordidzixun'], 2);
				}
				else {
					$list[$k]['zixunchengben'] = $list[$k]['xiaofei'];
				}

				if (0 < $list[$k]['keywordidyuyue']) {
					$list[$k]['yuyuechengben'] = round($list[$k]['xiaofei'] / $list[$k]['keywordidyuyue'], 2);
				}
				else {
					$list[$k]['yuyuechengben'] = $list[$k]['xiaofei'];
				}

				if (0 < $list[$k]['keywordiddaozhen']) {
					$list[$k]['daozhenchengben'] = round($list[$k]['xiaofei'] / $list[$k]['keywordiddaozhen'], 2);
				}
				else {
					$list[$k]['daozhenchengben'] = $list[$k]['xiaofei'];
				}

				$list[$k]['dianjizixunlv'] = (round($list[$k]['keywordidzixun'] / $list[$k]['dianji'], 4) * 100) . '%';
			}

			if ($urljiance == 1) {
				$this->assign('selectd_jc', 'selected');

				foreach ($list as $k => $v ) {
					$fangwen = httpcode($list[$k]['URL']);

					if ($fangwen == '200') {
						$list[$k]['fangwen'] = '[正常]';
					}
					else {
						$list[$k]['fangwen'] = '<font color="red">[异常]</font>';
					}
				}
			}

			$this->assign('list', $list);
			$daochu = serialize($list);
			$this->assign('daochu', $daochu);
		}

		$this->display();
	}

	public function ExcleDC_zhaoluye()
	{
		$arr = array_reverse(unserialize($_POST['excle']));
		$arr = array_values($arr);
		$zx_timeStart = $_POST['zx_timeStart'];
		$zx_timeEnd = $_POST['zx_timeEnd'];
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator('liufeng')->setLastModifiedBy('Maarten Balliauw')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Test result file');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'URL')->setCellValue('B1', '展现')->setCellValue('C1', '点击')->setCellValue('D1', '消费')->setCellValue('E1', '点击咨询率')->setCellValue('F1', '咨询量')->setCellValue('G1', '预约量')->setCellValue('H1', '到诊量')->setCellValue('I1', '咨询成本')->setCellValue('J1', '预约成本')->setCellValue('K1', '到诊成本');
		$num = '2';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['URL']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zhanxian']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['dianji']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['xiaofei']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['dianjizixunlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['keywordidzixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['keywordidyuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['keywordiddaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $num, $arr[$i]['zixunchengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $num, $arr[$i]['yuyuechengben']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $num, $arr[$i]['daozhenchengben']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		header('Content-Disposition:inline;filename="着陆页报表(' . $zx_timeStart . '至' . $zx_timeEnd . ').xls"');
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		saveviatempfile($objWriter);
		exit();
	}

	public function baiduzhanghu_getword($zhanghuID, $arrkeyID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new KeywordServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = $testService->get($arrkeyID);
		$datesheji = count($datas);

		for ($i = 0; $i < $datesheji; $i++) {
			$zhanghuarray[$i] = array('PCURL' => $datas[$i]->pcDestinationUrl, 'YDURL' => $datas[$i]->mobileDestinationUrl, 'keywordid' => (string) $datas[$i]->keywordId);
		}

		return $zhanghuarray;
	}

	public function report_shijianduan_jihua()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');
		$jihuaInfo = $canshu['campaignxz'];
		unset($canshu['campaignxz']);
		$canshu['campaignxz'] = array();

		foreach ($jihuaInfo as $k => $v ) {
			$jihua_fenli = explode('|', $v);
			array_push($canshu['campaignxz'], $jihua_fenli[0]);
			$xuanzhongjihua .= $jihua_fenli[1] . ', ';
		}

		if (empty($canshu['zx_timeStart'])) {
			$canshu['zx_timeStart'] = date('Y-m-01');
		}

		if (empty($canshu['zx_timeEnd'])) {
			$canshu['zx_timeEnd'] = date('Y-m-d');
		}

		$starttime = strtotime($canshu['zx_timeStart']);
		$endtime = strtotime($canshu['zx_timeEnd']);
		$sql = 'select zhanghuming,zhanghu_ID from oa_baiduzhanghu where yy_ID=' . $canshu['yy_ID'] . ' and zhanghudel=0 order by yy_ID desc';
		$zhanghu = m('baiduzhanghu');
		$zhanghurow = $zhanghu->where('zhanghudel=0 and yy_ID=' . $canshu['yy_ID'] . '')->select();
		$this->assign('zhanghurow', $zhanghurow);
		$this->assign('zhanghuID', $canshu['zhanghuID']);
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$shijiandian = array('00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00');

		if ($canshu['submit'] != '') {
			$dqtime = strtotime(date('Y-m-d H:i:s'));

			if (2592000 < ($dqtime - $starttime)) {
				js_alert('', '1百度竞价账户 时间段表仅支持最近一个月数据；请注意导出EXCLE表备份');
				exit();
			}

			if (2592000 < ($endtime - $starttime)) {
				js_alert('', '2百度竞价账户 时间段表仅支持最近一个月数据；请注意导出EXCLE表备份');
				exit();
			}
		}

		$this->assign('active_tian', 'active');
		$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

		if (!empty($canshu['yy_ID'])) {
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setdevice = 0;
			$zhanghuID = $canshu['zhanghuID'];

			if (empty($canshu['campaignxz'])) {
				js_alert('', '请选择要查看的计划');
				exit();
			}
			else {
				$this->assign('xzjh', '您选中的计划为：' . $xuanzhongjihua);
				$arrfanhui = array('impression', 'click', 'cost', 'cpc');
				$LevelOfDetails = 3;
				$yy_ID = $canshu['yy_ID'];
				$ReportType = 10;
				$unitOfTime = 7;
				$report_ID = $this->yibubaogao_ID_jh($StartDate, $setEndDate, $arrfanhui, $LevelOfDetails, $ReportType, $setDevice, $zhanghuID, $unitOfTime);

				do {
					sleep(1);
					$reportstate = $this->getReportState($report_ID, $zhanghuID);
				} while ($reportstate != 3);

				$report_url = $this->getReportUrl($report_ID, $zhanghuID);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $report_url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);
				$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
				$baidufanhui = explode("\n", $result);
				unset($result);

				foreach ($baidufanhui as $k => $v ) {
					$baidufanhui[$k] = explode('	', $v);

					if (!in_array($baidufanhui[$k]['5'], $canshu['campaignxz'])) {
						unset($baidufanhui[$k]);
					}
					else if (strlen($baidufanhui[$k]['7']) < 2) {
						$baidufanhui[$k]['7'] = '0' . $baidufanhui[$k]['7'] . ':00';
					}
					else {
						$baidufanhui[$k]['7'] = $baidufanhui[$k]['7'] . ':00';
					}
				}
			}

			$this->assign('shijianduan_keyword', 'zx_timeStart=' . $canshu['zx_timeStart'] . '&zx_timeEnd=' . $canshu['zx_timeEnd'] . '&yy_ID=' . $canshu['yy_ID'] . '');

			if ($canshu['zixunlaiyuan'] == 0) {
				if (($canshu['swtsj'] == 1) && empty($canshu['zhanghuID'])) {
					$tiaojian_zhanghu = '';
					$this->assign('selectd_swtzong', 'selected');
				}
				else {
					if (($canshu['swtsj'] == 0) && empty($canshu['zhanghuID'])) {
						$jingjiatongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('jingjiatongpeifu');
						$tiaojian_zhanghu = 'and locate(\'' . $jingjiatongpeifu . '\',oa_swtdaoru.chucifangwenURL)';
						$this->assign('selectd_swtbaidu', 'selected');
					}
					else {
						if (($canshu['swtsj'] == 1) && !empty($canshu['zhanghuID'])) {
							$tiaojian_zhanghu = '';
							$this->assign('selectd_swtzong', 'selected');
						}
						else {
							if (($canshu['swtsj'] == 0) && !empty($canshu['zhanghuID'])) {
								$weiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');
								$tiaojian_zhanghu = 'and locate(\'' . $weiyifu . '\',oa_swtdaoru.chucifangwenURL)';
								$this->assign('selectd_swtbaidu', 'selected');
							}
							else {
								echo 'error';
							}
						}
					}
				}

				$zixunliangSql = 'select zx_time,chucifangwenURL from oa_swtdaoru ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '									and yy_ID= ' . $canshu['yy_ID'] . ' ' . $tiaojian_zhanghu . ' order by zx_time';
				$yuyueliangSql = 'select oa_swtdaoru.zx_time,oa_swtdaoru.chucifangwenURL ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\'   and oa_managezx.shifouyuyue = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$daozhenliangSql = 'select oa_swtdaoru.zx_time,oa_swtdaoru.chucifangwenURL  ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID=' . $canshu['yy_ID'] . ' and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\' and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$setDevice = 0;
				$setUnitOfTime = 7;
				$zhanghuID = $canshu['zhanghuID'];
				$fanhuiziduan = array('impression', 'click', 'cost', 'cpc');
				$ReportType = 14;
				$levelOfDetails = 11;
				$report_ID = $this->yibubaogao_ID_jh($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID, $setUnitOfTime);

				do {
					$reportstate = $this->getReportState($report_ID, $zhanghuID);
				} while ($reportstate != 3);

				$report_url = $this->getReportUrl($report_ID, $zhanghuID);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $report_url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);
				$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII');
				$baidu_guanjianci = explode("\n", $result);
				$keywordID_arr = array();
				unset($result);

				foreach ($baidu_guanjianci as $k => $v ) {
					$baidu_guanjianci[$k] = explode('	', $v);

					if (!in_array($baidu_guanjianci[$k]['9'], $canshu['campaignxz'])) {
						unset($baidu_guanjianci[$k]);
					}
					else {
						if (strlen($baidu_guanjianci[$k]['12']) < 2) {
							$baidu_guanjianci[$k]['12'] = '0' . $baidu_guanjianci[$k]['12'] . ':00';
						}
						else {
							$baidu_guanjianci[$k]['12'] = $baidu_guanjianci[$k]['12'] . ':00';
						}

						array_push($keywordID_arr, $baidu_guanjianci[$k]['7']);
					}
				}

				$this->assign('selectd1', 'selected');
				$zixunliang1 = m('huanze');
				$zixunliang = $zixunliang1->query($zixunliangSql);
				$yuyueliang = $zixunliang1->query($yuyueliangSql);
				$daozhenliang = $zixunliang1->query($daozhenliangSql);
				$gjcwyf = 'bdkeyword';

				foreach ($zixunliang as $k => $v ) {
					$zx_kewordid = tiqu($zixunliang[$k]['chucifangwenURL'], $gjcwyf);

					if (!in_array($zx_kewordid, $keywordID_arr)) {
						unset($zixunliang[$k]);
					}
				}

				foreach ($yuyueliang as $k => $v ) {
					$yy_kewordid = tiqu($yuyueliang[$k]['chucifangwenURL'], $gjcwyf);

					if (!in_array($yy_kewordid, $keywordID_arr)) {
						unset($yuyueliang[$k]);
					}
				}

				foreach ($daozhenliang as $k => $v ) {
					$dz_kewordid = tiqu($daozhenliang[$k]['chucifangwenURL'], $gjcwyf);

					if (!in_array($dz_kewordid, $keywordID_arr)) {
						unset($daozhenliang[$k]);
					}
				}
			}
			else {
				$zixunliangSql = 'select zx_ID,zx_time,shifoudaozhen from oa_huanze ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . $tiaojian . ' order by zx_time';
				$yuyueliangSql = 'select zx_ID,zx_time,shifoudaozhen from oa_huanze ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0 ' . $tiaojian . ' order by zx_time';
				$daozhenliangSql = 'select zx_ID,zx_time,shifoudaozhen from oa_huanze ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0 ' . $tiaojian . ' order by zx_time';
				$this->assign('selectd2', 'selected');
				$zixunliang1 = m('huanze');
				$zixunliang = $zixunliang1->query($zixunliangSql);
				$yuyueliang = $zixunliang1->query($yuyueliangSql);
				$daozhenliang = $zixunliang1->query($daozhenliangSql);
			}

			for ($i = 0; $i <= count($zixunliang) - 1; $i++) {
				$zixunliang[$i]['zx_time'] = substr($zixunliang[$i]['zx_time'], 11, 2);
			}

			for ($i = 0; $i <= count($yuyueliang) - 1; $i++) {
				$yuyueliang[$i]['zx_time'] = substr($yuyueliang[$i]['zx_time'], 11, 2);
			}

			for ($i = 0; $i <= count($daozhenliang) - 1; $i++) {
				$daozhenliang[$i]['zx_time'] = substr($daozhenliang[$i]['zx_time'], 11, 2);
			}

			for ($i = 0; $i <= count($shijiandian) - 1; $i++) {
				$shijianzixun = 0;
				$shijianyuyue = 0;
				$shijiandaozhen = 0;

				foreach ($baidufanhui as $k => $v ) {
					if ($v[7] == $shijiandian[$i]) {
						$baiduheji = $baiduheji + $baidufanhui[$k][2];
						$baidudianji = $baidudianji + $baidufanhui[$k][1];
						$baiduzhanxian = $baiduzhanxian + $baidufanhui[$k][0];
						unset($baidufanhui[$k]);
					}
				}

				$xiaoshi = substr($shijiandian[$i], 0, 2);

				foreach ($zixunliang as $k => $v ) {
					if ($zixunliang[$k]['zx_time'] == $xiaoshi) {
						$shijianzixun++;
						unset($zixunliang[$k]);
					}
				}

				foreach ($yuyueliang as $k => $v ) {
					if ($yuyueliang[$k]['zx_time'] == $xiaoshi) {
						$shijianyuyue++;
						unset($yuyueliang[$k]);
					}
				}

				foreach ($daozhenliang as $k => $v ) {
					if ($daozhenliang[$k]['zx_time'] == $xiaoshi) {
						$shijiandaozhen++;
						unset($daozhenliang[$k]);
					}
				}

				$dianjilv1 = $baidudianji / $baiduzhanxian;
				$dianjilv2 = round($dianjilv1, 4);
				$zixunlv1 = $shijianzixun / $baidudianji;
				$zixunlv2 = round($zixunlv1, 4);
				$zixunchengben1 = $baiduheji / $shijianzixun;
				$zixunchengben = round($zixunchengben1, 2);
				$baidu[$i] = array('shijiandian' => $shijiandian[$i], 'xiaofei' => $baiduheji, 'dianji' => $baidudianji, 'shijianzixun' => $shijianzixun, 'shijianyuyue' => $shijianyuyue, 'shijiandaozhen' => $shijiandaozhen, 'zhanxian' => $baiduzhanxian, 'zixunlv' => ($zixunlv2 * 100) . '%', 'dianjilv' => ($dianjilv2 * 100) . '%', 'zixunchengben' => $zixunchengben);

				if ($baidu[$i]['zhanxian'] == '') {
					unset($baidu[$i]);
				}

				unset($baiduheji);
				unset($baidudianji);
				unset($baiduzhanxian);
			}
		}

		$this->assign('shiduanheji', $baidu);
		$daochu = serialize($baidu);
		$this->assign('daochu', $daochu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);

		foreach ($baidu as $k => $v ) {
			$tubiao_riqi .= '\'' . $v['shijiandian'] . '\',';
			$tubiao_toufang .= '' . $v['xiaofei'] . ',';
			$tubiao_dianji .= $v['dianji'] . ',';
			$tubiao_zixun .= $v['shijianzixun'] . ',';
			$tubiao_yuyue .= $v['shijianyuyue'] . ',';
			$tubiao_daozhen .= $v['shijiandaozhen'] . ',';
			$tubiao_zixunchengben .= $v['zixunchengben'] . ',';
		}

		$tubiao_riqi = rtrim($tubiao_riqi, ',');
		$tubiao_dianji = rtrim($tubiao_dianji, ',');
		$tubiao_zixun = rtrim($tubiao_zixun, ',');
		$tubiao_yuyue = rtrim($tubiao_yuyue, ',');
		$tubiao_daozhen = rtrim($tubiao_daozhen, ',');
		$tubiao_toufang = rtrim($tubiao_toufang, ',');
		$tubiao_zixunchengben = rtrim($tubiao_zixunchengben, ',');
		$this->assign('tubiao_riqi', $tubiao_riqi);
		$this->assign('tubiao_toufang', $tubiao_toufang);
		$this->assign('tubiao_dianji', $tubiao_dianji);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('tubiao_zixunchengben', $tubiao_zixunchengben);
		$this->assign('tubiao_zixunhj', $rows_hj[0]['zixun']);
		$this->assign('tubiao_yuyuehj', $rows_hj[0]['yuyue']);
		$this->assign('tubiao_daozhenhj', $rows_hj[0]['daozhen']);

		foreach ($baidu as $k => $v ) {
			$sdheji['zhanxian'] = $sdheji['zhanxian'] + $v['zhanxian'];
			$sdheji['dianji'] = $sdheji['dianji'] + $v['dianji'];
			$sdheji['xiaofei'] = $sdheji['xiaofei'] + $v['xiaofei'];
			$sdheji['shijianzixun'] = $sdheji['shijianzixun'] + $v['shijianzixun'];
			$sdheji['shijianyuyue'] = $sdheji['shijianyuyue'] + $v['shijianyuyue'];
			$sdheji['shijiandaozhen'] = $sdheji['shijiandaozhen'] + $v['shijiandaozhen'];
		}

		$zixunchengben1 = $sdheji['xiaofei'] / $sdheji['shijianzixun'];
		$sdheji['zixunchengben'] = round($zixunchengben1, 2);
		$dianjilv1 = $sdheji['dianji'] / $sdheji['zhanxian'];
		$sdheji['dianjilv'] = (round($dianjilv1, 4) * 100) . '%';
		$zixunlv1 = $sdheji['shijianzixun'] / $sdheji['dianji'];
		$sdheji['zixunlv'] = (round($zixunlv1, 4) * 100) . '%';
		$this->assign('sdheji', $sdheji);
		$this->display();
	}

	public function report_shijianduan_jihua_huoqu()
	{
		$zhanghuID = $_POST['zhanghuID'];
		$jihuaInfo = $this->get_campaign($zhanghuID);
		echo '<table class="table table-striped table-bordered table-hover display" cellspacing="0" id="screening">';
		echo '  <thead>';
		echo '	  <tr>';
		echo '		  <th nowrap><input type="checkbox" id="checkAll"><label for="checkAll"> 全选</label></th>';
		echo '		  <th nowrap>计划</th>';
		echo '	  </tr>';
		echo '  </thead>';
		echo '  <tbody id="jihuaneirong">';

		foreach ($jihuaInfo as $k => $v ) {
			echo '		 <tr>';
			echo '		  <td><input type="checkbox" name="campaignxz[]" value=' . $v['campaignId'] . '|' . $v['campaignName'] . '></td>';
			echo '		  <td>' . $v['campaignName'] . '</td>';
			echo '		 </tr>';
		}

		echo '  </tbody>';
		echo '</table>';
	}

	public function get_campaign($zhanghuID)
	{
		$zhanghuInfo1 = m('baiduzhanghu');
		$zhanghuInfo = $zhanghuInfo1->query('select * from oa_baiduzhanghu where zhanghudel=0 and zhanghu_ID=' . $zhanghuID . ' order by yy_ID');
		$testService = new CampaignServiceTest();
		$newheader = new \Component\AuthHeader();
		$newheader->setUsername($zhanghuInfo[0]['zhanghuming']);
		$newheader->setPassword(base64_decode($zhanghuInfo[0]['zhanghumima']));
		$newheader->setToken($zhanghuInfo[0]['zhanghutoken']);
		$testService->setAuthHeader($newheader);
		$datas = NULL;
		$datas = $testService->get($datas);

		foreach ($datas as $k => $v ) {
			$zhanghuarray[$k] = array('campaignName' => $datas[$k]->campaignName, 'campaignId' => (string) $datas[$k]->campaignId);
		}

		return $zhanghuarray;
	}

	public function report_shijianduan_keyword()
	{
		$canshu = i('get.');
		print_r($canshu);
		$this->display();
	}
}


?>
