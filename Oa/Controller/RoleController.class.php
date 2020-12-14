<?php

namespace OA\Controller;


class RoleController extends \Component\AdminController
{
	public function system_role()
	{
		$data = m('role');
		$rows = $data->order('role_xitongmoren asc')->select();
		$this->assign('list', $rows);
		$this->display('');
	}

	public function role_insert()
	{
		$data = d('role');

		if ($aaa = $data->create()) {
			if ($data->add()) {
				js_alert('Role/system_role', '添加成功');
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function role_update()
	{
		$data = d('role');

		if ($data->create()) {
			if ($data->save()) {
				js_alert('Role/system_role', '修改成功');
			}
			else {
				js_alert('', '修改失败');
			}
		}
		else {
			$this->error($data->getError());
		}
	}

	public function role_start()
	{
		$data = m('role');
		$role_ID = i('get.');

		if ($data->where('role_ID=' . $role_ID['ID'] . '')->setField('role_del', '0')) {
			js_alert('Role/system_role', '修改成功');
		}
		else {
			js_alert('', '修改失败');
		}
	}

	public function role_stop()
	{
		$data = m('role');
		$role_ID = i('get.');

		if ($data->where('role_ID=' . $role_ID['ID'] . '')->setField('role_del', '1')) {
			js_alert('Role/system_role', '修改成功');
		}
		else {
			js_alert('', '修改失败');
		}
	}

	public function role_del()
	{
		$data = m('role');
		$ly = i('get.');
		if (($ly['ID'] == 1) || ($ly['ID'] == 2) || ($ly['ID'] == 3) || ($ly['ID'] == 4) || ($ly['ID'] == 5) || ($ly['ID'] == 6)) {
			js_alert('', '系统默认组无法删除');
			exit();
		}

		$data->delete($ly['ID']);
		js_alert('Role/system_role', '成功删除');
	}

	public function system_qj()
	{
		$data = m('quanjushezhi');
		$role = i('get.');
		$rows = $data->select();

		for ($i = 0; $i <= count($rows) - 1; $i++) {
			$baohan = $data->where('qj_ID = ' . $rows[$i]['qj_ID'] . ' and find_in_set(' . $role['yyid'] . ', qj_yyid)')->select();

			if ($baohan[0]['qj_yyid'] == '') {
				$rows[$i]['qj_del'] = '1';
			}
			else {
				$rows[$i]['qj_del'] = '0';
			}
		}

		$User = m('yiyuan');
		$yiyuanName = $User->where('yy_ID=' . i('get.yyid') . '')->getField('yy_name');
		$this->assign('yiyuanName', $yiyuanName);
		$this->assign('list', $rows);
		$this->display('');
	}

	public function system_qj_update()
	{
		$data = m('quanjushezhi');
		$role = i('get.');
		$qj_yyid1 = $data->where('qj_ID=' . $role['ID'] . '')->select();
		$qj_yyid = $qj_yyid1[0]['qj_yyid'];
		$panduancf = $data->where('qj_ID=' . $role['ID'] . ' and find_in_set(' . $role['yyid'] . ', qj_yyid) ')->select();
		if (($panduancf != '') && ($role['zhuangtai'] == '0')) {
			js_alert('', '与当前状态一样,无需修改');
			exit();
		}

		if ($role['zhuangtai'] == '0') {
			$qj_yyid .= ',' . $role['yyid'];
		}
		else {
			$qj_yyidarray = explode(',', $qj_yyid);
			$key = array_search($role['yyid'], $qj_yyidarray);

			if ($key !== false) {
				array_splice($qj_yyidarray, $key, 1);
			}

			$qj_yyid = implode(',', $qj_yyidarray);
		}

		$yyname1 = d()->query('select yy_name from oa_yiyuan where yy_ID =' . $role['yyid'] . '');
		$yyname = $yyname1[0]['yy_name'];
		$data->where('qj_ID=' . $role['ID'] . '')->setField('qj_yyid', ltrim($qj_yyid, ','));
		js_alert('Role/system_qj/yyid/' . $role['yyid'], '修改成功');
	}

	public function system_auth()
	{
		$data = m('auth');
		$rows_add = $data->where('auth_level=1')->select();
		$sql = 'select * from oa_auth where auth_level=1 ';
		$rows = array();
		$rows2 = mysql_query($sql);

		while ($rows1 = mysql_fetch_array($rows2)) {
			$erji1 = mysql_query('select * from oa_auth where auth_pid = ' . $rows1['auth_ID'] . '');

			while ($erji2 = mysql_fetch_array($erji1)) {
				$erji[] = array('auth_ID' => $erji2['auth_ID'], 'auth_name' => $erji2['auth_name'], 'auth_level' => $erji2['auth_level'], 'auth_c' => $erji2['auth_c'], 'auth_a' => $erji2['auth_a']);
			}

			$rows[] = array('auth_ID' => $rows1['auth_ID'], 'auth_name' => $rows1['auth_name'], 'auth_level' => $rows1['auth_level'], 'auth_c' => $rows1['auth_c'], 'auth_a' => $rows1['auth_a'], 'erji' => $erji);
			unset($erji);
		}

		$this->assign('list', $rows);
		$this->assign('yiji', $rows_add);
		$this->display('system_auth');
	}

	public function auth_insert()
	{
		$data = m('auth');

		if ($data->create()) {
			$data_pid = $data->auth_pid;

			if ($data->auth_c != '') {
				$strsql = 'select * from oa_auth where auth_c=\'' . $data->auth_c . '\' and auth_a = \'' . $data->auth_a . '\'';
				$congfu = $data->query($strsql);

				if (!empty($congfu)) {
					js_alert('', '控制器和方法已存在！');
					exit();
				}
			}

			if ($data->auth_pid == 0) {
				$data->auth_level = 1;
			}
			else {
				$data->auth_level = 2;
			}

			$auth_ID = $data->add();
			$data_path['auth_path'] = $data_pid . '-' . $auth_ID;

			if ($data->where('auth_ID=' . $auth_ID . '')->save($data_path)) {
				js_alert('Role/system_auth', '添加成功');
			}
			else {
				js_alert('Role/system_auth', '添加失败');
			}
		}
	}

	public function system_roleZu()
	{
		$data = m('auth');
		$sql = 'select * from oa_auth where auth_level=1 ';
		$rows = array();
		$rows2 = mysql_query($sql);

		while ($rows1 = mysql_fetch_array($rows2)) {
			$erji1 = mysql_query('select * from oa_auth where auth_pid = ' . $rows1['auth_ID'] . '');

			while ($erji2 = mysql_fetch_array($erji1)) {
				$strsql = 'select * from oa_role where role_ID=' . i('request.roleID') . ' and find_in_set(' . $erji2['auth_ID'] . ', role_auth_ids)';
				$xuanzhong = d()->query($strsql);

				if (!empty($xuanzhong)) {
					$xz = 'checked';
				}
				else {
					$xz = '';
				}

				$erji[] = array('auth_ID' => $erji2['auth_ID'], 'auth_name' => $erji2['auth_name'], 'auth_level' => $erji2['auth_level'], 'auth_c' => $erji2['auth_c'], 'auth_a' => $erji2['auth_a'], 'auth_xz' => $xz);
			}

			$rows[] = array('auth_ID' => $rows1['auth_ID'], 'auth_name' => $rows1['auth_name'], 'auth_level' => $rows1['auth_level'], 'auth_c' => $rows1['auth_c'], 'auth_a' => $rows1['auth_a'], 'erji' => $erji);
			unset($erji);
		}

		$this->assign('list', $rows);
		$this->assign('roleID', i('get.roleID'));
		$this->display();
	}

	public function roleZu_insert()
	{
		$quanxian = i('post.ZuRole');

		if ($quanxian == '') {
			js_alert('', '请选择功能');
			exit();
		}

		$zubieID = i('post.RoleID');
		$roleZuName = i('post.RoleName');
		$data = m('auth');

		for ($i = 0; $i <= count($quanxian) - 1; $i++) {
			$auth_ca1 = $data->query('select auth_c,auth_a from oa_auth where auth_ID=' . $quanxian[$i] . '');
			$auth_ca .= $auth_ca1[0]['auth_c'] . '-' . $auth_ca1[0]['auth_a'] . ',';
			$role_auth_ids1 .= $quanxian[$i] . ',';
		}

		$genxin['role_auth_ids'] = rtrim($role_auth_ids1, ',');
		$genxin['role_auth_ac'] = rtrim($auth_ca, ',');
		$datarole = m('role');

		if ($datarole->where('role_ID=' . $zubieID . '')->save($genxin)) {
			js_alert('', '设置成功');
		}
		else {
			js_alert('', '没有更改数据');
		}
	}

	public function system_allowip()
	{
		$ipallow = m('ipallow');
		$ipallowinfo = $ipallow->where('ID=1')->find();

		if ($ipallowinfo['shifoukaiqi'] == '0') {
			$this->assign('checked', 'checked');
		}

		$this->assign('allow_username', $ipallowinfo['allow_username']);
		$this->assign('IPdizhi', $ipallowinfo['IPdizhi']);
		$this->assign('IPdizhiduan', $ipallowinfo['IPdizhiduan']);
		$this->assign('shifoukaiqi', $ipallowinfo['shifoukaiqi']);
		$this->display('');
	}

	public function system_allowip_update()
	{
		$ipallow = m('ipallow');

		if ($ipallow->create()) {
			if ($ipallow->shifoukaiqi == 'on') {
				$ipallow->shifoukaiqi = 0;
			}
			else {
				$ipallow->shifoukaiqi = 1;
			}

			$ipallow->where('ID=1')->save();
		}

		js_alert('', '设置成功');
	}

	public function system_shouji()
	{
		$shoujiyanzheng = m('shoujiyanzheng');
		$shoujiinfo = $shoujiyanzheng->where('ID=1')->find();

		if ($shoujiinfo['shifoukaiqi'] == '0') {
			$this->assign('checked', 'checked');
		}

		$this->assign('shifoukaiqi', $shoujiinfo['shifoukaiqi']);
		$this->assign('dx_username', $shoujiinfo['dx_username']);
		$this->assign('dx_password', $shoujiinfo['dx_password']);
		$this->display('');
	}

	public function system_shouji_update()
	{
		$shoujiyanzheng = m('shoujiyanzheng');

		if ($shoujiyanzheng->create()) {
			if ($shoujiyanzheng->shifoukaiqi == 'on') {
				$shoujiyanzheng->shifoukaiqi = 0;
			}
			else {
				$shoujiyanzheng->shifoukaiqi = 1;
			}

			$shoujiyanzheng->where('ID=1')->save();
		}

		js_alert('', '设置成功');
	}
}


?>
