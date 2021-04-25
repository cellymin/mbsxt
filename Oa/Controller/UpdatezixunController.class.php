<?php

namespace OA\Controller;


class UpdatezixunController extends \Component\AdminController
{
	public function update()
	{
		$data = m('huanze');
		$zx_ID = i('get.zx_ID');
		$ly = i('get.');

		foreach ($ly as $key => $value ) {
			$URLcs .= '&' . $key . '=' . $value;
		}

		$URLcs = ltrim($URLcs, '&');
		$zixun = $data->query('select * from oa_managezx where zx_ID = ' . $zx_ID . '');
		$doctor = $data->query('select doctor_ID,doctor_name from oa_doctor where yy_ID=' . $zixun[0]['yy_ID'] . ' and doctor_del=0 ');
		$doctor_name = $data->query('select doctor_name from oa_doctor where doctor_ID =' . $zixun[0]['yuyueyishengID'] . '');

		if ($zixun[0]['shifoudaozhen'] == 1) {
			$dianji = '确认到诊';
			$daozhentime = date('Y-m-d H:i');
		}
		else {
			$dianji = '取消到诊';
			$daozhentime = $zixun[0]['daozhen_time'];
		}

		if ($zixun[0]['shifoudaozhen'] == 0) {
			$zhuangtai = '已来院';
		}
		else {
			if (($zixun[0]['shifoudaozhen'] == 1) && ($zixun[0]['shifouyuyue'] == 0)) {
				$zhuangtai = '已预约 未来院';
			}
			else if ($zixun[0]['shifouyuyue'] == 1) {
				$zhuangtai = '仅咨询';
			}
		}

		$yuyuehao = $data->query('select yuyuehao from oa_huanzeyuyue where zx_ID = ' . $zx_ID . '');
		$this->assign('dqURLcanshu', $URLcs);
		$this->assign('doctor', $doctor);
		$this->assign('doctor_name', $doctor_name[0]['doctor_name']);
		$this->assign('dianji', $dianji);
		$this->assign('zhuangtai', $zhuangtai);
		$this->assign('yuyuehao', $yuyuehao[0]['yuyuehao']);
		$this->assign('daozhentime', substr($daozhentime, 0, 21));
		$shezhi = $data->query('select count(qj_ID) as total from oa_quanjushezhi where qj_ID=6 and find_in_set(' . $zixun[0]['yy_ID'] . ', qj_yyid)');

		if ($shezhi[0]['total'] == '0') {
			$this->assign('quanju', 'readonly');
		}
		else {
			$this->assign('quanju', 'onClick="laydate({istime: true, format:\'YYYY-MM-DD hh:mm\', min:laydate.now()})"');
		}

		$huanzexinxi = $data->query('select * from oa_huanzeinfo where zx_ID = ' . $zx_ID . '');
		$huanzeyuyue = $data->query('select * from oa_huanzeyuyue where zx_ID = ' . $zx_ID . '');
		$this->assign('huanzeyuyue', $huanzeyuyue);
		$this->assign('huanzexinxi', $huanzexinxi);
		$this->assign('yy_ID', $zixun[0]['yy_ID']);
		$this->assign('zxfs_ID', $zixun[0]['zxfs_ID']);
		$this->assign('bz_ID', $zixun[0]['bz_ID']);
		$user = m('Useradmin');
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

		$this->assign('ssyy', $ssyy);
		$zixunyuan = $user->query('select user_ID,userchinaname from  oa_useradmin where find_in_set(' . $zixun[0]['yy_ID'] . ',yy_ID) and user_del= \'0\' and role_ID in(4,5)');
		$this->assign('zixunyuan', $zixunyuan);
		$qj = m('quanjushezhi');
		$quanju = $qj->query('select * from oa_quanjushezhi where qj_ID=4 and find_in_set(' . $zixun[0]['yy_ID'] . ',qj_yyid)');

		if ($quanju[0]['qj_yyid'] == '') {
			$this->assign('qj_zixunyuan', 'readonly');
		}

		if ($huanzexinxi[0]['xingbie'] == 1) {
			$this->assign('xingbie1', 'selected="selected"');
		}
		else {
			$this->assign('xingbie2', 'selected="selected"');
		}

		if ($huanzexinxi[0]['hunyin'] == 1) {
			$this->assign('hunyin1', 'selected="selected"');
		}
		else {
			$this->assign('hunyin2', 'selected="selected"');
		}

		$this->assign('shengri', strtr($huanzexinxi[0]['shengri'], '-', '/'));
		$this->assign('diqu_sheng', $huanzexinxi[0]['seachprov']);
		$this->assign('diqu_shi', $huanzexinxi[0]['homecity']);
		$this->assign('diqu_qu', $huanzexinxi[0]['seachdistrict']);
		$yisheng = m('doctor');
		$yisheng1 = $yisheng->query('select doctor_ID,doctor_name from oa_doctor where yy_ID=' . $zixun[0]['yy_ID'] . ' and doctor_del=0');
		$this->assign('yisheng', $yisheng1);
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
		$this->assign('siteurl', $_SERVER['HTTP_HOST']);
		$yinpin = explode('.', $huanzexinxi[0]['fujian']);
		$yinpinarr = array('mp3', 'wav');

		if (in_array($yinpin[1], $yinpinarr)) {
			$this->assign('yinpin', '0');
		}
		else {
			$this->assign('yinpin', '1');
		}

		$laiyuanwangzhan = m('wangzhan');
		$laiyuanwangzhan1 = $laiyuanwangzhan->query('select wangzhan_ID,wangzhan_url from oa_wangzhan where yy_ID=' . $zixun[0]['yy_ID'] . ' and wangzhan_del=0');
		$this->assign('laiyuanwangzhan', $laiyuanwangzhan1);
		$xinxilaiyuan = m('xinxilaiyuan');
		$xinxilaiyuan1 = $xinxilaiyuan->query('select ID,xx_name from oa_xinxilaiyuan where find_in_set(' . $zixun[0]['yy_ID'] . ',yy_ID) and xx_del=0');
		$this->assign('xinxilaiyuan', $xinxilaiyuan1);
		$zonghe = m('yuyue_timeupdate');
		$oldyytime = $zonghe->where('zx_ID=' . $zx_ID . '')->getField('yuyueTime', true);

		for ($i = 0; $i < count($oldyytime); $i++) {
			$yytime_xgjl .= substr($oldyytime[$i], 0, 10) . ' | ';
		}

		$this->assign('oldyytime', $yytime_xgjl);

		if ($zixun[0]['chucifangwen'] == '0000-00-00 00:00:00') {
			$zixun[0]['chucifangwen'] = '';
		}

		$this->assign('zixun', $zixun);
		$huifangManage = m('huanzehuifang');
		$huifangxinxi = $huifangManage->where('zx_ID=' . $zx_ID . '')->order('zxhf_ID desc')->select();
		$this->assign('cishu', count($huifangxinxi));
		$this->assign('huifangxinxi', $huifangxinxi);
		$shezhi = $huifangManage->query('select count(qj_ID) as total from oa_quanjushezhi where qj_ID=8 and find_in_set(' . $zixun[0]['yy_ID'] . ', qj_yyid)');

		if ($shezhi[0]['total'] == '0') {
			$this->assign('quanju_hfsj', 'readonly');
		}
		else {
			$this->assign('quanju_hfsj', 'id="today2"');
		}

		$hfjh = m('huifangjihua');
		$hfjhArr = $hfjh->where('zx_ID=' . $zx_ID . '')->order('hfjh_time desc')->select();
		$this->assign('hfjh', $hfjhArr);
		$this->display('');
	}

	public function daozhen()
	{
		$data = m('huanze');
		$ly = i('get.');

		foreach ($ly as $key => $value ) {
			$URLcs .= '&' . $key . '=' . $value;
		}

		$URLcs = ltrim($URLcs, '&');
		$dao['shifoudaozhen'] = 0;
		$dao['daozhen_time'] = date('Y-m-d H:i:s');
		$data->where('zx_ID=' . $ly['zixunID'] . '')->save($dao);
		$manage1 = m('managezx');
		$manage1->where('zx_ID=' . $ly['zixunID'] . '')->save($dao);
		$dataDao = m('huanzecaozuo');
		$daozhen['daozhenUserID'] = session('username_lf');
		$dataDao->where('zx_ID=' . $ly['zixunID'] . '')->save($daozhen);
		js_alert1('' . DQURL . 'ManageZx/manage?' . $URLcs . '', '修改成功');
	}

	public function daozhenXQ()
	{
		$data = m('huanze');
		$ly = i('post.');
		$zhuangtai = $data->where('zx_ID=' . $ly['zixunID'] . '')->getField('shifoudaozhen');

		if ($zhuangtai == 1) {
			$dao['shifoudaozhen'] = 0;
			$dao['daozhen_time'] = $ly['daozhen_time'];
			$data->query('update oa_huanzeyuyue set jiuzhenyishengID=' . $ly['jiuzhenyishengID'] . ' where zx_ID =' . $ly['zixunID'] . '');
		}
		else {
			if (($_SESSION['user_role'] == 1) || ($_SESSION['user_role'] == 2)) {
			}
			else {
				$yyid = $data->where('zx_ID=' . $ly['zixunID'] . '')->getField('yy_ID');
				$shezhiquxiao = $data->query('select count(qj_ID) as total from oa_quanjushezhi where qj_ID=1 and find_in_set(' . $yyid . ', qj_yyid)');

				if ($shezhiquxiao[0]['total'] == '0') {
					js_alert('', '没有权限操作,请联系管理员');
					exit();
				}
			}

			$dao['shifoudaozhen'] = 1;
			$dao['daozhen_time'] = Null;
			$data->query('update oa_huanzeyuyue set jiuzhenyishengID=\'\' where zx_ID =' . $ly['zixunID'] . '');
		}

		$data->where('zx_ID=' . $ly['zixunID'] . '')->save($dao);
		$manage1 = m('managezx');
		$manage1->where('zx_ID=' . $ly['zixunID'] . '')->save($dao);
		$dataDao = m('huanzecaozuo');
		$daozhen['daozhenUserID'] = session('username_lf');
		$dataDao->where('zx_ID=' . $ly['zixunID'] . '')->save($daozhen);
		js_alert('Updatezixun/update?' . $ly['URLcs'] . '', '修改成功');
	}

	public function zixun_update()
	{
		$ly = i('post.URLcs');
		$huanze = m('huanze');
		$zx_ID = i('post.zx_ID');
		$zonghe = m('managezx');
		$oldyytime = $zonghe->where('zx_ID=' . $zx_ID . '')->getField('yuyueTime');
		$yuyueshijian = i('post.yuyueTime');
		$zixun = $huanze->query('select * from oa_managezx where zx_ID = ' . $zx_ID . '');
		$chushiyyzt = $zixun[0]['shifouyuyue'];

		$shouji = $_POST['shouji'];
		$haoma = m('huanzeyuyue');
		$haoma1 = $haoma->where('shouji= \'' . $shouji . '\' AND zx_ID!=\''.$zx_ID.'\'')->select();
		$haoma2 = $zonghe->where('shouji= \'' . $shouji . '\' AND zx_ID!=\''.$zx_ID.'\'')->select();
		if((!empty($haoma1) || !empty($haoma2)) && intval($shouji)>0){
			js_alert('', '修改失败，手机号重复');
			exit();
		}
		if ($zixun[0]['shifouyuyue'] == 0) {
			if (substr($oldyytime, 0, 10) != substr($yuyueshijian, 0, 10)) {
				if (empty($yuyueshijian)) {
					js_alert('', '不可以取消预约状态');
					exit();
				}

				if (strtotime($yuyueshijian) < strtotime(date('Y-m-d'))) {
					js_alert('', '修改的预约时间,不可以早于当前时间！');
					exit();
				}
			}
		}

		if (($zixun[0]['shifouyuyue'] == 1) && !empty($yuyueshijian)) {
			if (strtotime(substr($yuyueshijian, 0, 10)) < strtotime(date('Y-m-d'))) {
				js_alert('', '修改的预约时间,不可以早于当前时间！');
				exit();
			}
		}

		if ($huanze->create()) {
			$zx_timee = i('post.zx_timee');
			$zixuntime = substr($huanze->zx_time . ' ' . $zx_timee, 0, 16);
			$xx_ID = $huanze->xx_ID;

			if (!empty($huanze->yuyueTime)) {
				$huanze->shifouyuyue = '0';
				$weiyuyue = '1';
			}
			else {
				$huanze->shifouyuyue = '1';
				$huanze->yuyueTime = Null;
			}

			if ($huanze->shifouzhuyuan == '') {
				$huanze->shifouzhuyuan = '1';
				$shifouzhuyuan = '1';
			}
			else {
				$shifouzhuyuan = '0';
			}

			$huanze->zx_time = $zixuntime;
			$huanze->save();
		}
		else {
			js_alert('', '修改失败');
			exit();
		}

		$huanzeYuyue = m('huanzeyuyue');
		$huanzeYuyue->create();

		if ($weiyuyue != '1') {
			$huanzeYuyue->yuyuehao = '';
			$yuyuehao = '';
		}
		else {
			$yuyuehao = $huanzeYuyue->yuyuehao;
		}

		$huanzeYuyue->where('zx_ID=' . $huanzeYuyue->zx_ID . '')->save();
		$huanzeCaozuo = m('huanzecaozuo');
		$huanzeCaozuo->create();
		$huanzeCaozuo->last_time = date('Y-m-d H:i:s');
		$huanzeCaozuo->last_updateuserID = session('username_lf');
		$huanzeCaozuo->where('zx_ID=' . $huanzeCaozuo->zx_ID . '')->save();
		$xinxiname = m('xinxilaiyuan');
		$xxname = $xinxiname->where('ID=' . $xx_ID . '')->getField('xx_name');
		$huanzeJingjia = m('huanzejingjia');
		$huanzeJingjia->create();
		$chucifangwen = $huanzeJingjia->chucifangwen;
		$yongjiushenfen = $huanzeJingjia->yongjiushenfen;
		$huanzeJingjia->yongjiushenfen = trim($yongjiushenfen);
		$huanzeJingjia->chucifangwen = trim($chucifangwen);
		$huanzeJingjia->xx_name = $xxname;
		$huanzeJingjia->where('zx_ID=' . $huanzeJingjia->zx_ID . '')->save();
		$huanzeInfo = m('huanzeinfo');
		$huanzeInfo->create();

		if ($huanzeInfo->shengri == '') {
			$huanzeInfo->shengri = Null;
		}

		$huanzeInfo->liaotianjilu = $_POST['liaotianjilu'];
		$info_zxID = $huanzeInfo->zx_ID;
		$file = $_FILES['fujian'];
		$huanzeInfo->where('zx_ID=' . $huanzeInfo->zx_ID . '')->save();
		$huanze->create();
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
		$huanzeInfo->create();

		if ($huanzeInfo->xingbie == '1') {
			$xingbie = '男';
		}
		else {
			$xingbie = '女';
		}

		$laiyuanwangzhan1 = m('wangzhan');
		$wangzhan_url = $laiyuanwangzhan1->where('wangzhan_ID=' . $huanzeJingjia->laiyuanwangzhan . '')->getField('wangzhan_url');
		$managezx = array('userID' => $huanze->userID, 'userchinaname' => $userchinaname, 'yy_ID' => $huanze->yy_ID, 'yy_name' => $yyname, 'zxfs_ID' => $huanze->zxfs_ID, 'zxfs_name' => $zxfsname, 'bz_ID' => $huanze->bz_ID, 'bz_name' => $bingzhong, 'xx_ID' => $huanze->xx_ID, 'xx_name' => $xxname, 'zx_time' => $zixuntime, 'yuyueTime' => $yuyueTime, 'shifouyuyue' => $shifouyuyue, 'huanzeName' => $huanzeYuyue->huanzeName, 'shouji' => $huanzeYuyue->shouji, 'yuyueyishengID' => $huanzeYuyue->yuyueyishengID, 'guanjianci' => $huanzeJingjia->guanjianci, 'laiyuanwangzhan' => $huanzeJingjia->laiyuanwangzhan, 'wangzhan_url' => $wangzhan_url, 'fangwenrukou' => $huanzeJingjia->fangwenrukou, 'faqizixun' => $huanzeJingjia->faqizixun, 'ppguanjianci' => $huanzeJingjia->ppguanjianci, 'yuyuehao' => $yuyuehao, 'IPdizhi' => $huanzeJingjia->IPdizhi, 'yuyueBeizhu' => $huanzeYuyue->yuyueBeizhu, 'QQhaoma' => $huanzeInfo->QQhaoma, 'yuyueBeizhu' => $huanzeYuyue->yuyueBeizhu, 'shifouzhuyuan' => $shifouzhuyuan, 'xiaofei' => $huanzeYuyue->xiaofei, 'yongjiushenfen' => trim($yongjiushenfen), 'chucifangwen' => trim($chucifangwen), 'weixinhao' => $huanzeInfo->weixinhao, 'xingbie' => $xingbie, 'age' => $huanzeInfo->age);
		$zonghe->where('zx_ID=' . $huanze->zx_ID . '')->save($managezx);

		if (substr($oldyytime, 0, 10) != substr($yuyueTime, 0, 10)) {
			$yuyuetimeupdate = array('zx_ID' => $huanze->zx_ID, 'yuyuetime' => $yuyueTime, 'updatetime' => date('Y-m-d H:i:s'), 'userID' => $huanze->userID);
			$yuyue_updatetime = m('yuyue_timeupdate');
			$yuyue_updatetime->where('zx_ID=' . $huanze->zx_ID . '')->add($yuyuetimeupdate);
		}

		if (($chushiyyzt == 1) && ($shifouyuyue == 0)) {
			if (strtotime(substr($zixuntime, 0, 10)) < strtotime(date('Y-m-d'))) {
				$buyuyue['shifoubuyuyue'] = 0;
				$buyuyue['buyuyue_caozuotime'] = date('Y-m-d H:i:s');
				$huanzeYuyue->where('zx_ID=' . $zx_ID . '')->save($buyuyue);
			}
		}

		if (is_uploaded_file($file['tmp_name'])) {
			$OldFujian = $huanzeInfo->where('zx_ID=' . $info_zxID . '')->getField('fujian');
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
				$huanzeInfo->where('zx_ID=' . $info_zxID . '')->save($data);
				unlink('./OA/Uploads_lf/' . $OldFujian . '');
			}
		}

		js_alert('Updatezixun/update?' . $ly . '', '修改成功');
	}

	public function huifang_insert()
	{
		if (i('post.hfjh_time') != '') {
			$hfjh = m('huifangjihua');
			$hfjh->create();
			$zx_ID = $hfjh->zx_ID;
			$hfjh->adduser_ID = session('username_lf');

			if ($hfjh->hfjh_zhuti == '') {
				js_alert('', '下次回访主题必须填写');
				exit();
			}

			if (strtotime($hfjh->hfjh_time) < strtotime(date('Y-m-d'))) {
				js_alert('', '时间不允许小于当前');
				exit();
			}
		}

		$data = d('huanzehuifang');
		$ly = i('post.URLcs');
		$zxid = i('post.zx_ID');
		$hfjh_ID = i('post.hfjh_ID');
		$benyeTiaozhuan = i('post.benye');
		$hf_zhuti = i('post.hf_zhuti');
		$huifang_time = i('post.hf_time');

		if ($data->create()) {
			$data->user_ID = session('username_lf');
			$data->hf_addtime = substr(date('Y-m-d H:i:s'), 0, 16);
			$result = $data->add();
			$file = $_FILES['fujian'];

			if (is_uploaded_file($file['tmp_name'])) {
				$upload = new \Think\Upload();
				$upload->maxSize = 3145728;
				$upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'html', 'htm');
				$upload->rootPath = './OA/Uploads_lf/';
				$upload->savePath = '';
				$info = $upload->upload();

				if (!$info) {
					$this->error($upload->getError());
				}
				else {
					$hf_data['hf_fujian'] = $info['fujian']['savepath'] . $info['fujian']['savename'];
					$data->where('zxhf_ID=' . $result . '')->save($hf_data);
				}
			}

			$hufiangcishu = m('huanze');
			mysql_query('update oa_huanze set huifangcishu = huifangcishu+1 where zx_ID=' . $zxid . '');
			$hufiangcishu = m('managezx');
			mysql_query('update oa_managezx set huifangcishu = huifangcishu+1 where zx_ID=' . $zxid . '');
			mysql_query('update oa_managezx set lasthuifang = \'' . $hf_zhuti . '\',lasthuifang_time=\'' . $huifang_time . '\' where zx_ID=' . $zxid . '');

			if ($hfjh_ID != '') {
				mysql_query('update oa_huifangjihua set zxhf_ID = ' . $result . ',hfzuser_ID=' . session('username_lf') . ' where hfjh_ID=' . $hfjh_ID . '');
			}

			if (i('post.hfjh_time') != '') {
				$hfjh = m('huifangjihua');
				$huifangjihua1 = $hfjh->create();
				$b = 'hfjh_ID';

				foreach ($huifangjihua1 as $k => $v ) {
					if ($k == $b) {
						unset($huifangjihua1[$k]);
					}
				}

				$huifangjihua1['adduser_ID'] = session('username_lf');
				$hfjh->add($huifangjihua1);
			}

			if ($ly != '') {
				if ($benyeTiaozhuan == 1) {
					js_alert('Updatezixun/update?' . $ly . '', '添加回访成功');
				}
				else {
					js_alert('Updatezixun/update?' . $ly . '', '添加回访成功');
				}
			}
			else {
				echo '<script>var index=parent.layer.getFrameIndex(window.name);parent.layer.msg(\'登记成功\');parent.iframe0.location.reload();parent.layer.close(index);</script>';
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function huifangXiangqing()
	{
		$data = d('huanzehuifang');
		$zxhf_ID = i('get.zxhf_ID');
		$zx_ID1 = $data->query('select  zx_ID,hf_time,hf_content,hf_zhuti,user_ID,hf_addtime,hf_fangshi,hf_fujian from oa_huanzehuifang where zxhf_ID=' . $zxhf_ID . '');
		$zx_ID = $zx_ID1[0]['zx_ID'];
		$this->assign('hf_time', $zx_ID1[0]['hf_time']);
		$this->assign('hf_zhuti', $zx_ID1[0]['hf_zhuti']);
		$this->assign('hf_content', $zx_ID1[0]['hf_content']);
		$this->assign('hf_fujian', $zx_ID1[0]['hf_fujian']);

		if ($zx_ID1[0]['hf_fangshi'] == '电话') {
			$this->assign('fangshi1', 'selected');
		}
		else if ($zx_ID1[0]['hf_fangshi'] == '短信') {
			$this->assign('fangshi2', 'selected');
		}
		else if ($zx_ID1[0]['hf_fangshi'] == 'QQ') {
			$this->assign('fangshi3', 'selected');
		}
		else if ($zx_ID1[0]['hf_fangshi'] == '微信') {
			$this->assign('fangshi4', 'selected');
		}
		else if ($zx_ID1[0]['hf_fangshi'] == '其他') {
			$this->assign('fangshi5', 'selected');
		}

		$userchinaname1 = $data->query('select userchinaname from oa_useradmin where user_ID=' . $zx_ID1[0]['user_ID'] . '');
		$this->assign('userchinaname', $userchinaname1[0]['userchinaname']);
		$huifangCount = $data->where('zx_ID=' . $zx_ID . '')->count();
		$dangqiancishu1 = i('get.cishu');
		$this->assign('dangqiancishu', ($huifangCount - $dangqiancishu1) + 1);
		$this->assign('huifangCount', $huifangCount);
		$huanzexinxi = $data->query('select huanzeName,shouji,yuyueTime,shifoudaozhen,shifouyuyue from oa_managezx where zx_ID=' . $zx_ID . '');

		if ($huanzexinxi[0]['shifoudaozhen'] == 0) {
			$zhuangtai = '已来院';
		}
		else {
			if (($huanzexinxi[0]['shifoudaozhen'] == 1) && ($huanzexinxi[0]['shifouyuyue'] == 0)) {
				$zhuangtai = '已预约 未来院';
			}
			else if ($huanzexinxi[0]['shifouyuyue'] == 1) {
				$zhuangtai = '仅咨询';
			}
		}

		$this->assign('zhuangtai', $zhuangtai);
		$this->assign('huanzename', $huanzexinxi[0]['huanzeName']);
		$this->assign('shouji', $huanzexinxi[0]['shouji']);
		$this->assign('yuyuetime', $huanzexinxi[0]['yuyueTime']);
		$this->assign('siteurl', $_SERVER['HTTP_HOST']);
		$this->display('');
	}

	public function huifangdengji()
	{
		$canshu = i('get.');
		$data = d('huanzehuifang');
		$sql = 'select huanzeName,shouji,yuyueTime,yy_ID,userID from oa_managezx where zx_ID=' . $canshu['zx_ID'] . '';
		$shuju = $data->query($sql);
		$this->assign('huanzeName', $shuju[0]['huanzeName']);
		$this->assign('shouji', $shuju[0]['shouji']);
		$this->assign('yuyueTime', $shuju[0]['yuyueTime']);
		$this->assign('huifangtime', date('Y-m-d H:i'));
		$this->assign('dqURLcanshu', $canshu['URLcs']);
		$this->assign('hfjh_ID', $canshu['hfjh_ID']);
		$this->assign('yy_ID', $shuju[0]['yy_ID']);
		$this->assign('user_ID', $shuju[0]['userID']);
		$shezhi = $data->query('select count(qj_ID) as total from oa_quanjushezhi where qj_ID=8 and find_in_set(' . $shuju[0]['yy_ID'] . ', qj_yyid)');

		if ($shezhi[0]['total'] == '0') {
			$this->assign('quanju_hfsj', 'readonly');
		}
		else {
			$this->assign('quanju_hfsj', 'id="today2"');
		}

		$this->display('');
	}

	public function huifangjihua()
	{
		$canshu = i('get.');
		$data = d('huanzehuifang');
		$sql = 'select huanzeName,shouji,yuyueTime,yy_ID,userID from oa_managezx where zx_ID=' . $canshu['zx_ID'] . '';
		$shuju = $data->query($sql);
		$this->assign('huanzeName', $shuju[0]['huanzeName']);
		$this->assign('shouji', $shuju[0]['shouji']);
		$this->assign('yuyueTime', $shuju[0]['yuyueTime']);
		$this->assign('huifangtime', date('Y-m-d H:i'));
		$this->assign('dqURLcanshu', $canshu['URLcs']);
		$this->assign('hfjh_ID', $canshu['hfjh_ID']);
		$this->assign('yy_ID', $shuju[0]['yy_ID']);
		$this->assign('user_ID', $shuju[0]['userID']);
		$this->display('');
	}

	public function huifangxiangq()
	{
		$canshu = i('get.');
		$zx_ID = $canshu['zx_ID'];
		$zxhf_ID = $canshu['hfjh_ID'];
		$huifangManage = m('huanzehuifang');
		$huifangxinxi = $huifangManage->where('zx_ID=' . $zx_ID . '')->order('zxhf_ID desc')->select();
		$this->assign('cishu', count($huifangxinxi));
		$this->assign('huifangxinxi', $huifangxinxi);
		$this->display('');
	}

	public function huifang_update()
	{
		$huifang = m('huanzehuifang');
		$data['hf_zhuti'] = i('post.hf_zhuti');
		$data['hf_fangshi'] = i('post.hf_fangshi');
		$data['hf_content'] = i('post.hf_content');
		$zxfh_ID = i('post.zxhf_ID');
		$huifang->where('zxhf_ID=' . $zxfh_ID . '')->save($data);

		if ($huifang->where('zxhf_ID=' . $zxfh_ID . '')->save($data) !== false) {
			$zx_ID = $huifang->where('zxhf_ID=' . $zxfh_ID . '')->getField('zx_ID');
			$big_zxfh_ID = $huifang->where('zx_ID=' . $zx_ID . '')->order('zxhf_ID desc')->find();
			$lasthufiang_zhuti = $big_zxfh_ID['hf_zhuti'];
			$lasthufiang_hfID = $big_zxfh_ID['zxhf_ID'];

			if ($lasthufiang_hfID == $zxfh_ID) {
				mysql_query('update oa_managezx set lasthuifang = \'' . $lasthufiang_zhuti . '\' where zx_ID =' . $zx_ID . '');
			}

			$file = $_FILES['fujian'];

			if (is_uploaded_file($file['tmp_name'])) {
				$OldFujian = $huifang->where('zxhf_ID=' . $zxfh_ID . '')->getField('hf_fujian');
				$upload = new \Think\Upload();
				$upload->maxSize = 3145728;
				$upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'html', 'htm');
				$upload->rootPath = './OA/Uploads_lf/';
				$upload->savePath = '';
				$info = $upload->upload();

				if (!$info) {
					$this->error($upload->getError());
				}
				else {
					$dataFj['hf_fujian'] = $info['fujian']['savepath'] . $info['fujian']['savename'];
					mysql_query('update oa_huanzehuifang set hf_fujian = \'' . $dataFj['hf_fujian'] . '\' where zxhf_ID=' . $zxfh_ID . '');
					unlink('./OA/Uploads_lf/' . $OldFujian . '');
				}
			}

			js_alert('Updatezixun/huifangxiangqing?zxhf_ID=' . $zxfh_ID . '', '修改成功');
		}
		else {
			js_alert('', '修改失败');
		}
	}

	public function huifang_del()
	{
		$zxhf_ID = i('get.zxhf_ID');
		$ly = i('get.');

		foreach ($ly as $key => $value ) {
			$URLcs .= '&' . $key . '=' . $value;
		}

		$URLcs = ltrim($URLcs, '&');
		$hzhf = m('huanzehuifang');
		$zx_ID = $hzhf->where('zxhf_ID=' . $zxhf_ID . '')->getField('zx_ID');

		if ($hzhf->delete($zxhf_ID)) {
			mysql_query('update oa_huanze set huifangcishu = huifangcishu-1 where zx_ID=' . $zx_ID . '');
			mysql_query('update oa_managezx set huifangcishu = huifangcishu-1 where zx_ID=' . $zx_ID . '');
			$big_zxfh_ID = $hzhf->where('zx_ID=' . $zx_ID . '')->order('zxhf_ID desc')->find();
			$lasthuifang_zhuti = $big_zxfh_ID['hf_zhuti'];
			$lasthuifang_time = $big_zxfh_ID['hf_time'];
			mysql_query('update oa_managezx set lasthuifang = \'' . $lasthuifang_zhuti . '\',lasthuifang_time = \'' . $lasthuifang_time . '\' where zx_ID =' . $zx_ID . '');
			js_alert('Updatezixun/update?' . $URLcs . '&zxhf_ID=' . $zx_ID . '', '删除成功');
		}
		else {
			js_alert('', '删除失败');
		}
	}

	public function huifangjihua_del()
	{
		$hfjh_ID = i('get.hfjh_ID');
		$ly = i('get.');

		foreach ($ly as $key => $value ) {
			$URLcs .= '&' . $key . '=' . $value;
		}

		$URLcs = ltrim($URLcs, '&');
		$hzhf = m('huanzehuifang');
		$hfjh = m('huifangjihua');
		$zx_ID = $hzhf->where('hfjh_ID=' . $hfjh_ID . '')->getField('zx_ID');

		if ($zx_ID != '') {
			js_alert('', '存在匹配回访记录，无法删除');
			exit();
		}

		if ($hfjh->delete($hfjh_ID)) {
			js_alert('Updatezixun/update?' . $URLcs . '&zxhf_ID=' . $zx_ID . '', '删除成功');
		}
		else {
			js_alert('', '删除失败');
		}
	}

	public function huifangjihua_insert()
	{
		$hfjh = m('huifangjihua');
		$hfjh->create();
		$ly = i('post.URLcs');
		$zx_ID = $hfjh->zx_ID;
		$hfjh->adduser_ID = session('username_lf');

		if ($hfjh->hfjh_time == '') {
			js_alert('', '下次回访时间必须填写');
			exit();
		}

		if ($hfjh->hfjh_zhuti == '') {
			js_alert('', '下次回访主题必须填写');
			exit();
		}

		if (strtotime($hfjh->hfjh_time) < strtotime(date('Y-m-d'))) {
			js_alert('', '时间不允许小于当前');
			exit();
		}

		if ($hfjh->add()) {
			if ($ly != '') {
				js_alert('Updatezixun/update?' . $ly . '', '添加回访计划成功');
			}
			else {
				echo '<script>var index=parent.layer.getFrameIndex(window.name);parent.layer.msg(\'登记成功\');parent.iframe0.location.reload();parent.layer.close(index);</script>';
			}
		}
		else {
			js_alert('', '添加失败');
		}
	}
}


?>
