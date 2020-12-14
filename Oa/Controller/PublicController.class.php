<?php

namespace OA\Controller;


class IndexController extends \Component\AdminController
{
	public function top()
	{
		$user = m('Useradmin');
		$userid = session('username_lf');
		$sql = 'select oa_useradmin.user_ID,oa_useradmin.role_ID,oa_useradmin.userchinaname,oa_role.role_name ' . "\r\n" . '		 from oa_useradmin,oa_role ' . "\r\n" . '		 where  oa_useradmin.role_ID = oa_role.role_ID and oa_useradmin.user_ID = \'' . $userid . '\'';
		$topinfo = $user->query($sql);
		$this->assign('username', $topinfo[0]['userchinaname']);
		$this->assign('userrolename', $topinfo[0]['role_name']);
		$this->display();
	}

	public function safe()
	{
		session('username_lf', NULL);
		session('user_role', NULL);
		session_destroy();
		echo '<script language=\'javascript\'>;parent.window.location.href=\'' . DQURL . 'Login/welcome\';</script>';
	}
}


?>
