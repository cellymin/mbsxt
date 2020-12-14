<?php

namespace OA\Controller;
use PHPExcel_IOFactory;


class SystemSiteController extends \Component\AdminController
{
	public function system()
	{
		$this->display();
	}

	public function system_yy()
	{
		$m = d('Yiyuan');
		$total = $m->count();
		$pagesize = 10;
		$page = new \Component\Page($total, $pagesize);
		$yiyuan = d()->query('select yy_ID from oa_useradmin where user_ID=' . session('username_lf') . '');

		if (session('user_role') != 1) {
			$sql = 'select * from oa_yiyuan where yy_ID in(' . $yiyuan[0]['yy_ID'] . ') ' . $page->limit;
		}
		else {
			$sql = 'select * from oa_yiyuan ' . $page->limit;
		}

		$info = $m->query($sql);
		$list = $page->fpage();
		$this->assign('list', $info);
		$this->assign('page', $list);
		$this->display();
	}

	public function yy_insert()
	{
		$data = d('Yiyuan');

		if ($data->create()) {
			$result = $data->add();
			//$YYbig = bigyy();

			//if ($YYbig != '') {
				//js_alert2('', '超出购买' . $YYbig . '家门店的数量，请联系软件公司！');
				//exit();
			//}

			echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('SystemSite/system_yy') . '\';</script>';
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_yy_stop()
	{
		$data = m('Yiyuan');
		$ly = i('get.');
		$xx_del = $data->where('yy_ID=' . $ly['ID'] . '')->getField('yy_del');

		if ($xx_del == 0) {
			$data->where('yy_ID=' . $ly['ID'] . '')->setField('yy_del', '1');
		}
		else {
			$data->where('yy_ID=' . $ly['ID'] . '')->setField('yy_del', '0');
		}

		echo '<script language=\'javascript\'>parent.layer.msg(\'修改完成\');location.href=\'' . u('SystemSite/system_yy') . '\';</script>';
	}

	public function system_yy_del()
	{
		$data = m('Yiyuan');
		$ly = i('get.');
		$shuju = m('huanze');

		if ($shuju->where('yy_ID=' . $ly['ID'] . '')->select() != '') {
			js_alert('', '有数据属于此门店!只可停用');
			exit();
		}

		$userYY = m('useradmin');
		$data->delete($ly['ID']);
		$delyyID = $userYY->query('select * from oa_useradmin where find_in_set(' . $ly['ID'] . ',yy_ID)');

		for ($i = 0; $i <= count($delyyID) - 1; $i++) {
			$newyyID = delarrayys($delyyID[$i]['yy_ID'], $ly['ID']);
			mysql_query('update oa_useradmin set yy_ID=\'' . $newyyID . '\' where user_ID=' . $delyyID[$i]['user_ID'] . '');
		}

		echo '<script language=\'javascript\'>parent.layer.msg(\'删除完成\');location.href=\'' . u('SystemSite/system_yy') . '\';</script>';
	}

	public function yy_update()
	{
		$data = d('yiyuan');

		if ($data->create()) {
			if ($data->save()) {
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改成功\');location.href=\'' . u('SystemSite/system_yy') . '\';</script>';
			}
			else {
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改失败\');location.href=\'' . u('SystemSite/system_yy') . '\';</script>';
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_xx()
	{
		$m = m('xinxilaiyuan');
		$total = $m->count();
		$pagesize = 15;
		$page = new \Component\Page($total, $pagesize);
		$sql = 'select * from oa_xinxilaiyuan order by xx_sort desc ' . $page->limit;
		$info = $m->query($sql);
		$list = $page->fpage();
		$this->assign('list', $info);
		$this->assign('page', $list);
		$this->display();
	}

	public function xx_update()
	{
		$data = d('xinxilaiyuan');

		if ($data->create()) {
			if ($data->save()) {
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改成功\');location.href=\'' . u('SystemSite/system_xx') . '\';</script>';
			}
			else {
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改失败\');location.href=\'' . u('SystemSite/system_xx') . '\';</script>';
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_xx_stop()
	{
		$data = m('xinxilaiyuan');
		$ly = i('get.');
		$xx_del = $data->where('ID=' . $ly['ID'] . '')->getField('xx_del');

		if ($xx_del == 0) {
			$data->where('ID=' . $ly['ID'] . '')->setField('xx_del', '1');
		}
		else {
			$data->where('ID=' . $ly['ID'] . '')->setField('xx_del', '0');
		}

		echo '<script language=\'javascript\'>parent.layer.msg(\'修改完成\');location.href=\'' . u('SystemSite/system_xx') . '\';</script>';
	}

	public function system_xx_del()
	{
		$data = m('xinxilaiyuan');
		$ly = i('get.');
		$data->delete($ly['ID']);
		echo '<script language=\'javascript\'>parent.layer.msg(\'删除完成\');location.href=\'' . u('SystemSite/system_xx') . '\';</script>';
	}

	public function xx_insert()
	{
		$data = d('xinxilaiyuan');

		if ($data->create()) {
			$data->add();
			echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('SystemSite/system_xx') . '\';</script>';
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_bz()
	{
		$this->StartColumnID = '0';
		$this->display('system_bz');
	}

	public function bz_insert()
	{
		$data = d('Bingzhong');

		if ($data->create()) {
			$column_name1 = str_replace(',', '，', trim($data->bz_name));
			$column_name = explode('，', $column_name1);
			$P_id1 = explode(',', $data->P_id);
			$P_id = $P_id1[0];
			$column_level = $P_id1[1];
			$bz_lujin = bzcolumn_uppid($P_id);

			for ($i = 0; $i <= count($column_name) - 1; $i++) {
				$data->bz_name = trim($column_name[$i]);

				if (!empty($data->bz_name)) {
					$data->bz_sort = '1';
					$data->P_id = $P_id;
					$data->bz_level = $column_level + 1;
					$data->bz_lujin = $bz_lujin;
					$lastID = $data->add();
					bingzhongson($lastID, $P_id);
				}
			}

			echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('SystemSite/system_bz') . '\';</script>';
		}
		else {
			$this->error($data->getError());
		}
	}

	public function bz_submit()
	{
		if (i('post.submit_del') != '') {
			$data = i('post.');
			$aaa = $data[quanxuan];

			if ($aaa == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			rsort($aaa);
			$deltiaoshu = 0;
			$rows = m('bingzhong');
			$bingzhong = m('huanze');
			$clength = count($aaa);

			for ($x = 0; $x < $clength; $x++) {
				$list1 = $rows->field('ID')->where('P_id=' . $aaa[$x] . '')->select();
				$list = $bingzhong->field('zx_ID')->where('bz_ID=' . $aaa[$x] . '')->select();
				if (($list1 == '') && ($list == '')) {
					$rows->delete($aaa[$x]);
					$delID .= $aaa[$x] . ',';
					$deltiaoshu++;
					$bz_son = $rows->where('find_in_set(' . $aaa[$x] . ', bz_son)')->select();

					for ($num = 0; $num <= count($bz_son) - 1; $num++) {
						$NewBz_son = delarrayys($bz_son[$num]['bz_son'], $aaa[$x]);
						$rows->query('update oa_bingzhong set bz_son=\'' . $NewBz_son . '\' where ID=' . $bz_son[$num]['ID'] . '');
					}
				}
			}

			bz_column_lujin();
			bz_sonupdate();
			echo '<script language=\'javascript\'>' . "\r\n" . '			  parent.layer.msg(\'共计选中：' . count($aaa) . ', 成功删除' . $deltiaoshu . '条,ID分别为:' . $delID . ',如果为0条，可能存在下级或者存在咨询属于此病种\');' . "\r\n" . '			  location.href=\'' . u('SystemSite/system_bz') . '\';' . "\r\n" . '			  </script>';
		}

		if (i('post.submit_stop') != '') {
			$data = i('post.');
			$aaa = $data[quanxuan];

			if ($aaa == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			rsort($aaa);
			$rows = m('bingzhong');
			$clength = count($aaa);

			for ($x = 0; $x < $clength; $x++) {
				$rows->where('id=' . $aaa[$x] . '')->setField('bz_del', '1');
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_bz') . '\';' . "\r\n" . '						  </script>';
		}

		if (i('post.submit_go') != '') {
			$data = i('post.');
			$aaa = $data[quanxuan];

			if ($aaa == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			rsort($aaa);
			$rows = m('bingzhong');
			$clength = count($aaa);

			for ($x = 0; $x < $clength; $x++) {
				$rows->where('id=' . $aaa[$x] . '')->setField('bz_del', '0');
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_bz') . '\';' . "\r\n" . '						  </script>';
		}

		if (i('post.submit_save') != '') {
			$aaa = i('post.');
			$rows = m('bingzhong');
			$clength = count($aaa['bz_id']);

			for ($x = 0; $x < $clength; $x++) {
				$array = array('bz_name' => '' . $aaa['bz_name'][$x] . '', 'bz_sort' => '' . $aaa['bz_sort'][$x] . '');
				$rows->where('id=' . $aaa['bz_id'][$x] . '')->setField($array);
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_bz') . '\';' . "\r\n" . '						  </script>';
		}

		if (i('post.submit_yidong') != '') {
			$aaa = i('post.');

			if ($aaa['quanxuan'] == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			$rows = m('bingzhong');
			$P_id1 = explode(',', $aaa['yidong']);
			$P_id = $P_id1[0];
			$clength = count($aaa['quanxuan']);
			$tiaoshu1 = $rows->where('ID=' . $P_id . '')->getField('bz_lujin');
			$tiaoshu = explode(',', $tiaoshu1);

			for ($x = 0; $x < $clength; $x++) {
				if (in_array($aaa['quanxuan'][$x], $tiaoshu) || ($aaa['quanxuan'][$x] == $P_id)) {
					echo '<script language=\'javascript\'>parent.layer.msg(\'操作被系统禁止，请合理转移项目\');history.back();</script>';
					exit();
				}
			}

			$column_level = $P_id1[1] + 1;
			$array = array('bz_level' => '' . $column_level . '', 'P_id' => '' . $P_id . '');

			for ($x = 0; $x < $clength; $x++) {
				$rows->where('id=' . $aaa['quanxuan'][$x] . '')->setField($array);
				bz_column_levelxg($aaa['quanxuan'][$x]);
			}

			bz_column_lujin();
			bz_sonupdate();
			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_bz') . '\';' . "\r\n" . '						  </script>';
		}
	}

	public function system_zxfs()
	{
		$this->StartColumnID = '0';
		$this->display('');
	}

	public function zxfs_insert()
	{
		$data = d('zixunfangshi');

		if ($data->create()) {
			$column_name1 = str_replace(',', '，', trim($data->zxfs_name));
			$column_name = explode('，', $column_name1);
			$P_id1 = explode(',', $data->P_id);
			$P_id = $P_id1[0];
			$column_level = $P_id1[1];
			$zxfs_lujin = zxfscolumn_uppid($P_id);

			for ($i = 0; $i <= count($column_name) - 1; $i++) {
				$data->zxfs_name = trim($column_name[$i]);

				if (!empty($data->zxfs_name)) {
					$data->zxfs_sort = '1';
					$data->P_id = $P_id;
					$data->zxfs_level = $column_level + 1;
					$data->zxfs_lujin = $zxfs_lujin;
					$data->add();
					zxfs_sonupdate();
				}
			}

			echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('SystemSite/system_zxfs') . '\';</script>';
		}
		else {
			$this->error($data->getError());
		}
	}

	public function zxfs_submit()
	{
		if (i('post.submit_del') != '') {
			$data = i('post.');
			$aaa = $data[quanxuan];

			if ($aaa == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			rsort($aaa);
			$deltiaoshu = 0;
			$rows = m('zixunfangshi');
			$zixunfangshi = m('huanze');
			$clength = count($aaa);

			for ($x = 0; $x < $clength; $x++) {
				$list1 = $rows->field('ID')->where('P_id=' . $aaa[$x] . '')->select();
				$list = $zixunfangshi->field('zx_ID')->where('zxfs_ID=' . $aaa[$x] . '')->select();
				if (($list1 == '') && ($list == '')) {
					$rows->delete($aaa[$x]);
					$delID .= $aaa[$x] . ',';
					$deltiaoshu++;
					$zxfs_son = $rows->where('find_in_set(' . $aaa[$x] . ', zxfs_son)')->select();

					for ($num = 0; $num <= count($zxfs_son) - 1; $num++) {
						$Newzxfs_son = delarrayys($zxfs_son[$num]['zxfs_son'], $aaa[$x]);
						$rows->query('update oa_zixunfangshi set zxfs_son=\'' . $Newzxfs_son . '\' where ID=' . $zxfs_son[$num]['ID'] . '');
					}
				}
			}

			zxfs_column_lujin();
			zxfs_sonupdate();
			echo '<script language=\'javascript\'>' . "\r\n" . '			  parent.layer.msg(\'共计选中：' . count($aaa) . ',成功删除' . $deltiaoshu . '条,ID分别为:' . $delID . '\');' . "\r\n" . '			  location.href=\'' . u('SystemSite/system_zxfs') . '\';' . "\r\n" . '			  </script>';
		}

		if (i('post.submit_stop') != '') {
			$data = i('post.');
			$aaa = $data[quanxuan];

			if ($aaa == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			rsort($aaa);
			$rows = m('zixunfangshi');
			$clength = count($aaa);

			for ($x = 0; $x < $clength; $x++) {
				$rows->where('id=' . $aaa[$x] . '')->setField('zxfs_del', '1');
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_zxfs') . '\';' . "\r\n" . '						  </script>';
		}

		if (i('post.submit_go') != '') {
			$data = i('post.');
			$aaa = $data[quanxuan];

			if ($aaa == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			rsort($aaa);
			$rows = m('zixunfangshi');
			$clength = count($aaa);

			for ($x = 0; $x < $clength; $x++) {
				$rows->where('id=' . $aaa[$x] . '')->setField('zxfs_del', '0');
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_zxfs') . '\';' . "\r\n" . '						  </script>';
		}

		if (i('post.submit_save') != '') {
			$aaa = i('post.');
			$rows = m('zixunfangshi');
			$clength = count($aaa['zxfs_id']);

			for ($x = 0; $x < $clength; $x++) {
				$array = array('zxfs_name' => '' . $aaa['zxfs_name'][$x] . '', 'zxfs_sort' => '' . $aaa['zxfs_sort'][$x] . '');
				$rows->where('id=' . $aaa['zxfs_id'][$x] . '')->setField($array);
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_zxfs') . '\';' . "\r\n" . '						  </script>';
		}

		if (i('post.submit_yidong') != '') {
			$aaa = i('post.');

			if ($aaa['quanxuan'] == '') {
				echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
				exit();
			}

			$rows = m('zixunfangshi');
			$P_id1 = explode(',', $aaa['yidong']);
			$P_id = $P_id1[0];
			$clength = count($aaa['quanxuan']);
			$tiaoshu1 = $rows->where('ID=' . $P_id . '')->getField('zxfs_lujin');
			$tiaoshu = explode(',', $tiaoshu1);

			for ($x = 0; $x < $clength; $x++) {
				if (in_array($aaa['quanxuan'][$x], $tiaoshu) || ($aaa['quanxuan'][$x] == $P_id)) {
					echo '<script language=\'javascript\'>parent.layer.msg(\'操作被系统禁止，请合理转移项目\');history.back();</script>';
					exit();
				}
			}

			$column_level = $P_id1[1] + 1;
			$array = array('zxfs_level' => '' . $column_level . '', 'P_id' => '' . $P_id . '');

			for ($x = 0; $x < $clength; $x++) {
				$rows->where('id=' . $aaa['quanxuan'][$x] . '')->setField($array);
				zxfs_column_levelxg($aaa['quanxuan'][$x]);
				zxfs_column_lujin();
				zxfs_sonupdate();
			}

			echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_zxfs') . '\';' . "\r\n" . '						  </script>';
		}
	}

	public function system_yybz()
	{
		$User = m('yiyuan');
		$yiyuanName = $User->where('yy_ID=' . i('get.yyid') . '')->getField('yy_name');
		$this->assign('yiyuanname', $yiyuanName);
		$this->assign('yyid', i('get.yyid'));
		$this->StartColumnID = '0';
		$this->display('');
	}

	public function yybz_insert()
	{
		$data = i('post.');
		$aaa = $data[quanxuan];

		if ($aaa == '') {
			echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
			exit();
		}

		$rows = m('bingzhong');
		$clength = count($aaa);
		$sql = 'SELECT * FROM oa_bingzhong WHERE find_in_set(' . $data['yyid'] . ', yy_ID)';
		$syid1 = mysql_query($sql);

		while ($syid = mysql_fetch_array($syid1)) {
			$newyy_ID = delarrayys($syid['yy_ID'], $data['yyid']);
			$rows->where('ID=' . $syid['ID'] . '')->setField('yy_ID', $newyy_ID);
		}

		for ($x = 0; $x < $clength; $x++) {
			$sql = ' UPDATE oa_bingzhong SET yy_ID=CONCAT(yy_ID,\'' . $data['yyid'] . ',\') WHERE id =' . $aaa[$x] . ' ';
			mysql_query($sql);
		}

		echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yybz?yyid=' . $data['yyid'] . '') . '\';' . "\r\n" . '						  </script>';
	}

	public function system_yyzxfs()
	{
		$User = m('yiyuan');
		$yiyuanName = $User->where('yy_ID=' . i('get.yyid') . '')->getField('yy_name');
		$this->assign('yiyuanname', $yiyuanName);
		$this->assign('yyid', i('get.yyid'));
		$this->StartColumnID = '0';
		$this->display('');
	}

	public function yyzxfs_insert()
	{
		$data = i('post.');
		$aaa = $data[quanxuan];

		if ($aaa == '') {
			echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
			exit();
		}

		$rows = m('zixunfangshi');
		$clength = count($aaa);
		$sql = 'SELECT * FROM oa_zixunfangshi WHERE find_in_set(' . $data['yyid'] . ', yy_ID)';
		$syid1 = mysql_query($sql);

		while ($syid = mysql_fetch_array($syid1)) {
			$newyy_ID = delarrayys($syid['yy_ID'], $data['yyid']);
			$rows->where('ID=' . $syid['ID'] . '')->setField('yy_ID', $newyy_ID);
		}

		for ($x = 0; $x < $clength; $x++) {
			$sql = ' UPDATE oa_zixunfangshi SET yy_ID=CONCAT(yy_ID,\'' . $data['yyid'] . ',\') WHERE id =' . $aaa[$x] . ' ';
			mysql_query($sql);
		}

		echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yyzxfs?yyid=' . $data['yyid'] . '') . '\';' . "\r\n" . '						  </script>';
	}

	public function system_yydoctor()
	{
		$User = m('yiyuan');
		$yiyuanName = $User->where('yy_ID=' . i('get.yyid') . '')->getField('yy_name');
		$this->assign('yyid', i('get.yyid'));
		$this->assign('yiyuanName', $yiyuanName);
		$rows = m('doctor');
		$yisheng = $rows->where('yy_ID=' . i('get.yyid') . '')->order('doctor_sort desc')->select();
		$this->assign('list', $yisheng);
		$this->display('');
	}

	public function yydoctor_insert()
	{
		$data = d('doctor');

		if ($data->create()) {
			$yyid = $data->yy_ID;

			if ($data->add()) {
				echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'添加成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yydoctor?yyid=' . $yyid . '') . '\';' . "\r\n" . '						  </script>';
			}
			else {
				echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'添加失败\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yydoctor?yyid=' . $yyid . '') . '\';' . "\r\n" . '						  </script>';
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_doctor_stop()
	{
		$data = m('Doctor');
		$ly = i('get.');
		$doctor_del = $data->where('doctor_ID=' . $ly['ID'] . '')->getField('doctor_del');

		if ($doctor_del == 0) {
			$data->where('doctor_ID=' . $ly['ID'] . '')->setField('doctor_del', '1');
		}
		else {
			$data->where('doctor_ID=' . $ly['ID'] . '')->setField('doctor_del', '0');
		}

		echo '<script language=\'javascript\'>parent.layer.msg(\'修改完成\');location.href=\'' . u('SystemSite/system_yydoctor?yyid=' . $ly['yyid'] . '') . '\';</script>';
	}

	public function system_yydoctor_del()
	{
		$data = m('Doctor');
		$ly = i('get.');
		$data->delete($ly['ID']);
		echo '<script language=\'javascript\'>' . "\r\n" . '		  parent.layer.msg(\'删除完成\');' . "\r\n" . '		  location.href=\'' . u('SystemSite/system_yydoctor?yyid=' . $ly['yyid'] . '') . '\';' . "\r\n" . '		  </script>';
	}

	public function system_yydoctor_update()
	{
		$data = d('doctor');

		if ($data->create()) {
			$yyid = $data->yy_ID;

			if ($data->save()) {
				echo '<script language=\'javascript\'>' . "\r\n" . '					  parent.layer.msg(\'修改完成\');' . "\r\n" . '					  location.href=\'' . u('SystemSite/system_yydoctor?yyid=' . $yyid . '') . '\';' . "\r\n" . '					  </script>';
			}
			else {
				js_alert('', '信息一致 无需修改');
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_yyxinxi()
	{
		$User = m('yiyuan');
		$Get_yyID = i('get.yyid');
		$yiyuanName = $User->where('yy_ID=' . $Get_yyID . '')->getField('yy_name');
		$this->assign('yyid', $Get_yyID);
		$this->assign('yiyuanname', $yiyuanName);
		$m = m('xinxilaiyuan');
		$sql = 'select * from oa_xinxilaiyuan where xx_del=0';
		$info = $m->query($sql);

		for ($i = 0; $i <= count($info) - 1; $i++) {
			$yyarray = explode(',', $info[$i]['yy_ID']);

			if (in_array($Get_yyID, $yyarray)) {
				$info[$i]['yy_ID'] = 'checked';
			}
			else {
				$info[$i]['yy_ID'] = '';
			}
		}

		$this->assign('list', $info);
		$this->display();
	}

	public function system_yyxinxi_update()
	{
		$data = i('post.');
		$aaa = $data[quanxuan];

		if ($aaa == '') {
			echo '<script language=\'javascript\'>parent.layer.msg(\'请选择条数\');history.back();</script>';
			exit();
		}

		$rows = m('xinxilaiyuan');
		$clength = count($aaa);
		$sql = 'SELECT * FROM oa_xinxilaiyuan WHERE find_in_set(' . $data['yyid'] . ', yy_ID)';
		$syid1 = mysql_query($sql);

		while ($syid = mysql_fetch_array($syid1)) {
			$newyy_ID = delarrayys($syid['yy_ID'], $data['yyid']);
			$rows->where('ID=' . $syid['ID'] . '')->setField('yy_ID', $newyy_ID);
		}

		for ($x = 0; $x < $clength; $x++) {
			$sql = ' UPDATE oa_xinxilaiyuan SET yy_ID = CONCAT(yy_ID,\'' . $data['yyid'] . ',\') WHERE id =' . $aaa[$x] . ' ';
			mysql_query($sql);
		}

		echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'修改成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yyxinxi?yyid=' . $data['yyid'] . '') . '\';' . "\r\n" . '						  </script>';
	}

	public function system_yywangzhan()
	{
		$User = m('yiyuan');
		$yiyuanName = $User->where('yy_ID=' . i('get.yyid') . '')->getField('yy_name');
		$this->assign('yyid', i('get.yyid'));
		$this->assign('yiyuanName', $yiyuanName);
		$rows = m('wangzhan');
		$wangzhan = $rows->where('yy_ID=' . i('get.yyid') . '')->order('wangzhan_sort desc')->select();
		$this->assign('list', $wangzhan);
		$binz = m('bingzhong');
		$binzsj = $binz->where('p_id=0 and find_in_set(' . i('get.yyid') . ',yy_ID)')->getField('bz_name', true);

		for ($i = 0; $i < count($binzsj); $i++) {
			$binzsjj .= '<option value="' . $binzsj[$i] . '">' . $binzsj[$i] . '</option>';
		}

		$this->assign('binzsjj', htmlspecialchars($binzsjj));
		$this->assign('HTTP_HOST', $_SERVER['HTTP_HOST']);
		$this->display();
	}

	public function yywangzhan_insert()
	{
		$data = d('wangzhan');

		if ($data->create()) {
			$yyid = $data->yy_ID;

			if ($data->add()) {
				echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'添加成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yywangzhan?yyid=' . $yyid . '') . '\';' . "\r\n" . '						  </script>';
			}
			else {
				echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'添加失败\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_yywangzhan?yyid=' . $yyid . '') . '\';' . "\r\n" . '						  </script>';
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_wangzhan_stop()
	{
		$data = m('Wangzhan');
		$ly = i('get.');
		$wangzhan_del = $data->where('wangzhan_ID=' . $ly['ID'] . '')->getField('wangzhan_del');

		if ($wangzhan_del == 0) {
			$data->where('wangzhan_ID=' . $ly['ID'] . '')->setField('wangzhan_del', '1');
		}
		else {
			$data->where('wangzhan_ID=' . $ly['ID'] . '')->setField('wangzhan_del', '0');
		}

		echo '<script language=\'javascript\'>parent.layer.msg(\'修改完成\');location.href=\'' . u('SystemSite/system_yywangzhan?yyid=' . $ly['yyid'] . '') . '\';</script>';
	}

	public function system_wangzhan_del()
	{
		$data = m('wangzhan');
		$ly = i('get.');
		$data->delete($ly['ID']);
		echo '<script language=\'javascript\'>' . "\r\n" . '		  parent.layer.msg(\'删除完成\');' . "\r\n" . '		  location.href=\'' . u('SystemSite/system_yywangzhan?yyid=' . $ly['yyid'] . '') . '\';' . "\r\n" . '		  </script>';
	}

	public function system_wangzhan_update()
	{
		$data = d('wangzhan');

		if ($data->create()) {
			$yyid = $data->yy_ID;

			if ($data->save()) {
				echo '<script language=\'javascript\'>' . "\r\n" . '					  parent.layer.msg(\'修改完成\');' . "\r\n" . '					  location.href=\'' . u('SystemSite/system_yywangzhan?yyid=' . $yyid . '') . '\';' . "\r\n" . '					  </script>';
			}
			else {
				js_alert('', '信息一致 无需修改');
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_message()
	{
		$data = d('duanxin');
		$rows = $data->select();
		$this->assign('list', $rows);
		$this->display('');
	}

	public function message_insert()
	{
		$data = d('duanxin');

		if ($data->create()) {
			$data->add();
			echo '<script language=\'javascript\'>parent.layer.msg(\'添加完成\');location.href=\'' . u('SystemSite/system_message') . '\';</script>';
		}
		else {
			$this->error($data->getError());
		}
	}

	public function message_del()
	{
		$data = m('duanxin');
		$ly = i('get.');
		$data->delete($ly['duanxin_ID']);
		echo '<script language=\'javascript\'>parent.layer.msg(\'删除完成\');location.href=\'' . u('SystemSite/system_message') . '\';</script>';
	}

	public function message_update()
	{
		$data = d('duanxin');

		if ($data->create()) {
			if ($data->save()) {
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改成功\');location.href=\'' . u('SystemSite/system_message') . '\';</script>';
			}
			else {
				echo '<script language=\'javascript\'>parent.layer.msg(\'修改失败\');location.href=\'' . u('SystemSite/system_message') . '\';</script>';
			}
		}
	}

	public function system_duanxin()
	{
		$yyid = i('get.yyid');
		$yyName1 = m('yiyuan');
		$yyName = $yyName1->where('yy_ID=' . $yyid . '')->getField('yy_name');
		$this->assign('yyname', $yyName);
		$duanxin1 = m('duanxinset');
		$yyduanxin = $duanxin1->where('yy_ID=' . $yyid . '')->find();
		$canshu = i('post.');

		if ($canshu['submit1'] != '') {
			$yyid = i('post.yy_ID');
			$duanxin = m('duanxinset');
			$yyduanxin = $duanxin->where('yy_ID=' . $yyid . '')->count();
			$aaa = $duanxin->create();

			if ($yyduanxin == 0) {
				$duanxin->add();
				js_alert('SystemSite/system_duanxin?yyid=' . $yyid . '', '修改完成');
			}
			else {
				$duanxin->where('yy_ID=' . $yyid . '')->save();
				js_alert('SystemSite/system_duanxin?yyid=' . $yyid . '', '修改完成');
			}
		}

		$this->assign('zhanghao', $yyduanxin['dx_username']);
		$this->assign('mima', $yyduanxin['dx_password']);
		$this->assign('foot', $yyduanxin['dx_footContent']);
		$this->assign('yyid', $yyid);
		$this->display();
	}

	public function duanxinset_update()
	{
		$yyid = i('post.yy_ID');
		$duanxin = m('duanxinset');
		$yyduanxin = $duanxin->where('yy_ID=' . $yyid . '')->count();
		$aaa = $duanxin->create();

		if ($yyduanxin == 0) {
			$duanxin->add();
			js_alert('SystemSite/system_duanxin?yyid=' . $yyid . '', '修改完成');
		}
		else {
			$duanxin->where('yy_ID=' . $yyid . '')->save();
			js_alert('SystemSite/system_duanxin?yyid=' . $yyid . '', '修改完成');
		}
	}

	public function system_baidu()
	{
		$User = m('yiyuan');
		$yiyuanName = $User->where('yy_ID=' . i('get.yyid') . '')->getField('yy_name');
		$this->assign('yyid', i('get.yyid'));
		$this->assign('yiyuanName', $yiyuanName);
		$rows = m('baiduzhanghu');
		$baidu = $rows->where('yy_ID=' . i('get.yyid') . '')->select();
		$this->assign('list', $baidu);
		$this->display('');
	}

	public function baidu_insert()
	{
		$data = d('baiduzhanghu');

		if ($data->create()) {
			$data->zhanghudel = 0;
			$mima1 = $data->zhanghumima;
			$mima = base64_encode($mima1);
			$data->zhanghumima = $mima;
			$yyid = $data->yy_ID;

			if ($data->add()) {
				echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'添加成功\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_baidu?yyid=' . $yyid . '') . '\';' . "\r\n" . '						  </script>';
			}
			else {
				echo '<script language=\'javascript\'>' . "\r\n" . '						  parent.layer.msg(\'添加失败\');' . "\r\n" . '						  location.href=\'' . u('SystemSite/system_baidu?yyid=' . $yyid . '') . '\';' . "\r\n" . '						  </script>';
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_baidu_stop()
	{
		$data = m('baiduzhanghu');
		$ly = i('get.');
		$baidu_del = $data->where('zhanghu_ID=' . $ly['ID'] . '')->getField('zhanghudel');

		if ($baidu_del == 0) {
			$data->where('zhanghu_ID=' . $ly['ID'] . '')->setField('zhanghudel', '1');
		}
		else {
			$data->where('zhanghu_ID=' . $ly['ID'] . '')->setField('zhanghudel', '0');
		}

		echo '<script language=\'javascript\'>parent.layer.msg(\'修改完成\');location.href=\'' . u('SystemSite/system_baidu?yyid=' . $ly['yyid'] . '') . '\';</script>';
	}

	public function system_baidu_del()
	{
		$data = m('baiduzhanghu');
		$ly = i('get.');
		$data->delete($ly['ID']);
		echo '<script language=\'javascript\'>' . "\r\n" . '		  parent.layer.msg(\'删除完成\');' . "\r\n" . '		  location.href=\'' . u('SystemSite/system_baidu?yyid=' . $ly['yyid'] . '') . '\';' . "\r\n" . '		  </script>';
	}

	public function system_baidu_update()
	{
		$data = d('baiduzhanghu');

		if ($data->create()) {
			$yyid = $data->yy_ID;
			$zhanghu_ID = $data->zhanghu_ID;
			$mima1 = $data->zhanghumima;

			if ($mima1 == '******') {
				$mima = $data->where('zhanghu_ID=' . $zhanghu_ID . '')->getField('zhanghumima');
			}
			else {
				$mima = base64_encode($mima1);
			}

			$data->zhanghumima = $mima;

			if ($data->save()) {
				echo '<script language=\'javascript\'>' . "\r\n" . '					  parent.layer.msg(\'修改完成\');' . "\r\n" . '					  location.href=\'' . u('SystemSite/system_baidu?yyid=' . $yyid . '') . '\';' . "\r\n" . '					  </script>';
			}
			else {
				js_alert('', '信息一致 无需修改');
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function system_swt()
	{
		$yyid = i('request.yyid');
		$this->assign('yyid', $yyid);
		$swtinsert = m('swtdaoru');
		$yyname = $swtinsert->query('select yy_name from oa_yiyuan where yy_ID=' . $yyid . ' limit 1');
		$this->assign('yyname', $yyname[0]['yy_name']);

		if (i('post.submit') != '') {
			$file = $_FILES['swt'];

			if (is_uploaded_file($file['tmp_name'])) {
				$upload = new \Think\Upload();
				$upload->maxSize = 3145728000;
				$upload->exts = array('csv', 'xls', 'xlsx');
				$upload->rootPath = './OA/Uploads_lf/';
				$upload->savePath = '';
				$info = $upload->upload();

				if (!$info) {
					$this->error($upload->getError());
				}
				else {
					$swtexcle = $info['swt']['savepath'] . $info['swt']['savename'];
					$file_path = pathinfo('./OA/Uploads_lf/' . $swtexcle . '');
					$houzui = $file_path['extension'];

					if ($houzui == 'xlsx') {
						$weizui = 'Excel2007';
					}
					else {
						$weizui = 'Excel5';
					}

					$filename = './OA/Uploads_lf/' . $swtexcle . '';

					if (!file_exists($filename)) {
						exit('not found ' . $filename . ' .' . "\n" . '');
					}

					vendor('PHPExcel.Classes.PHPExcel.IOFactory');
					$reader = PHPExcel_IOFactory::createReader($weizui);
					$PHPExcel = $reader->load($filename);
					$sheet = $PHPExcel->getSheet(0);
					$highestRow = $sheet->getHighestRow();
					$highestColumm = $sheet->getHighestColumn();
					$highestColumm++;

					for ($row = 1; $row <= $highestRow; $row++) {
						$column = 'A';

						for ($i = 1; $i <= 52; $i++) {
							$dataset[$row][$column] = $sheet->getCell($column . $row)->getValue();
							$column++;

							if ($column == $highestColumm) {
								break;
							}
						}
					}

					$maxrows = '2000';

					if ($maxrows < $highestRow) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '上传了' . $highestRow . ',超过系统允许的最大行数' . $maxrows . ',请分批次上传');
						exit();
					}

					if ($dataset[1]['A'] != '编号') {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '商务通数据格式不对!');
						exit();
					}

					$insertsql = array();

					if (!array_search('编号', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '编号 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('编号', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['bianhao'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('开始访问时间', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '开始访问时间 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('开始访问时间', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['chucifangwen'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('开始对话时间', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '开始对话时间 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('开始对话时间', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['zx_time'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('对话类型', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '对话类型 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('对话类型', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['duihualeixing'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('开始方式', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '开始方式 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('开始方式', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['kaishifangshi'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('客人类别', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '客人类别 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('客人类别', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['kerenleibie'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('初次访问网址', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '初次访问网址 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('初次访问网址', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['chucifangwenURL'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('IP', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, 'IP 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('IP', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['IPdizhi'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('关键词', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '关键词 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('关键词', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['guanjianci'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('初始接待客服', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '初始接待客服 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('初始接待客服', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['chucikefu'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('访问来源', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '访问来源 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('访问来源', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['fangwenlaiyuan'] = $dataset[$i][$yhziduan];
						}
					}

					if (!array_search('永久身份', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '永久身份 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('永久身份', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['yongjiushenfen'] = ltrim($dataset[$i][$yhziduan], '\'');
						}
					}

					if (!array_search('对话来源', $dataset[1])) {
						js_alert('SystemSite/system_swt?yyid=' . $yyid, '对话来源 没导入，请重新整理格式后再上传');
						exit();
					}
					else {
						$yhziduan = array_search('对话来源', $dataset[1]);

						for ($i = 2; $i <= count($dataset); $i++) {
							$insertsql[$i]['duihualaiyuan'] = $dataset[$i][$yhziduan];
							$insertsql[$i]['yy_ID'] = $yyid;
						}
					}
				}
			}

			unlink($filename);
			unset($dataset);
			$isno = 0;
			$isok = 0;

			foreach ($insertsql as $v ) {
				$oldcount = $swtinsert->where('zx_time=\'' . $v['zx_time'] . '\' and yongjiushenfen =\'' . $v['yongjiushenfen'] . '\'')->count();

				if (1 <= $oldcount) {
					$isno++;
				}
				else {
					$swtinsert->add($v);
					$isok++;
				}
			}

			echo '<script language=\'javascript\'>alert(\'上传' . count($insertsql) . '条数据，成功导入' . $isok . '条，重复数据' . $isno . '条\');history.back();</script>';
		}

		$sql = 'select max(zx_time) from oa_swtdaoru where yy_ID=' . $yyid . '';
		$lastdaoru = $swtinsert->query($sql);
		$this->assign('lastdaoru', $lastdaoru[0]['max(zx_time)']);
		$this->display();
	}

	public function system_yuemubiao()
	{
		$canshu = i('post.');
		$yyName1 = m('yiyuan');

		if ($canshu['submit1'] != '') {
			$yyid = $canshu['yyid'];

			if ($canshu['yy_daozhenmubiao'] != '') {
				$yyName1->query('update oa_yiyuan set yy_daozhenmubiao = ' . $canshu['yy_daozhenmubiao'] . ' where yy_ID = ' . trim($canshu['yyid']) . '');
				js_alert('SystemSite/system_yuemubiao?yyid=' . $canshu['yyid'] . '', '修改完成');
			}
			else {
				js_alert('SystemSite/system_yuemubiao?yyid=' . $canshu['yyid'] . '', '修改失败');
			}
		}
		else {
			$yyid = i('get.yyid');
		}

		$yyName = $yyName1->where('yy_ID=' . $yyid . '')->getField('yy_name');
		$this->assign('yyname', $yyName);
		$yy_daozhenmubiao = $yyName1->where('yy_ID=' . $yyid . '')->getField('yy_daozhenmubiao');
		$this->assign('yy_daozhenmubiao', $yy_daozhenmubiao);
		$this->assign('yyid', $yyid);
		$this->display();
	}
}


?>
