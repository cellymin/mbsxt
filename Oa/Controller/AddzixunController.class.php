<?php

namespace OA\Controller;



class AddzixunController extends \Component\AdminController
{
	public function ceshi100()
	{
		$user = m('huanze');
		$t = time();

		for ($i = 0; $i <= 20002; $i++) {
			$yy_ID = array(1, 2, 3, 167);
			$yy_IDnum = array_rand($yy_ID, 1);
			$zxfs_ID = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 17, 18, 19, 20, 21, 22);
			$zxfs_IDnum = array_rand($zxfs_ID, 1);
			$user_ID = array(12, 36, 46, 47, 48, 49, 50, 51, 54);
			$user_IDnum = array_rand($user_ID, 1);
			$bz_ID = array(147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206);
			$bz_IDnum = array_rand($bz_ID, 1);
			$xx_ID = array(2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 28, 30, 31, 32, 33);
			$xx_IDnum = array_rand($xx_ID, 1);
			$nian = array(2015, 2016);
			$niannum = array_rand($nian, 1);
			$yue = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
			$yuenum = array_rand($yue, 1);
			$ri = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28);
			$rinum = array_rand($ri, 1);
			$riqi = date('' . $nian[$niannum] . '-' . $yue[$yuenum] . '-' . $ri[$rinum] . '');
			$riqi2 = date('' . $nian[$niannum] . '-' . $yue[$yuenum] . '-' . $ri[$rinum] . '');
			$riqi3 = date('' . $nian[$niannum] . '-' . $yue[$yuenum] . '-' . $ri[$rinum] . '');
			$shifou_ID = array(0, 1);
			$shifou_IDnum = array_rand($shifou_ID, 1);
			$shifoudaozhen_ID = array(0, 1);
			$shifoudaozhen_IDnum = array_rand($shifoudaozhen_ID, 1);
			$huanze = array('yy_ID' => $yy_ID[$yy_IDnum], 'zxfs_ID' => $zxfs_ID[$zxfs_IDnum], 'userID' => $user_ID[$user_IDnum], 'zx_time' => $riqi, 'bz_ID' => $bz_ID[$bz_IDnum], 'yuyueTime' => $riqi2, 'xx_ID' => $xx_ID[$xx_IDnum], 'shifouyuyue' => $shifou_ID[$shifou_IDnum], 'shifoudaozhen' => $shifoudaozhen_ID[$shifoudaozhen_IDnum], 'daozhen_time' => $riqi3);
			$zx_ID = $user->add($huanze);
			$sql2 = 'INSERT INTO `oa_huanzeyuyue` (`huanzeName`,`shouji`,`yuyueyishengID`,`yuyuehao`,`yuyueBeizhu`,`zx_ID`) VALUES (\'423423\',\'15211058838\',62,\'W151209001\',\'fsdfdfs\',' . $zx_ID . ')';
			$sql3 = 'INSERT INTO `oa_huanzecaozuo` (`zx_ID`,`add_time`,`addUser_ID`) VALUES (' . $zx_ID . ',\'' . $riqi3 . ' 15:48\',11)';
			$sql4 = 'INSERT INTO `oa_huanzejingjia` (`fangwenrukou`,`faqizixun`,`guanjianci`,`ppguanjianci`,`laiyuanwangzhan`,`zx_ID`) VALUES (\'fsdfsd\',\'fdsfsdfsd\',\'fdsfsdfdsfdsfsdfds\',\'fsdfsdfsdfsdfdsfsdfdsfdsfsdfsdfdsfsdfd\',16,' . $zx_ID . ')';
			$sql5 = 'INSERT INTO `oa_huanzeinfo` (`QQhaoma`,`xingbie`,`hunyin`,`age`,`shengri`,`zhiye`,`seachprov`,`homecity`,`seachdistrict`,`liaotianjilu`,`zx_ID`) VALUES (\'423423\',2,1,\'43\',\'2015-12-10\',\'\',43,4301,0,\'\',' . $zx_ID . ')';
			mysql_query($sql2);
			mysql_query($sql3);
			mysql_query($sql4);
			mysql_query($sql5);
			$huanzeName1 = array('张三', '李四', '王五', '刘德华', '张学友', '郭富城', '黎明', '周小二', '陈放假', '五月天', '朱军', '王强', '刘强', '周强', '刘东');
			$huanzeName = array_rand($huanzeName1, 1);
			$guanjianci1 = array('长沙妇科门店', '长沙哪家妇科门店好', '妇科炎症的症状', '怀孕初期症状', '武汉妇幼门店', '四维彩超', '人流费用', '长沙人流价格', '武汉人流费用', '北京妇科门店', '不孕不育症状');
			$guanjianci = array_rand($guanjianci1, 1);
			$yiyuanming1 = array('武汉妇科门店', '株洲妇科门店', '怀化骨科门店', '南昌不孕不育门店');
			$yiyuanming = array_rand($yiyuanming1, 1);
			$bingzhongname1 = array('妇科炎症', '宫颈疾病', '不孕不育', '无痛人流', '脊柱外科', '创伤骨科', '颈肩腰腿痛科', '关节外科');
			$bingzhongname = array_rand($bingzhongname1, 1);
			$zixunfangshiname1 = array('商务通', '个人QQ', '企业QQ', '微信', '网络电话', '传统电话', '商务通留言', '快商通');
			$zixunfangshiname = array_rand($zixunfangshiname1, 1);
			$xinxiname1 = array('百度PC', '百度移动', '搜狗', '360', '新闻源', '神马', '搜狗移动', '号码抓取');
			$xinxiname = array_rand($xinxiname1, 1);
			$zixunyuanname1 = array('何欢', '', '谢乐', '刘栋', '王鹏', '王珂', '范辉', '张发斌', '曾华');
			$zixunyuanname = array_rand($zixunyuanname1, 1);
			$zonghearr = array('zx_ID' => $i, 'userID' => $user_ID[$user_IDnum], 'userchinaname' => $zixunyuanname1[$zixunyuanname], 'yy_ID' => $yy_ID[$yy_IDnum], 'yy_name' => $yiyuanming1[$yiyuanming], 'zxfs_ID' => $zxfs_ID[$zxfs_IDnum], 'zxfs_name' => $zixunfangshiname1[$zixunfangshiname], 'bz_ID' => $bz_ID[$bz_IDnum], 'bz_name' => $bingzhongname1[$bingzhongname], 'xx_ID' => $xx_ID[$xx_IDnum], 'xx_name' => $xinxiname1[$xinxiname], 'zx_time' => $riqi, 'yuyueTime' => $riqi2, 'shifouyuyue' => $shifou_ID[$shifou_IDnum], 'shifoudaozhen' => $shifoudaozhen_ID[$shifoudaozhen_IDnum], 'huanzeName' => $huanzeName1[$huanzeName], 'shouji' => '13888888888', 'yuyueyishengID' => '21', 'guanjianci' => $guanjianci1[$guanjianci], 'laiyuanwangzhan' => '4', 'wangzhan_url' => 'www.8629000.net', 'fangwenrukou' => 'ddsdsfdfsfdsfdsfdsdf', 'faqizixun' => 'fdsfsdfsfsdfsdfdsfsd', 'ppguanjianci' => '妇科门店', 'daozhen_time' => $riqi3, 'xiaofei' => rand(1000, 10000));
			$zonghe = m('managezx');
			$zonghe->add($zonghearr);
		}

		echo 'OK ';
		echo $i . '条';
		$t1 = time();
		echo '<p>';
		echo 'It used:';
		echo $t1 - $t;
		echo '秒';
	}

	public function add()
	{
		$user = m('Useradmin');
		$qj = m('quanjushezhi');
		$quanju = $qj->field('qj_ID,qj_del')->select();
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

		if (session('user_role') == 5) {
			if ($quanju[2]['qj_del'] == 0) {
				$this->assign('time_JS', 'class="ipticon"');
			}
		}

		$this->assign('dqtime', date('Y-m-d H:i'));
		$liushui = m('yuyuehaoadd');
		$yyliushui = $liushui->where('add_time = \'' . date('Y-m-d') . '\'')->select();

		if (empty($yyliushui)) {
			$liushui->yyhadd = '1';
			$liushui->add_time = date('Y-m-d');
			$liushui->where('ID=1')->save();
			$liushuihao = sprintf('%03d', '1');
		}
		else {
			$sql = 'update oa_yuyuehaoadd set yyhadd = yyhadd+1 where ID =1';
			$liushui->query($sql);
			$liushuihao1 = $liushui->select();
			$liushuihao = sprintf('%03d', $liushuihao1[0]['yyhadd']);
		}

		$yuyuehao = 'W' . substr(date('Y'), 2) . date('m') . date('d') . $liushuihao;
		$this->assign('yuyuehao', $yuyuehao);
		$t = mktime();
		$_SESSION['conn_id'] = $t;
		$_SESSION['conn'] = $t;
		$this->assign('chongfutijiao', $_SESSION['conn_id']);
		$this->display('add');
	}

	public function ZiXunLianDong()
	{
		$yyid = htmlspecialchars(trim($_POST['username']));
		echo $yyid;

		if (!empty($yyid)) {
			zxfs_down_eyy('0', '0', $yyid, '0');
		}
	}

	public function ZiXunpdxj()
	{
		$YYid1 = htmlspecialchars(trim($_POST['yyid']));

		if (!empty($YYid1)) {
			$yyid = explode('|', $YYid1);
			$sql = 'SELECT P_id,yy_ID FROM oa_zixunfangshi WHERE find_in_set(' . $yyid[0] . ', yy_ID) and P_id = ' . $yyid[1] . '';
			$data = m('zixunfangshi');
			$syid1 = $data->query($sql);

			if (!empty($syid1)) {
				echo 'no';
			}
			else {
				echo 'okokok';
			}
		}
	}

	public function zixunyuanLianDong()
	{
		$zixunyuan1 = htmlspecialchars(trim($_POST['zixunyuan']));

		if (!empty($zixunyuan1)) {
			$qj = m('quanjushezhi');
			$quanju = $qj->query('select * from oa_quanjushezhi where qj_ID=4 and find_in_set(' . $zixunyuan1 . ',qj_yyid)');
			$sql = 'SELECT user_ID,userchinaname FROM oa_useradmin WHERE find_in_set(' . $zixunyuan1 . ', yy_ID) and user_del= \'0\' and role_ID in(4,5)';
			$data = m('useradmin');
			$syid1 = $data->query($sql);

			if (session('user_role') == 5) {
				if ($quanju[0]['qj_yyid'] != '') {
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
					for ($i = 0; $i <= count($syid1) - 1; $i++) {
						if ($syid1[$i]['user_ID'] == session('username_lf')) {
							echo '<option value=' . $syid1[$i]['user_ID'] . '>' . $syid1[$i]['userchinaname'] . '</option>';
						}
					}
				}
			}
			else {
				for ($i = 0; $i <= count($syid1) - 1; $i++) {
					if ($syid1[$i]['user_ID'] != session('username_lf')) {
						echo '<option value=' . $syid1[$i]['user_ID'] . '>' . $syid1[$i]['userchinaname'] . '</option>';
					}
					else {
						echo '<option value=' . $syid1[$i]['user_ID'] . ' selected=\'selected\'>' . $syid1[$i]['userchinaname'] . '</option>';
					}
				}
			}
		}
	}

	public function bzLianDong()
	{
		$yyid = htmlspecialchars(trim($_POST['bzid']));

		if (!empty($yyid)) {
			column_down_eyy('0', '0', $yyid, '0');
		}
	}

	public function xxlyLianDong()
	{
		$yyid = htmlspecialchars(trim($_POST['xxid']));

		if (!empty($yyid)) {
			$sql = 'select xx_name,ID from oa_xinxilaiyuan where find_in_set(' . $yyid . ', yy_ID) and xx_del=0 order by xx_sort desc';
			$data = m('xinxilaiyuan');
			$syid1 = mysql_query($sql);
			echo '<option value=\'\'>请选择信息来源</option>';

			while ($syid = mysql_fetch_array($syid1)) {
				echo '<option value=' . $syid['ID'] . '>' . $syid['xx_name'] . '</option>';
			}
		}
	}

	public function BingZhongpdxj()
	{
		$YYid1 = htmlspecialchars(trim($_POST['yyid']));

		if (!empty($YYid1)) {
			$yyid = explode('|', $YYid1);
			$sql = 'SELECT P_id,yy_ID FROM oa_bingzhong WHERE find_in_set(' . $yyid[0] . ', yy_ID) and P_id = ' . $yyid[1] . ' and bz_del=0';
			$data = m('oa_bingzhong');
			$syid1 = $data->query($sql);

			if (!empty($syid1)) {
				echo 'no';
			}
			else {
				echo 'okokok';
			}
		}
	}

	public function wzLianDong()
	{
		$yyid = htmlspecialchars(trim($_POST['wzid']));

		if (!empty($yyid)) {
			$sql = 'select wangzhan_URL,wangzhan_ID from oa_wangzhan where find_in_set(' . $yyid . ', yy_ID) and wangzhan_del=0 order by wangzhan_sort desc';
			$data = m('wangzhan');
			$syid1 = mysql_query($sql);
			echo '<option value=\'\'>请选择网站&nbsp;&nbsp;&nbsp;</option>';

			while ($syid = mysql_fetch_array($syid1)) {
				echo '<option value=' . $syid['wangzhan_ID'] . '>' . $syid['wangzhan_URL'] . '</option>';
			}
		}
	}

	public function yuyueYYLiandong()
	{
		$yyid = htmlspecialchars(trim($_POST['yyid']));

		if (!empty($yyid)) {
			$sql = 'select doctor_name,doctor_ID from oa_doctor where find_in_set(' . $yyid . ', yy_ID) and doctor_del=0 order by doctor_sort desc';
			$data = m('doctor');
			$syid1 = mysql_query($sql);
			echo '<option value=\'\'>请选择预约医生</option>';

			while ($syid = mysql_fetch_array($syid1)) {
				echo '<option value=' . $syid['doctor_ID'] . '>' . $syid['doctor_name'] . '</option>';
			}
		}
	}

	public function zxtimexg()
	{
		$yyid = htmlspecialchars(trim($_POST['yyid']));

		if (!empty($yyid)) {
			$sql = 'select * from oa_quanjushezhi where qj_ID=3 and find_in_set(' . $yyid . ', qj_yyid)';
			$data = m('quanjushezhi');
			$isxuanzhong = $data->query($sql);

			if ($isxuanzhong[0]['qj_yyid'] != '') {
				echo 'form-control layer-date readonly1';
			}
			else {
				echo 'form-control layer-date readonly2';
			}
		}
	}

	public function shoujijc()
	{
		$sjhm = htmlspecialchars(trim($_POST['sjhm']));
		if(intval($sjhm)>0){
			$haoma = m('huanzeyuyue');
			$haomaman = m('managezx');
			$haoma1 = $haoma->where('shouji= \'' . $sjhm . '\'')->select();
			$haoma2 = $haomaman->where('shouji= \'' . $sjhm . '\'')->select();
			if (!empty($haoma1) || !empty($haoma2)) {
				echo '<span id=\'sjjc-error\' class=\'help-block m-b-none\'><i class=\'fa icon-info-sign\'></i>手机号已存在 <a href=javascript:sjhm(\'' . $sjhm . '\');>查看</a></span>';
			}
		}
	}

	public function ipdizhijc()
	{
		$ipdizhi = htmlspecialchars(trim($_POST['ipdizhi']));
		$dizhi = m('huanzejingjia');
		$dizhi1 = $dizhi->where('ipdizhi= \'' . $ipdizhi . '\'')->select();

		if (!empty($dizhi1)) {
			echo '<span style=\'color:#3c763d\'><i class=\'fa icon-info-sign\' ></i>IP地址已存在 <a href=javascript:iphm(\'' . $ipdizhi . '\');>查看</a></span>';
		}
	}

	public function shoujitj()
	{
		$sjhm = htmlspecialchars(trim($_POST['sjhm']));
		$arr = explode('|', $sjhm);
		if(!(intval($arr[0])>0)){
			echo 'okokok';
		}
		$haoma = m('huanzeyuyue');
		$haomaman = m('managezx');
		$haoma1 = $haoma->where('shouji= \'' . $arr[0] . '\'')->select();
		$haoma2 = $haomaman->where('shouji= \'' . $arr[0] . '\'')->select();
		if (!empty($haoma1) || !empty($haoma2)) {
			$shezhi = $haoma->query('select count(qj_ID) as total from oa_quanjushezhi where qj_ID=7 and find_in_set(' . $arr[1] . ', qj_yyid)');

			if ($shezhi[0]['total'] == '0') {
				echo 'no';
			}
			else {
				echo 'okokok';
			}
		}
		else {
			echo 'okokok';
		}
	}

	public function shoujisheng()
	{
		$sjhm = htmlspecialchars(trim($_POST['sjhm']));
		$ch = curl_init();
		$url = 'http://apis.baidu.com/showapi_open_bus/mobile/find?num=' . $sjhm;
		$header = array('apikey: 916e5efe634b5ccd22b5b4cc9f914097');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		$res = curl_exec($ch);
		$str = $res;
		$arr = json_decode($str, true);
		$sheng = $arr['showapi_res_body']['prov'];
		$chengshi = $arr['showapi_res_body']['city'];
		$filename = SITEURL . JS_URL . '/AreaData_min.js';
		$contents = file_get_contents($filename);
		$sheng = get_shengid($contents, $sheng);
		$chengshi = get_chengshiid($contents, $chengshi);
		echo $sheng . '/' . $chengshi;
	}

	public function shoujijc_update()
	{
		$sjhm = htmlspecialchars(trim($_POST['sjhm']));

		if (preg_match('/^1[0-9][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/', $sjhm)) {
			echo '√';
		}
		else {
			echo ' <a href=\'aaa/bbb\'>手机号码格式错误</a>';
			exit();
		}
	}

	public function add_insert()
	{
		$hide = $_POST['chongfutijiao'];
		if (($hide != '') && ($hide == $_SESSION['conn'])) {
			$shouji = $_POST['shouji'];
			$haoma = m('huanzeyuyue');
			$haomaman = m('managezx');
			$haoma1 = $haoma->where('shouji= \'' . $shouji . '\'')->select();
			$haoma2 = $haomaman->where('shouji= \'' . $shouji . '\'')->select();
			if((!empty($haoma1) || !empty($haoma2)) && intval($shouji)>0){
				js_alert('add', '手机号重复');
				exit();
			}

			unset($_SESSION['conn']);
			$huanze = m('huanze');

			if ($huanze->create()) {
				$zx_timee = i('post.zx_timee');
				$zixuntime = substr($huanze->zx_time . ' ' . $zx_timee, 0, 16);
				$xx_ID = $huanze->xx_ID;

				if (!empty($huanze->yuyueTime)) {
					$huanze->shifouyuyue = '0';
					$weiyuyue = '1';
				}
				else {
					$huanze->yuyueTime = Null;
				}

				$huanze->zx_time = $zixuntime;
				$huanze_ID = $huanze->add();
			}
			else {
				js_alert('', '添加失败');
			}

			if ($huanze_ID != '') {
				$huanzeYuyue = m('huanzeyuyue');
				$huanzeYuyue->create();

				if ($weiyuyue != '1') {
					$huanzeYuyue->yuyuehao = '';
					$yuyuehao = '';
				}
				else {
					$yuyuehao = $huanzeYuyue->yuyuehao;
				}

				if ($huanzeYuyue != '') {
					$huanzeYuyue->zx_ID = $huanze_ID;
					$huanzeYuyue->xiaofei = '0';
					$huanzeYuyue->add();
				}

				$huanzeCaozuo = m('huanzecaozuo');
				$huanzeCaozuo->create();

				if ($huanzeCaozuo != '') {
					$huanzeCaozuo->zx_ID = $huanze_ID;
					$huanzeCaozuo->add_time = date('Y/m/d H:i');
					$huanzeCaozuo->addUser_ID = session('username_lf');
					$huanzeCaozuo->add();
				}

				$huanzeJingjia = m('huanzejingjia');
				$huanzeJingjia->create();
				$yongjiushenfen = $huanzeJingjia->yongjiushenfen;
				$chucifangwen = $huanzeJingjia->chucifangwen;

				if ($huanzeJingjia != '') {
					$huanzeJingjia->zx_ID = $huanze_ID;
					$huanzeJingjia->xx_name = $xxname;
					$huanzeJingjia->yongjiushenfen = trim($yongjiushenfen);
					$huanzeJingjia->chucifangwen = trim($chucifangwen);
					$huanzeJingjia->add();
				}

				$huanze->create();
				$huanze->zx_ID = $huanze_ID;
				$userchinaname1 = m('useradmin');
				$userchinaname = $userchinaname1->where('user_ID=' . $huanze->userID . '')->getField('userchinaname');
				$yyname1 = m('yiyuan');
				$yyname = $yyname1->where('yy_ID=' . $huanze->yy_ID . '')->getField('yy_name');
				$zxfsname1 = m('zixunfangshi');
				$zxfsname = $zxfsname1->where('ID=' . $huanze->zxfs_ID . '')->getField('zxfs_name');
				$bingzhong1 = m('bingzhong');
				$bingzhong = $bingzhong1->where('ID=' . $huanze->bz_ID . '')->getField('bz_name');
				$xinxiname1 = m('xinxilaiyuan');
				$xxname = $xinxiname1->where('ID=' . $huanze->xx_ID . '')->getField('xx_name');

				if (!empty($huanze->yuyueTime)) {
					$shifouyuyue = '0';
					$weiyuyue = '1';
					$yuyueTime = $huanze->yuyueTime;
				}
				else {
					$yuyueTime = Null;
					$shifouyuyue = '1';
				}

				$huanzeYuyue->create();
				$huanzeJingjia->create();
				$laiyuanwangzhan1 = m('wangzhan');
				$wangzhan_url = $laiyuanwangzhan1->where('wangzhan_ID=' . $huanzeJingjia->laiyuanwangzhan . '')->getField('wangzhan_url');
				$huanzeInfo = m('huanzeinfo');
				$huanzeInfo->create();

				if ($huanzeInfo->xingbie == 1) {
					$xingbie = '男';
				}
				else {
					$xingbie = '女';
				}

				if ($huanzeInfo != '') {
					$huanzeInfo->zx_ID = $huanze_ID;

					if ($huanzeInfo->shengri == '') {
						$huanzeInfo->shengri = Null;
					}

					$huanzeInfo->liaotianjilu = $_POST['liaotianjilu'];
					$file = $_FILES['fujian'];
					$grxxid = $huanzeInfo->add();
				}

				$huanzeInfo->create();
				$managezx = array('zx_ID' => $huanze_ID, 'userID' => $huanze->userID, 'userchinaname' => $userchinaname, 'yy_ID' => $huanze->yy_ID, 'yy_name' => $yyname, 'zxfs_ID' => $huanze->zxfs_ID, 'zxfs_name' => $zxfsname, 'bz_ID' => $huanze->bz_ID, 'bz_name' => $bingzhong, 'xx_ID' => $huanze->xx_ID, 'xx_name' => $xxname, 'zx_time' => $zixuntime, 'yuyueTime' => $yuyueTime, 'shifouyuyue' => $shifouyuyue, 'huanzeName' => $huanzeYuyue->huanzeName, 'shouji' => $huanzeYuyue->shouji, 'yuyueyishengID' => $huanzeYuyue->yuyueyishengID, 'guanjianci' => $huanzeJingjia->guanjianci, 'laiyuanwangzhan' => $huanzeJingjia->laiyuanwangzhan, 'wangzhan_url' => $wangzhan_url, 'fangwenrukou' => $huanzeJingjia->fangwenrukou, 'faqizixun' => $huanzeJingjia->faqizixun, 'ppguanjianci' => $huanzeJingjia->ppguanjianci, 'yuyuehao' => $yuyuehao, 'IPdizhi' => $huanzeJingjia->IPdizhi, 'xiaofei' => 0, 'yuyueBeizhu' => $huanzeYuyue->yuyueBeizhu, 'QQhaoma' => $huanzeInfo->QQhaoma, 'yongjiushenfen' => trim($yongjiushenfen), 'chucifangwen' => trim($chucifangwen), 'weixinhao' => $huanzeInfo->weixinhao, 'age' => $huanzeInfo->age, 'xingbie' => $xingbie);
				$zonghe = m('managezx');
				$zonghe->add($managezx);
				$yuyuetimeupdate = array('zx_ID' => $huanze_ID, 'yuyuetime' => $yuyueTime, 'updatetime' => date('Y-m-d H:i:s'), 'userID' => $huanze->userID);
				$yuyue_updatetime = m('yuyue_timeupdate');
				$yuyue_updatetime->add($yuyuetimeupdate);

				if (is_uploaded_file($file['tmp_name'])) {
					$upload = new \Think\Upload();
					$upload->maxSize = 3145728000;
					$upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'html', 'htm', 'mp3', 'wav', 'doc', 'docx', 'rtf', 'wps', 'wpt');
					$upload->rootPath = './OA/Uploads_lf/';
					$upload->savePath = '';
					$info = $upload->upload();

					if (!$info) {
						$this->error($upload->getError());
					}
					else {
						$data['fujian'] = $info['fujian']['savepath'] . $info['fujian']['savename'];
						$huanzeInfo->where('grxx_ID=' . $grxxid . '')->save($data);
						$this->success('上传成功！');
					}
				}
			}

			js_alert('add', '添加完成');
		}
		else {
			js_alert('add', '已提交,不要重复提交!');
		}
	}
}


?>
