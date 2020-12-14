<?php
namespace OA\Model;
use Think\Model;

class DuanxinsetModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('yy_ID','require','医院ID不允许为空'),	
        );
		
	
}
?>
