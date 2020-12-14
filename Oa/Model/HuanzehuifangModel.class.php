<?php
namespace OA\Model;
use Think\Model;

class HuanzehuifangModel extends Model {
	//一次性获取全部错误
	// protected $patchValidate  =  true;
	 
    // 定义自动验证
    protected $_validate    =   array(
	    array('hf_zhuti','require','请输入回访主题'),			 
        array('hf_time','require','请输入回访时间'),		
		array('hf_fangshi','require','请选择回访方式'),	 
        );
		
		
	

}
?>
