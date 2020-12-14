<?php
namespace OA\Model;
use Think\Model;

class DuanxinModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('message_title','require','回访短信主题必须填写'),
		array('message_message','require','回访短信内容必须填写'),
		//array('bz_name','require','标题已经存在！',0,'unique',1), 
        );
		
	// 定义自动完成
   /* protected $_auto  =   array(
        array('create_time','time',1,'function'),
        );*/

}
?>
