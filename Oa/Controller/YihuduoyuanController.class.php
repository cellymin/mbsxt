<?php

namespace OA\Controller;


class YihuduoyuanController extends \Component\AdminController
{
	public function report_oneline()
	{
		$canshu = i('request.');
		$this->assign('duoyuan1', $canshu['duoyuan']);

		foreach ($canshu['duoyuan'] as $v ) {
			$dyyid .= $v . ',';
		}

		$dyyid .= $canshu['yy_ID'];

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
		$tiaojian1 = explode('and', $tiaojian);
		unset($tiaojian1[0]);
		unset($tiaojian1[1]);
		unset($tiaojian);
		$tiaojian .= ' and yy_ID  in (' . $dyyid . ') ';

		foreach ($tiaojian1 as $k => $v ) {
			$tiaojian .= ' and ' . $v;
		}

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
					$fenriqi = a('Baidutongji');
					$baidufanhui = $fenriqi->baiduzhanghu_fenriqi($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);

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
						$zixunliangSql = 'select count(swtID) as total from oa_swtdaoru' . "\r\n" . '								  where   zx_time  >= \'' . $arrDate[$i] . ' 00:00:00\' and zx_time <= \'' . $arrDate[$i] . ' 23:59:59\' ' . $tiaojian_zhanghu . ' and yy_ID in (' . $dyyid . ')';
						$yuyueliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . '	 where   a.zx_time>=\'' . $arrDate[$i] . ' 00:00:00\' and a.zx_time<=\'' . $arrDate[$i] . ' 23:59:59\' and b.yy_ID in  (' . $dyyid . ') and  b.shifouyuyue = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . ' and b.yy_ID in (' . $dyyid . ')';
						$daozhenliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . '	 where  b.daozhen_time>=\'' . $arrDate[$i] . ' 00:00:00\' and b.daozhen_time<=\'' . $arrDate[$i] . ' 23:59:59\' and b.shifoudaozhen = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . ' and b.yy_ID in (' . $dyyid . ') ';
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
					$fenriqi = a('baidutongji');
					$baidufanhui = $fenriqi->baiduzhanghu_fenriqi($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);

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

						$zixunliangSql = 'select count(swtID) as  total  from oa_swtdaoru  where   DATE_FORMAT(zx_time,\'%Y-%m\') =\'' . $arrDate[$i] . '\' ' . $tiaojian_zhanghu . ' and yy_ID in (' . $dyyid . ')';
						$yuyueliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . ' where  DATE_FORMAT(a.zx_time,\'%Y-%m\')=\'' . $arrDate[$i] . '\' and b.shifouyuyue = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . ' and b.yy_ID in (' . $dyyid . ')';
						$daozhenliangSql = 'select count(a.swtID) as total from oa_managezx as b inner join oa_swtdaoru as a on a.yongjiushenfen = b.yongjiushenfen' . "\r\n" . ' where  DATE_FORMAT(b.daozhen_time,\'%Y-%m\')=\'' . $arrDate[$i] . '\' and b.shifoudaozhen = 0 and a.chucifangwen = b.chucifangwen ' . $tiaojian_zhanghu . ' and b.yy_ID in (' . $dyyid . ')';
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

	public function report_shijianduan()
	{
		$canshu = i('request.');
		$this->assign('duoyuan1', $canshu['duoyuan']);

		foreach ($canshu['duoyuan'] as $v ) {
			$dyyid .= $v . ',';
		}

		$dyyid .= $canshu['yy_ID'];
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
		$tiaojian1 = explode('and', $tiaojian);
		unset($tiaojian1[0]);
		unset($tiaojian1[1]);
		unset($tiaojian);
		$tiaojian .= ' and yy_ID  in (' . $dyyid . ') ';

		foreach ($tiaojian1 as $k => $v ) {
			$tiaojian .= ' and ' . $v;
		}

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
			$bdzh_sjd = a('Baidutongji');
			$baidufanhui = $bdzh_sjd->baiduzhanghu_shijianduan($StartDate, $setEndDate, $setdevice, $levelOfDetails, $yy_ID, $setUnitOfTime, $zhanghuID);

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

				$zixunliangSql = 'select zx_time from oa_swtdaoru ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '									and yy_ID in (' . $dyyid . ') ' . $tiaojian_zhanghu . ' order by zx_time';
				$yuyueliangSql = 'select oa_swtdaoru.zx_time ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in(' . $dyyid . ') and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\'   and oa_managezx.shifouyuyue = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$daozhenliangSql = 'select oa_swtdaoru.zx_time  ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in(' . $dyyid . ') and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\' and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
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

	public function report_shijianduan_jihua()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');

		foreach ($canshu['duoyuan'] as $v ) {
			$dyyid .= $v . ',';
		}

		$dyyid .= $canshu['yy_ID'];
		$this->assign('duoyuan1', $canshu['duoyuan']);
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
		$tiaojian1 = explode('and', $tiaojian);
		unset($tiaojian1[0]);
		unset($tiaojian1[1]);
		unset($tiaojian);
		$tiaojian .= ' and yy_ID  in (' . $dyyid . ') ';

		foreach ($tiaojian1 as $k => $v ) {
			$tiaojian .= ' and ' . $v;
		}

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
				$bdzh = a('Baidutongji');
				$report_ID = $bdzh->yibubaogao_id_jh($StartDate, $setEndDate, $arrfanhui, $LevelOfDetails, $ReportType, $setDevice, $zhanghuID, $unitOfTime);

				do {
					sleep(1);
					$reportstate = $bdzh->getReportState($report_ID, $zhanghuID);
				} while ($reportstate != 3);

				$report_url = $bdzh->getReportUrl($report_ID, $zhanghuID);
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

				$zixunliangSql = 'select zx_time from oa_swtdaoru ' . "\r\n" . '						  			where zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' ' . "\r\n" . '									and yy_ID in (' . $dyyid . ') ' . $tiaojian_zhanghu . ' order by zx_time';
				$yuyueliangSql = 'select oa_swtdaoru.zx_time ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in(' . $dyyid . ') and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\'   and oa_managezx.shifouyuyue = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$daozhenliangSql = 'select oa_swtdaoru.zx_time  ' . "\r\n" . '				  from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in(' . $dyyid . ') and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\' and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
				$setDevice = 0;
				$setUnitOfTime = 7;
				$zhanghuID = $canshu['zhanghuID'];
				$fanhuiziduan = array('impression', 'click', 'cost', 'cpc');
				$ReportType = 14;
				$levelOfDetails = 11;
				$report_ID = $bdzh->yibubaogao_ID_jh($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID, $setUnitOfTime);

				do {
					$reportstate = $bdzh->getReportState($report_ID, $zhanghuID);
				} while ($reportstate != 3);

				$report_url = $bdzh->getReportUrl($report_ID, $zhanghuID);
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

	public function report_lishisousuociid()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');

		foreach ($canshu['duoyuan'] as $v ) {
			$dyyid .= $v . ',';
		}

		$dyyid .= $canshu['yy_ID'];
		$this->assign('duoyuan1', $canshu['duoyuan']);

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
		$tiaojian1 = explode('and', $tiaojian);
		unset($tiaojian1[0]);
		unset($tiaojian1[1]);
		unset($tiaojian);
		$tiaojian .= ' and yy_ID  in (' . $dyyid . ') ';

		foreach ($tiaojian1 as $k => $v ) {
			$tiaojian .= ' and ' . $v;
		}

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

			$this->assign('shuoming', '说明：在有设置URL着陆页{keywordid}的前提下，可以通过商务通导入初次访问URL中keywordid，' . "\r\n" . '			  对账户搜索词及关键词报表keywordid，进行匹配！<br>' . "\r\n" . '			  关键词咨询量：keywordid咨询量合计；<br>' . "\r\n" . '			  关键词到诊量：keywordid到诊量合计；<br>' . "\r\n" . '			  搜索词咨询量：' . $shuomingtiaojian . ';<br>' . "\r\n" . '			  此模块报表相对很准确，但需要前期的设置；报表中搜索词的咨询量与触发关键词的咨询量没有直接关系，仅供参考;<br>' . "\r\n" . '			  咨询量来源-咨询员录入模式下：搜索词的（咨询量到诊量）来源为咨询员录入，与关键词咨询量、到诊量的没有关系;<br>' . "\r\n" . '			  咨询量来源-商务通导入模式下：搜索词的（咨询量到诊量）来源为商务通录入，与关键词咨询量、到诊量的没有关系;' . "\r\n" . '			  ');
			$StartDate = $canshu['zx_timeStart'] . ' 00:00:01';
			$setEndDate = $canshu['zx_timeEnd'] . ' 23:59:59';
			$setDevice = $canshu['zhanghushebei'];
			$yy_ID = $canshu['yy_ID'];
			$setUnitOfTime = 8;
			$zhanghuID = $canshu['zhanghuID'];
			$fanhuiziduan = array('impression', 'click', 'cost', 'cpc');
			$ReportType = 14;
			$levelOfDetails = 11;
			$bdzh = a('Baidutongji');
			$report_ID = $bdzh->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $bdzh->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url = $bdzh->getReportUrl($report_ID, $zhanghuID);
			$fanhuiziduan = array('impression', 'click');
			$ReportType = 6;
			$levelOfDetails = 12;
			$report_ID = $bdzh->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $bdzh->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url_sousuo = $bdzh->getReportUrl($report_ID, $zhanghuID);
			$zhanghuweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('weiyifu');

			if ($canshu['zhanghushebei'] == 1) {
				$diannaotongpeifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('pcweiyifu');
				$tiaojian_zhanghu .= ' and locate(\'' . $diannaotongpeifu . '\',oa_swtdaoru.chucifangwenURL) ';
			}

			if ($canshu['zhanghushebei'] == 2) {
				$yidongweiyifu = $zhanghu->where('zhanghu_ID=' . $canshu['zhanghuID'] . '')->getField('yidongweiyifu');
				$tiaojian_zhanghu .= ' and locate(\'' . $yidongweiyifu . '\',oa_swtdaoru.chucifangwenURL) ';
			}

			$swtsqlzixun = 'select chucifangwenURL from oa_swtdaoru' . "\r\n" . '				 where  zx_time>\'' . $StartDate . '\' and zx_time<\'' . $setEndDate . '\' and locate(\'' . $zhanghuweiyifu . '\',chucifangwenURL) and yy_ID in (' . $dyyid . ') ' . $tiaojian_zhanghu . '';
			$swtsqldaozhen = 'select oa_swtdaoru.chucifangwenURL from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in (' . $dyyid . ') and oa_swtdaoru.zx_time>\'' . $StartDate . '\' and oa_swtdaoru.zx_time<\'' . $setEndDate . '\'  and locate(\'' . $zhanghuweiyifu . '\',oa_swtdaoru.chucifangwenURL) and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
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
			$zixunsql = 'select guanjianci,count(*) as zixunliang' . "\r\n" . '					 from oa_swtdaoru ' . "\r\n" . '			 		 where yy_ID in(' . $dyyid . ') and guanjianci !=\'\' and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
			$this->assign('selectd1', 'selected');
		}
		else {
			$zixunsql = 'select guanjianci,count(*) as zixunliang' . "\r\n" . '					 from oa_managezx' . "\r\n" . '					 where yy_ID in (' . $dyyid . ') and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
			$this->assign('selectd2', 'selected');
		}

		$yuyuesql = 'select guanjianci,count(*) as yuyueliang' . "\r\n" . '					 from oa_managezx ' . "\r\n" . '					 where yy_ID in(' . $dyyid . ') and  shifouyuyue=0 and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
		$daozhensql = 'select guanjianci,count(*) as daozhenliang' . "\r\n" . '					 from oa_managezx' . "\r\n" . '					 where yy_ID in(' . $dyyid . ') and shifoudaozhen=0 and guanjianci !=\'\'  and  zx_time<\'' . $setEndDate . '\' and zx_time>\'' . $StartDate . '\'' . "\r\n" . '					 group by guanjianci';
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

	public function report_zhaoluye()
	{
		ini_set('max_execution_time', '600');
		ini_set('memory_limit', '500M');
		$canshu = i('request.');

		foreach ($canshu['duoyuan'] as $v ) {
			$dyyid .= $v . ',';
		}

		$dyyid .= $canshu['yy_ID'];
		$this->assign('duoyuan1', $canshu['duoyuan']);
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
			$bdzh = a('Baidutongji');
			$report_ID = $bdzh->yibubaogao_ID($StartDate, $setEndDate, $fanhuiziduan, $levelOfDetails, $ReportType, $setDevice, $zhanghuID);
			$report_url = $bdzh->getReportUrl($report_ID, $zhanghuID);

			do {
				sleep(2);
				$reportstate = $bdzh->getReportState($report_ID, $zhanghuID);
			} while ($reportstate != 3);

			$report_url = $bdzh->getReportUrl($report_ID, $zhanghuID);
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

			$swtsqlzixun = 'select chucifangwenURL from oa_swtdaoru' . "\r\n" . '				 where yy_ID in (' . $dyyid . ') and zx_time>=\'' . $StartDate . '\' and zx_time<=\'' . $setEndDate . '\' and locate(\'' . $zhanghuweiyifu . '\',chucifangwenURL) ' . $tiaojian_zhanghu . '';
			$swtsqlyuyue = 'select oa_swtdaoru.chucifangwenURL from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in (' . $dyyid . ') and oa_swtdaoru.zx_time>=\'' . $StartDate . '\' and oa_swtdaoru.zx_time<=\'' . $setEndDate . '\'  and locate(\'' . $zhanghuweiyifu . '\',oa_swtdaoru.chucifangwenURL) and oa_managezx.shifouyuyue = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
			$swtsqldaozhen = 'select oa_swtdaoru.chucifangwenURL from oa_managezx inner join oa_swtdaoru on oa_managezx.yongjiushenfen = oa_swtdaoru.yongjiushenfen ' . "\r\n" . '				 where oa_managezx.yy_ID in (' . $dyyid . ') and oa_swtdaoru.zx_time>=\'' . $StartDate . '\' and oa_swtdaoru.zx_time<=\'' . $setEndDate . '\'  and locate(\'' . $zhanghuweiyifu . '\',oa_swtdaoru.chucifangwenURL) and oa_managezx.shifoudaozhen = 0 and oa_swtdaoru.chucifangwen = oa_managezx.chucifangwen ' . $tiaojian_zhanghu . '';
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
				$zhaoluye = $bdzh->baiduzhanghu_getword($canshu['zhanghuID'], $arrkeyID);
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
}


?>
