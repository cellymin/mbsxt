<?php
namespace OA\Model;
use Think\Model;

class HuanzeModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('bz_ID','require','病种必须选择'),
		array('zxfs_ID','require','咨询方式必须选择'),
		//array('zxfa_ID','require','标题已经存在！',0,'unique',1), 
        );
		
	// 定义自动完成
   /* protected $_auto  =   array(
        array('create_time','time',1,'function'),
        );*/

}
?>
