<?php

namespace OA\Controller;


class UseradminController extends \Component\AdminController
{
	public function system_zh()
	{
		$Columns = a('Tongji');
		$tiaojian = $Columns->report_common($canshu);
		$canshu = i('request.');

		if (!empty($canshu['submit1'])) {
			if ($canshu['yy_ID'] != '') {
				$tiaojian .= ' and find_in_set(' . $canshu['yy_ID'] . ',oa_useradmin.yy_ID) ';
				$this->assign('dqyyID', $canshu['yy_ID']);
			}

			if ($canshu['role_ID'] != '') {
				$tiaojian .= ' and oa_role.role_ID=' . $canshu['role_ID'] . ' ';
				$this->assign('dqrole_ID', $canshu['role_ID']);
			}
		}

		//bigyy();
		$m = m('useradmin');
		$total = $m->count();
		$pagesize = 10000;
		$page = new \Component\Page($total, $pagesize);
		$sql = 'select oa_role.role_name,oa_useradmin.userchinaname,oa_useradmin.yy_ID,oa_useradmin.user_ID,oa_useradmin.username,oa_useradmin.role_ID,oa_useradmin.user_del  ' . "\r\n" . '					from oa_role,oa_useradmin' . "\r\n" . '					where  oa_useradmin.role_ID = oa_role.role_ID ' . $tiaojian . '  order by role_ID ' . $page->limit;
		$list = $m->query($sql);

		if (empty($list)) {
			$sql = 'select oa_role.role_name,oa_useradmin.userchinaname,oa_useradmin.yy_ID,oa_useradmin.user_ID,oa_useradmin.username,oa_useradmin.role_ID,oa_useradmin.user_del  ' . "\r\n" . '					from oa_role,oa_useradmin' . "\r\n" . '					where  oa_useradmin.role_ID = oa_role.role_ID  order by role_ID ' . $page->limit;
			$list = $m->query($sql);
		}

		$yy_name = m('Yiyuan');

		for ($i = 0; $i <= count($list) - 1; $i++) {
			$yyidzifuchuan = $list[$i]['yy_ID'];
			$yyidarray = explode(',', $yyidzifuchuan);

			for ($x = 0; $x <= count($yyidarray) - 1; $x++) {
				$yiyuanname = $yy_name->where('yy_ID = ' . $yyidarray[$x] . '')->getField('yy_name');
				$yiyuan1 .= ' ' . $yiyuanname . ',';
			}

			$list[$i]['yy_ID'] = rtrim($yiyuan1, ',');
			unset($yiyuan1);
		}

		$down_page = $page->fpage();
		$this->assign('list', $list);
		$this->assign('page', $down_page);
		$yiyuan = m('Yiyuan');
		$yiyuanlist = $yiyuan->where('yy_del=0')->select();
		$this->assign('yiyuanlist', $yiyuanlist);
		$quanxian = m('role');
		$quanxianlist = $quanxian->where('role_del=0')->select();
		$this->assign('quanxianlist', $quanxianlist);
		$this->display('');
	}

	public function zh_insert()
	{
		//bigyy();
		$data = d('Useradmin');
		$yy_ID1 = i('post.yy_ID');
		$yy_ID = implode(',', $yy_ID1);

		if ($yy_ID1 == '') {
			js_alert('', '请选择至少一个门店');
			exit();
		}

		if ($data->create()) {
			$data->user_lastIP = get_client_ip();
			$data->user_addtime = date('Y-m-d H:i:s');
			$data->yy_ID = $yy_ID;
			$data->add();
			js_alert('Useradmin/system_zh', '添加成功');
		}
		else {
			$this->error($data->getError());
		}
	}

	public function useradmin_stop()
	{
		$data = m('useradmin');
		$user_ID = i('get.user_ID');

		if ($data->where('user_ID=' . $user_ID . '')->setField('user_del', '1')) {
			js_alert('Useradmin/system_zh', '修改成功');
		}
		else {
			js_alert('', '修改失败');
		}
	}

	public function useradmin_start()
	{
		$data = m('useradmin');
		$user_ID = i('get.user_ID');

		if ($data->where('user_ID=' . $user_ID . '')->setField('user_del', '0')) {
			js_alert('Useradmin/system_zh', '修改成功');
		}
		else {
			js_alert('', '修改失败');
		}
	}

	public function system_zhxg()
	{
		//bigyy();
		$data = m('useradmin');
		$user_ID = i('get.userid');
		$userinfo = $data->where('user_ID=' . $user_ID . '')->select();
		$this->assign('userinfo', $userinfo);
		$yy_array = explode(',', $userinfo[0]['yy_ID']);
		$this->assign('useryyid', $yy_array);
		$this->assign('userinfo_qxz', $userinfo[0]['role_ID']);

		if ($userinfo[0]['user_rizhi'] == 0) {
			$this->assign('selectYES', 'selected');
		}
		else {
			$this->assign('selectNO', 'selected');
		}

		$quanxian = m('role');
		$quanxianlist = $quanxian->where('role_del=0')->select();
		$this->assign('quanxianlist', $quanxianlist);
		$yiyuan = m('Yiyuan');
		$yiyuanlist = $yiyuan->where('yy_del=0')->select();

		for ($i = 0; $i <= count($yiyuanlist) - 1; $i++) {
			if (in_array($yiyuanlist[$i]['yy_ID'], $yy_array)) {
				$yiyuanlist[$i]['checked'] = 'checked';
			}
			else {
				$yiyuanlist[$i]['checked'] = '';
			}
		}

		$this->assign('yiyuanlist', $yiyuanlist);
		$this->display();
	}

	public function zh_del()
	{
		$data = m('useradmin');
		$user_ID = i('get.user_ID');
		$shuju = m('huanze');
		$shuju1 = $shuju->where('userID =' . $user_ID . '')->getField('userID');
		$superman = $data->where('user_ID=' . $user_ID . '')->getField('role_ID');

		if ($superman == 1) {
			js_alert('', '超级管理员无法删除!');
			exit();
		}
		else if (!empty($shuju1)) {
			js_alert('', '账号存在数据,只可停用! 点击[启用中/停用中]切换状态');
			exit();
		}
		else {
			$data->where('user_ID=' . $user_ID . '')->delete();
			js_alert('Useradmin/system_zh', '删除成功!');
		}
	}

	public function zh_update()
	{
		$zhanghao1 = m('useradmin');
		$zhanghao = i('post.');
		$yy_ID1 = i('post.yy_ID');
		$yy_ID = implode(',', $yy_ID1);

		if ($yy_ID1 == '') {
			js_alert('', '请选择至少一个门店');
			exit();
		}

		$data['yy_ID'] = implode(',', $zhanghao['yy_ID']);
		$data['role_ID'] = $zhanghao['role_ID'];
		$data['userchinaname'] = $zhanghao['userchinaname'];
		$data['user_rizhi'] = $zhanghao['user_rizhi'];
		$data['user_shouji'] = $zhanghao['user_shouji'];

		if ($zhanghao['userpsw'] != '****') {
			$data['userpsw'] = md5($zhanghao['userpsw']);
		}

		if ($zhanghao1->where('username = \'' . $zhanghao['username'] . '\'')->save($data)) {
			js_alert('', '修改成功');
		}
		else {
			js_alert('', '设置一致，无需修改');
		}
	}

	public function system_zhimg()
	{
		$canshu = i('post.');
		$touxiang = m('useradmin');
		$datas['QQhaoma'] = $canshu['QQhaoma'];

		if ($canshu['submit2'] != '') {
			if ($touxiang->where('user_ID = \'' . session('username_lf') . '\'')->save($datas)) {
				js_alert('', '修改成功');
			}
			else {
				js_alert('', '修改失败');
			}
		}

		$this->display('');
	}
}


?>
