<?php
namespace OA\Model;
use Think\Model;

class UseradminModel extends Model {
	//一次性获取全部错误
	// protected $patchValidate  =  true;
	 
    // 定义自动验证
    protected $_validate    =   array(
	    array('username','require','请输入账户名'),			 
        array('username','','帐号名称已经存在！',0,'unique',1), 
		array('username','5,20','账号长度5-20个字符',3,'length'),	
		array('userpsw','checkPwd','密码格式不正确',0,'function'),
		//array('yy_ID','require','请选择医院'),
		array('role_ID','require','请选择权限组'),
		array('userpsw','5,20','密码长度5-20个字符',3,'length'),
		 	 
        );
		
	 //定义自动完成
      protected $_auto  =   array(
        //array('user_addtime','date("Y-m-d H:i:s")',1,'function'),
		array('userpsw','md5',3,'function'),
        );

}
?>
