<?php

namespace OA\Controller;
use OA\Think;


class EmptyController extends \Think\Controller
{
	public function _empty()
	{
		echo '<script language=\'javascript\'>alert(\'访问不存在\');history.back();</script>';
	}
}


?>
