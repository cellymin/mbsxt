<?php
namespace OA\Model;
use Think\Model;

class HuanzejingjiaModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('','',''),
		
		//array('bz_name','require','标题已经存在！',0,'unique',1), 
        );
		
	// 定义自动完成
   /* protected $_auto  =   array(
        array('create_time','time',1,'function'),
        );*/

}
?>
