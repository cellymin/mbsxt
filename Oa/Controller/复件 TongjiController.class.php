<?php

namespace OA\Controller;
use PHPExcel_IOFactory;



class TongjiController extends \Component\AdminController
{
	public function report_tongji()
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

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		if (($canshu['tian_geshi'] == '天') || ($canshu['tian_geshi'] == '')) {
			if (8035200 < ($endtime - $starttime)) {
				js_alert('', '日期不支持超过三个月');
				exit();
			}

			$this->assign('active_tian', 'active');
			$arrDate = prdates($canshu['zx_timeStart'], $canshu['zx_timeEnd']);

			for ($i = 0; $i <= count($arrDate) - 1; $i++) {
				$zixunliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' ' . $tiaojian . '';
				$zixunliang = mysql_query($zixunliangSql);

				while ($aaa = mysql_fetch_array($zixunliang)) {
					$zixun = $aaa['total'];
				}

				$zixun_heji = $zixun_heji + $zixun;
				$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '		  where zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' and shifouyuyue=0' . $tiaojian . '';
				$yuyueliang = mysql_query($yuyueliangSql);

				while ($aaa = mysql_fetch_array($yuyueliang)) {
					$yuyue = $aaa['total'];
				}

				$yuyue_heji = $yuyue_heji + $yuyue;
				$daozhenliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '		  where daozhen_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and daozhen_time <= \'' . $arrDate[$i] . ' 23:59:59\' and shifoudaozhen=0' . $tiaojian . '';
				$daozhenliang = mysql_query($daozhenliangSql);

				while ($aaa = mysql_fetch_array($daozhenliang)) {
					$daozhen = $aaa['total'];
				}

				$daozhen_heji = $daozhen_heji + $daozhen;
				$daozhenliangSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '		  where yuyueTime  >= \'' . $arrDate[$i] . ' 00:00:00\' and yuyueTime <= \'' . $arrDate[$i] . ' 23:59:59\' and shifouyuyue=0' . $tiaojian . '';
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
		}

		if ($canshu['tian_geshi'] == '月') {
			$starttime = strtotime($canshu['zx_timeStart2']);
			$endtime = strtotime($canshu['zx_timeEnd2']);

			if (63072000 < ($endtime - $starttime)) {
				js_alert('', '日期不支持超过24个月');
				exit();
			}

			$this->assign('checked_yue', 'checked');
			$this->assign('active_yue', 'active');
			$arrDate = monthlist($starttime, $endtime);

			for ($i = 0; $i <= count($arrDate) - 1; $i++) {
				if (($canshu['tian_geshi'] == '月') && ($canshu['tianshu'] == '')) {
					$zixunliangSql = 'select count(zx_ID) as  total  from oa_huanze  where DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' ' . $tiaojian . '';
					$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					    where DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifouyuyue=0' . $tiaojian . '';
					$daozhenliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					     where DATE_FORMAT(daozhen_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifoudaozhen=0' . $tiaojian . '';
					$daozhenliangSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where DATE_FORMAT(yuyueTime,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifouyuyue=0' . $tiaojian . '';
				}
				else {
					$zixunliangSql = 'select count(zx_ID) as  total  from oa_huanze  where  zx_time>=\'' . $arrDate[$i] . '-01 00:00:00\' and zx_time<=\'' . $arrDate[$i] . '-' . $canshu['tianshu'] . ' 23:59:59\' ' . $tiaojian . '';
					$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					    where DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' and shifouyuyue=0' . $tiaojian . '';
					$yuyueliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					    where zx_time>=\'' . $arrDate[$i] . '-01 00:00:00\' and zx_time<=\'' . $arrDate[$i] . '-' . $canshu['tianshu'] . ' 23:59:59\' and shifouyuyue=0' . $tiaojian . '';
					$daozhenliangSql = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					     where daozhen_time>=\'' . $arrDate[$i] . '-01 00:00:00\' and daozhen_time<=\'' . $arrDate[$i] . '-' . $canshu['tianshu'] . ' 23:59:59\' and shifoudaozhen=0' . $tiaojian . '';
					$daozhenliangSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '						  where yuyueTime>=\'' . $arrDate[$i] . '-01 00:00:00\' and yuyueTime<=\'' . $arrDate[$i] . '-' . $canshu['tianshu'] . ' 23:59:59\' and shifouyuyue=0' . $tiaojian . '';
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
				$rows[] = array('riqi' => $arrDate[$i], 'zixun' => $zixun, 'yuyue' => $yuyue, 'daozhen' => $daozhen, 'ydaozhen' => $daozhen1, 'yuyuelv' => $yuyuelv, 'daozhenlv' => $daozhenlv, 'zhuanhualv' => $zhuanhualv);
			}

			$yuyuelv_z = round(($yuyue_heji / $zixun_heji) * 100) . '%';
			$daozhenlv_z = round(($daozhen_heji / $yuyue_heji) * 100) . '%';
			$zhuanhualv_z = round(($daozhen_heji / $zixun_heji) * 100) . '%';
			$rows_hj[] = array('riqi' => '按月份 合计', 'zixun' => $zixun_heji, 'yuyue' => $yuyue_heji, 'daozhen' => $daozhen_heji, 'ydaozhen' => $ydaozhen_heji, 'yuyuelv' => $yuyuelv_z, 'daozhenlv' => $daozhenlv_z, 'zhuanhualv' => $zhuanhualv_z);
		}

		$this->assign('list', array_reverse($rows));
		$this->assign('list1', $rows_hj);
		$this->assign('dqpage', $_REQUEST['page']);
		$this->assign('dqURLcanshu', $URLcanshu);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('zx_timeStart2', $canshu['zx_timeStart2']);
		$this->assign('zx_timeEnd2', $canshu['zx_timeEnd2']);

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

		for ($i = 1; $i <= 31; $i++) {
			$tianshu[$i] = $i;
		}

		$this->assign('tianshu', $tianshu);
		$this->assign('xz_tianshu', $canshu['tianshu']);
		$daochu = serialize($rows);
		$daochu_heji = serialize($rows_hj);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->display();
	}

	public function ExcleDC_tongji()
	{
		$arr = array_reverse(unserialize($_POST['excle']));
		$arr_heji = unserialize($_POST['excle1']);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \Think\PHPExcel();
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
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '日期')->setCellValue('B1', '咨询量')->setCellValue('C1', '预约量')->setCellValue('D1', '实际到诊')->setCellValue('E1', '应到诊')->setCellValue('F1', '预约率')->setCellValue('G1', '到诊率')->setCellValue('H1', '转化率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['riqi']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['zhuanhualv']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['riqi']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['yuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['daozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['ydaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zhuanhualv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		header('Content-Disposition:inline;filename="(' . date('Y-m-d') . ')咨询量报表.xls"');
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_bingzhong()
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

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['bz_ID'] == '') {
			$canshu['bz_ID'] = 0;
		}

		$shuju = m('reportbzls');

		if ($canshu['submit1'] != '') {
			if ($canshu['yy_ID'] != '') {
				bingzhong_report($canshu['bz_ID'], $canshu['yy_ID'], $canshu, $tiaojian);
			}
			 else {
				js_alert('', '请选择门店');
			} 
		} 

		$list = $shuju->select();
		mysql_query('truncate oa_reportbzls');
		$tianshu = substr($canshu['zx_timeEnd'], 8, 2);

		for ($i = 0; $i <= count($list) - 2; $i++) {
			$list[$i]['rijun'] = round($list[$i]['daozhen'] / $tianshu, 2);
		}

		for ($i = 0; $i <= count($list) - 2; $i++) {
			if ($list[$i]['bz_level'] == 1) {
				$zixun_total = $zixun_total + $list[$i]['zixun'];
				$yuyue_total = $yuyue_total + $list[$i]['yuyue'];
				$daozhen_total = $daozhen_total + $list[$i]['daozhen'];
				$ydaozhen_total = $ydaozhen_total + $list[$i]['ydaozhen'];
			}
		}

		$list1[0]['bz_name'] = '咨询病种合计';
		$list1[0]['zixun'] = $zixun_total;
		$list1[0]['yuyue'] = $yuyue_total;
		$list1[0]['daozhen'] = $daozhen_total;
		$list1[0]['rijun'] = round($daozhen_total / $tianshu, 2);
		$list1[0]['ydaozhen'] = $ydaozhen_total;
		$list1[0]['yuyuelv'] = round(($yuyue_total / $zixun_total) * 100) . '%';
		$list1[0]['daozhenlv'] = round(($daozhen_total / $yuyue_total) * 100) . '%';
		$list1[0]['zhuanhualv'] = round(($daozhen_total / $zixun_total) * 100) . '%';
		$this->assign('list1', $list1);
		$this->assign('list', $list);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('num', 0);
		$yijitotal = 0;

		for ($i = 0; $i <= count($list) - 1; $i++) {
			if ($list[$i]['bz_level'] == 1) {
				$tubiao_bzName .= '\'' . $list[$i]['bz_name'] . '\',';
				$tubiao_zixun .= $list[$i]['zixun'] . ',';
				$tubiao_yuyue .= $list[$i]['yuyue'] . ',';
				$tubiao_daozhen .= $list[$i]['daozhen'] . ',';
				$yijitotal = $yijitotal + 1;
			}
		}

		$this->assign('yijitotal', ($yijitotal * 60) + 50);
		$tubiao_bzName = rtrim($tubiao_bzName, ',');
		$daochu = serialize($list);
		$daochu_heji = serialize($list1);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->assign('tubiao_bzName', $tubiao_bzName);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('tubiao_zixunhj', $rows_hj[0]['zixun']);
		$this->assign('tubiao_yuyuehj', $rows_hj[0]['yuyue']);
		$this->assign('tubiao_daozhenhj', $rows_hj[0]['daozhen']);
		$this->display();
	}

	public function ExcleDC_bingzhong()
	{
		$arr = unserialize($_POST['excle']);
		$arr_heji = unserialize($_POST['excle1']);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \Think\PHPExcel();
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
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '病种')->setCellValue('B1', '咨询量')->setCellValue('C1', '预约量')->setCellValue('D1', '实际到诊')->setCellValue('E1', '应到诊')->setCellValue('F1', '预约率')->setCellValue('G1', '到诊率')->setCellValue('H1', '转化率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['bz_name']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['zhuanhualv']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['bz_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['yuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['daozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['ydaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zhuanhualv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="(' . date('Y-m-d') . ')病种咨询报表.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_fangshi()
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

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['zxfs_ID'] == '') {
			$canshu['zxfs_ID'] = 0;
		}

		$shuju = m('reportzxfsls');

		if ($canshu['submit1'] != '') {
			if ($canshu['yy_ID'] != '') {
				fangshi_report($canshu['zxfs_ID'], $canshu['yy_ID'], $canshu, $tiaojian);
			}
			else {
				js_alert('', '请选择门店');
			}
		}

		$list = $shuju->select();
		mysql_query('truncate oa_reportzxfsls');

		for ($i = 0; $i <= count($list) - 1; $i++) {
			if ($list[$i]['zxfs_level'] == 1) {
				$zixun_total = $zixun_total + $list[$i]['zixun'];
				$yuyue_total = $yuyue_total + $list[$i]['yuyue'];
				$daozhen_total = $daozhen_total + $list[$i]['daozhen'];
				$ydaozhen_total = $ydaozhen_total + $list[$i]['ydaozhen'];
			}
		}

		$list1[0]['zxfs_name'] = '咨询方式合计(工具)';
		$list1[0]['zixun'] = $zixun_total;
		$list1[0]['yuyue'] = $yuyue_total;
		$list1[0]['daozhen'] = $daozhen_total;
		$list1[0]['ydaozhen'] = $ydaozhen_total;
		$list1[0]['yuyuelv'] = round(($yuyue_total / $zixun_total) * 100) . '%';
		$list1[0]['daozhenlv'] = round(($daozhen_total / $yuyue_total) * 100) . '%';
		$list1[0]['zhuanhualv'] = round(($daozhen_total / $zixun_total) * 100) . '%';
		$this->assign('list1', $list1);
		$this->assign('list', $list);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$this->assign('num', 0);
		$yijitotal = 0;

		for ($i = 0; $i <= count($list) - 1; $i++) {
			if ($list[$i]['zxfs_level'] == 1) {
				$tubiao_zxfsName .= '\'' . $list[$i]['zxfs_name'] . '\',';
				$tubiao_zixun .= $list[$i]['zixun'] . ',';
				$tubiao_yuyue .= $list[$i]['yuyue'] . ',';
				$tubiao_daozhen .= $list[$i]['daozhen'] . ',';
				$yijitotal = $yijitotal + 1;
			}
		}

		$this->assign('yijitotal', ($yijitotal * 60) + 50);
		$tubiao_zxfsName = rtrim($tubiao_zxfsName, ',');
		$daochu = serialize($list);
		$daochu_heji = serialize($list1);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->assign('tubiao_zxfsName', $tubiao_zxfsName);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('tubiao_zixunhj', $rows_hj[0]['zixun']);
		$this->assign('tubiao_yuyuehj', $rows_hj[0]['yuyue']);
		$this->assign('tubiao_daozhenhj', $rows_hj[0]['daozhen']);
		$this->display();
	}

	public function ExcleDC_fangshi()
	{
		$arr = unserialize($_POST['excle']);
		$arr_heji = unserialize($_POST['excle1']);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \Think\PHPExcel();
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
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '咨询工具')->setCellValue('B1', '咨询量')->setCellValue('C1', '预约量')->setCellValue('D1', '实际到诊')->setCellValue('E1', '应到诊')->setCellValue('F1', '预约率')->setCellValue('G1', '到诊率')->setCellValue('H1', '转化率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['zxfs_name']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['zhuanhualv']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['zxfs_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['yuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['daozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['ydaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zhuanhualv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="(' . date('Y-m-d') . ')咨询方式报表.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_qudao()
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

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if ($canshu['xx_ID'] == '') {
			$canshu['xx_ID'] = 0;
		}

		if ($canshu['submit1'] != '') {
			if ($canshu['yy_ID'] == '') {
				js_alert('', '请选择门店');
				exit();
			}

			$sql = 'select ID,xx_name,yy_ID from oa_xinxilaiyuan where find_in_set(' . $canshu['yy_ID'] . ',yy_ID)';
			$result = mysql_query($sql);

			while ($rows = mysql_fetch_array($result)) {
				$zixunSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where xx_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
				$zixun1 = mysql_query($zixunSql1);

				while ($total1 = mysql_fetch_array($zixun1)) {
					$zixun_total = $total1['total'];
				}

				$yuyueSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where xx_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
				$yuyue1 = mysql_query($yuyueSql1);

				while ($total1 = mysql_fetch_array($yuyue1)) {
					$yuyue_total = $total1['total'];
				}

				$daozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where xx_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and daozhen_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and daozhen_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0';
				$daozhen1 = mysql_query($daozhenSql1);

				while ($total1 = mysql_fetch_array($daozhen1)) {
					$daozhen_total = $total1['total'];
				}

				$ydaozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where xx_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and yuyueTime  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyueTime <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
				$ydaozhen1 = mysql_query($ydaozhenSql1);

				while ($total1 = mysql_fetch_array($ydaozhen1)) {
					$ydaozhen_total = $total1['total'];
				}

				$yuyuelv = round(($yuyue_total / $zixun_total) * 100) . '%';
				$daozhenlv = round(($daozhen_total / $yuyue_total) * 100) . '%';
				$zhuanhualv = round(($daozhen_total / $zixun_total) * 100) . '%';
				$list[] = array('xx_name' => $rows['xx_name'], 'zixun' => $zixun_total, 'yuyue' => $yuyue_total, 'daozhen' => $daozhen_total, 'ydaozhen' => $ydaozhen_total, 'yuyuelv' => $yuyuelv, 'daozhenlv' => $daozhenlv, 'zhuanhualv' => $zhuanhualv);
			}
		}

		for ($i = 0; $i <= count($list) - 2; $i++) {
			$zixun_total = $zixun_total + $list[$i]['zixun'];
			$yuyue_total = $yuyue_total + $list[$i]['yuyue'];
			$daozhen_total = $daozhen_total + $list[$i]['daozhen'];
			$ydaozhen_total = $ydaozhen_total + $list[$i]['ydaozhen'];
		}

		$list1[0]['userchinaname'] = '渠道来源合计';
		$list1[0]['zixun'] = $zixun_total;
		$list1[0]['yuyue'] = $yuyue_total;
		$list1[0]['daozhen'] = $daozhen_total;
		$list1[0]['ydaozhen'] = $ydaozhen_total;
		$list1[0]['yuyuelv'] = round(($yuyue_total / $zixun_total) * 100) . '%';
		$list1[0]['daozhenlv'] = round(($daozhen_total / $yuyue_total) * 100) . '%';
		$list1[0]['zhuanhualv'] = round(($daozhen_total / $zixun_total) * 100) . '%';
		$this->assign('list1', $list1);
		$this->assign('list', $list);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_xxName .= '\'' . $list[$i]['xx_name'] . '\',';
		}

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_zixun .= $list[$i]['zixun'] . ',';
		}

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_yuyue .= $list[$i]['yuyue'] . ',';
		}

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_daozhen .= $list[$i]['daozhen'] . ',';
		}

		$tubiao_xxName = rtrim($tubiao_xxName, ',');
		$tubiao_zixun = rtrim($tubiao_zixun, ',');
		$tubiao_yuyue = rtrim($tubiao_yuyue, ',');
		$tubiao_daozhen = rtrim($tubiao_daozhen, ',');
		$daochu = serialize($list);
		$daochu_heji = serialize($list1);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->assign('tubiao_xxName', $tubiao_xxName);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->assign('yijitotal', (count($list) * 60) + 50);
		$this->display();
	}

	public function ExcleDC_qudao()
	{
		$arr = unserialize($_POST['excle']);
		$arr_heji = unserialize($_POST['excle1']);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \Think\PHPExcel();
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
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '咨询渠道')->setCellValue('B1', '咨询量')->setCellValue('C1', '预约量')->setCellValue('D1', '实际到诊')->setCellValue('E1', '应到诊')->setCellValue('F1', '预约率')->setCellValue('G1', '到诊率')->setCellValue('H1', '转化率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['xx_name']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['zhuanhualv']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['xx_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['yuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['daozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['ydaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zhuanhualv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="(' . date('Y-m-d') . ')咨询渠道报表.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_zixunyuan()
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

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		/* if ($canshu['submit1'] != '') {
			if ($canshu['yy_ID'] == '') {
				js_alert('', '请选择门店');
				exit();
			}
		} */

		$sql = 'select user_ID,username,userchinaname,QQhaoma from oa_useradmin where user_del=0 and role_ID in(4,5) and   find_in_set(' . $canshu['yy_ID'] . ',yy_ID)';
		$result = mysql_query($sql);

		while ($rows = mysql_fetch_array($result)) {
			$zixunSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where userID=' . $rows['user_ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
			$zixun1 = mysql_query($zixunSql1);

			while ($total1 = mysql_fetch_array($zixun1)) {
				$zixun_total = $total1['total'];
			}

			$yuyueSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where userID=' . $rows['user_ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
			$yuyue1 = mysql_query($yuyueSql1);

			while ($total1 = mysql_fetch_array($yuyue1)) {
				$yuyue_total = $total1['total'];
			}

			$daozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where userID=' . $rows['user_ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and daozhen_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and daozhen_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0';
			$daozhen1 = mysql_query($daozhenSql1);

			while ($total1 = mysql_fetch_array($daozhen1)) {
				$daozhen_total = $total1['total'];
			}

			$ydaozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n" . '					 where userID=' . $rows['user_ID'] . ' ' . $tiaojian . ' ' . "\r\n" . '					 and yuyueTime  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyueTime <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
			$ydaozhen1 = mysql_query($ydaozhenSql1);

			while ($total1 = mysql_fetch_array($ydaozhen1)) {
				$ydaozhen_total = $total1['total'];
			}

			$yuyuelv = round(($yuyue_total / $zixun_total) * 100) . '%';
			$daozhenlv = round(($daozhen_total / $yuyue_total) * 100) . '%';
			$zhuanhualv = round(($daozhen_total / $zixun_total) * 100) . '%';
			$huifangSql1 = 'select count(zxhf_ID) as total from oa_huanzehuifang ' . "\r\n" . '					 where user_ID=' . $rows['user_ID'] . ' ' . "\r\n" . '					 and hf_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and hf_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
			$huifang1 = mysql_query($huifangSql1);

			while ($total1 = mysql_fetch_array($huifang1)) {
				$huifang_total = $total1['total'];
			}

			$list[] = array('userchinaname' => $rows['userchinaname'], 'user_img' => 'http://q1.qlogo.cn/g?b=qq&nk=' . $rows['QQhaoma'] . '&s=100&t=' . time() . '', 'zixun' => $zixun_total, 'yuyue' => $yuyue_total, 'daozhen' => $daozhen_total, 'ydaozhen' => $ydaozhen_total, 'yuyuelv' => $yuyuelv, 'daozhenlv' => $daozhenlv, 'zhuanhualv' => $zhuanhualv, 'huifang' => $huifang_total, 'qq' => $rows['QQhaoma']);
		}

		for ($i = 0; $i <= count($list) - 2; $i++) {
			$zixun_total = $zixun_total + $list[$i]['zixun'];
			$yuyue_total = $yuyue_total + $list[$i]['yuyue'];
			$daozhen_total = $daozhen_total + $list[$i]['daozhen'];
			$ydaozhen_total = $ydaozhen_total + $list[$i]['ydaozhen'];
			$huifang_total = $huifang_total + $list[$i]['huifang'];
		}

		$list1[0]['userchinaname'] = '咨询员合计	';
		$list1[0]['zixun'] = $zixun_total;
		$list1[0]['yuyue'] = $yuyue_total;
		$list1[0]['daozhen'] = $daozhen_total;
		$list1[0]['ydaozhen'] = $ydaozhen_total;
		$list1[0]['yuyuelv'] = round(($yuyue_total / $zixun_total) * 100) . '%';
		$list1[0]['daozhenlv'] = round(($daozhen_total / $yuyue_total) * 100) . '%';
		$list1[0]['zhuanhualv'] = round(($daozhen_total / $zixun_total) * 100) . '%';
		$list1[0]['huifang'] = $huifang_total;
		$this->assign('list1', $list1);
		$this->assign('list', $list);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_zxName .= '\'' . $list[$i]['userchinaname'] . '\',';
		}

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_zixun .= $list[$i]['zixun'] . ',';
		}

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_yuyue .= $list[$i]['yuyue'] . ',';
		}

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$tubiao_daozhen .= $list[$i]['daozhen'] . ',';
		}

		$tubiao_zxName = rtrim($tubiao_zxName, ',');
		$tubiao_zixun = rtrim($tubiao_zixun, ',');
		$tubiao_yuyue = rtrim($tubiao_yuyue, ',');
		$tubiao_daozhen = rtrim($tubiao_daozhen, ',');
		$daochu = serialize($list);
		$daochu_heji = serialize($list1);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->assign('tubiao_zxName', $tubiao_zxName);
		$this->assign('tubiao_zixun', $tubiao_zixun);
		$this->assign('tubiao_yuyue', $tubiao_yuyue);
		$this->assign('tubiao_daozhen', $tubiao_daozhen);
		$this->display();
	}

	public function ExcleDC_zixunyuan()
	{
		$arr = unserialize($_POST['excle']);
		$arr_heji = unserialize($_POST['excle1']);
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \Think\PHPExcel();
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
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '咨询员姓名')->setCellValue('B1', '咨询量')->setCellValue('C1', '预约量')->setCellValue('D1', '实际到诊')->setCellValue('E1', '应到诊')->setCellValue('F1', '预约率')->setCellValue('G1', '到诊率')->setCellValue('H1', '转化率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['userchinaname']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['zhuanhualv']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i]['userchinaname']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zixun']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['yuyue']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['daozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['ydaozhen']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zhuanhualv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="(' . date('Y-m-d') . ')咨询员报表.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_guanjianci()
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

		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);

		if (!empty($canshu['guanjianci'])) {
			$tiaojian .= ' and guanjianci like \'%' . $canshu['guanjianci'] . '%\' ';
		}

		$this->assign('serchguanjianci', $canshu['guanjianci']);

		if ($canshu['submit1'] != '') {
			if ($canshu['yy_ID'] == '') {
				js_alert('', '请选择门店');
				exit();
			}

			if ($canshu['paixu'] == 'count(*)') {
				$this->assign('paixumoren1', 'selected');
			}
			else {
				$this->assign('paixumoren2', 'selected');
			}

			$sql = 'SELECT ' . $canshu['keywords'] . ',count(*) as zixunliang FROM oa_managezx where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . $tiaojian . '  group BY ' . $canshu['keywords'] . ' order by ' . $canshu['paixu'] . ' desc';
			$data = m('');
			$gjcreport = $data->query($sql);
			if ((count($gjcreport) == 1) && ($gjcreport[0][$canshu['keywords']] == '')) {
				js_alert1('', '没有录入任何关键词');
				exit();
			}

			$zixunsql = 'select guanjianci,count(zx_ID) as zixunliang' . "\r\n" . '					 from oa_managezx  ' . "\r\n" . '					 where    zx_time<\'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . $tiaojian . ' and zx_time>\'' . $canshu['zx_timeStart'] . ' 00:00:00\' ' . "\r\n" . '					 group by guanjianci order by zixunliang desc';
			$yuyuesql = 'select guanjianci,count(zx_ID) as yuyueliang ' . "\r\n" . '					 from oa_managezx  ' . "\r\n" . '					 where    shifouyuyue=0 ' . $tiaojian . ' and  zx_time<\'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and zx_time>\'' . $canshu['zx_timeStart'] . ' 00:00:00\' group by guanjianci order by yuyueliang desc';
			$daozhensql = 'select guanjianci,count(zx_ID) as daozhenliang ' . "\r\n" . '					 from oa_managezx  ' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' ' . $tiaojian . '  and shifoudaozhen=0 and  daozhen_time<\'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and daozhen_time>\'' . $canshu['zx_timeStart'] . ' 00:00:00\' group by guanjianci order by daozhenliang desc';
			$ydaozhensql = 'select guanjianci,count(zx_ID) as yingdaozhenliang   ' . "\r\n" . '					 from oa_managezx  ' . "\r\n" . '					 where yy_ID=' . $canshu['yy_ID'] . ' ' . $tiaojian . '  and shifouyuyue=0 and  yuyueTime<\'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and yuyueTime>\'' . $canshu['zx_timeStart'] . ' 00:00:00\' group by guanjianci order by yingdaozhenliang desc';
			$zixunliangArr = $data->query($zixunsql);
			$yuyueliangArr = $data->query($yuyuesql);
			$daozhenliangArr = $data->query($daozhensql);
			$ydaozhenliangArr = $data->query($ydaozhensql);
			$zixunArr = array();

			foreach ($zixunliangArr as $k => $v ) {
				$zixunArr[$v['guanjianci']] = array('guanjianci' => $v['guanjianci'], 'zixunliang' => $v['zixunliang']);
			}

			$yuyueArr = array();

			foreach ($yuyueliangArr as $k => $v ) {
				$yuyueArr[$v['guanjianci']] = array('guanjianci' => $v['guanjianci'], 'yuyueliang' => $v['yuyueliang']);
			}

			$daozhenArr = array();

			foreach ($daozhenliangArr as $k => $v ) {
				$daozhenArr[$v['guanjianci']] = array('guanjianci' => $v['guanjianci'], 'daozhenliang' => $v['daozhenliang']);
			}

			$ydaozhenArr = array();

			foreach ($ydaozhenliangArr as $k => $v ) {
				$ydaozhenArr[$v['guanjianci']] = array('guanjianci' => $v['guanjianci'], 'ydaozhenliang' => $v['yingdaozhenliang']);
			}

			$list_ls = array_merge_recursive($zixunArr, $yuyueArr, $daozhenArr, $ydaozhenArr);

			foreach ($list_ls as $k => $v ) {
				if (is_array($v['guanjianci'])) {
					$list_ls[$k]['guanjianci'] = $v['guanjianci'][0];
				}
				else {
					$list_ls[$k]['guanjianci'] = $v['guanjianci'];
				}

				$yuyuelv = round(($v['yuyueliang'] / $v['zixunliang']) * 100) . '%';
				$daozhenlv = round(($v['daozhenliang'] / $v['yuyueliang']) * 100) . '%';
				$zhuanhualv = round(($v['daozhenliang'] / $v['zixunliang']) * 100) . '%';
				$list_ls[$k]['yuyuelv'] = $yuyuelv;
				$list_ls[$k]['daozhenlv'] = $daozhenlv;
				$list_ls[$k]['zhuanhualv'] = $zhuanhualv;
			}

			$gjcreport = array_values($list_ls);

			for ($i = 0; $i <= count($gjcreport) - 2; $i++) {
				if ($gjcreport[$i][$canshu['keywords']] == '') {
					$gjcreport[$i][$canshu['keywords']] = '<font color="red">没有录入关键词</font>';
				}

				$zixun_total = $zixun_total + $gjcreport[$i]['zixunliang'];
				$yuyue_total = $yuyue_total + $gjcreport[$i]['yuyueliang'];
				$daozhen_total = $daozhen_total + $gjcreport[$i]['daozhenliang'];
				$ydaozhen_total = $ydaozhen_total + $gjcreport[$i]['ydaozhenliang'];
			}

			$list1[0]['userchinaname'] = '关键词合计';
			$list1[0]['zixun'] = $zixun_total;
			$list1[0]['yuyue'] = $yuyue_total;
			$list1[0]['daozhen'] = $daozhen_total;
			$list1[0]['ydaozhen'] = $ydaozhen_total;
			$list1[0]['yuyuelv'] = round(($yuyue_total / $zixun_total) * 100) . '%';
			$list1[0]['daozhenlv'] = round(($daozhen_total / $yuyue_total) * 100) . '%';
			$list1[0]['zhuanhualv'] = round(($daozhen_total / $zixun_total) * 100) . '%';
			if (($canshu['keywords'] == 'guanjianci') || ($canshu['keywords'] == '')) {
				$this->assign('keywords', 'guanjianci');
				$this->assign('guanjiancimoren1', 'selected');
			}
			else {
				$this->assign('keywords', 'ppguanjianci');
				$this->assign('guanjiancimoren2', 'selected');
			}
		}

		$this->assign('list1', $list1);
		$this->assign('list', $gjcreport);
		$this->assign('zx_timeStart', $canshu['zx_timeStart']);
		$this->assign('zx_timeEnd', $canshu['zx_timeEnd']);
		$daochu = serialize($gjcreport);
		$daochu_heji = serialize($list1);
		$this->assign('daochu', $daochu);
		$this->assign('daochu_heji', $daochu_heji);
		$this->display();
	}

	public function ExcleDC_guanjianci()
	{
		$arr = unserialize($_POST['excle']);
		$arr_heji = unserialize($_POST['excle1']);
		$keywords = $_POST['keywords'];
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \Think\PHPExcel();
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
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '关键词')->setCellValue('B1', '咨询量')->setCellValue('C1', '预约量')->setCellValue('D1', '实际到诊')->setCellValue('E1', '应到诊')->setCellValue('F1', '预约率')->setCellValue('G1', '到诊率')->setCellValue('H1', '转化率');
		$num = '2';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr_heji[0]['userchinaname']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr_heji[0]['zixun']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr_heji[0]['yuyue']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr_heji[0]['daozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr_heji[0]['ydaozhen']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr_heji[0]['yuyuelv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr_heji[0]['daozhenlv']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr_heji[0]['zhuanhualv']);
		$num = '3';

		for ($i = 0; $i <= count($arr) - 1; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $arr[$i][$keywords]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $num, $arr[$i]['zixunliang']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $num, $arr[$i]['yuyueliang']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $num, $arr[$i]['daozhenliang']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $num, $arr[$i]['ydaozhenliang']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $num, $arr[$i]['yuyuelv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $num, $arr[$i]['daozhenlv']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $num, $arr[$i]['zhuanhualv']);
			$num++;
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('Simple2222');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="(' . date('Y-m-d') . ')关键词报表.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		saveviatempfile($objWriter);
		exit();
	}

	public function report_common($canshu)
	{
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

			$zixunyuan = $user->query('select user_ID,userchinaname,role_ID from oa_useradmin where user_del=0 and role_ID in(4,5) and  find_in_set(' . $yyarr[$i] . ',yy_ID)');

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
					$this->assign('zixunyuan1', $zixunyuan2);
				}
				else {
					if (($canshu['yy_ID'] != '') && ($canshu['userID'] != '')) {
						$tiaojian .= ' and userID=' . $canshu['userID'] . ' ';
						$URLcanshu .= '&userID=' . $canshu['userID'] . '';
						$zixunName = $user->where('user_ID=' . $canshu['userID'] . '')->select();
						$zixunyuan2 = $user->where('user_del=0 and role_ID in(4,5) and find_in_set(' . $canshu['yy_ID'] . ',yy_ID)')->select();
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
				$tiaojian .= ' and bz_ID in(' . $canshu['bz_ID'] . ',' . trim($bz, ',') . ') ';
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

		return $tiaojian;
	}
}


?>
