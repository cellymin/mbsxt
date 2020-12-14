<?
  
	namespace Component;
	use  Think\Controller;
	
class  ObAdminController extends Controller{
		
		function __construct(){	
		   //先执行父类的构造方法，否则系统会报错
		   //因为原先的构造方法默认是被执行的	
			parent::__construct();	
		 	if(session('username_lfob')==''){
				$this->success('请先登录','http://'.$_SERVER['SERVER_NAME'].'/shichang.php');
				exit;	
			}	 
		   //CONTROLLER_NAME ---Goods
			//ACTION_NAME  ----showlist
			//当前请求操作
        $now_ac = CONTROLLER_NAME."-".ACTION_NAME;
	   
		
		
		  //过滤控制器和方法，避免用户非法请求
        //通过角色获得用户可以访问的控制器和方法信息
        $sql ="select role_auth_ac from ob_role  where role_ID =".$_SESSION['user_roleob'];
        $auth_ac = D()->query($sql);
        $auth_ac = $auth_ac[0]['role_auth_ac']; 
		 
		
			  
		 //判断$now_ac是否在$auth_ac字符串里边有出现过
        //strpos函数如果返回false是没有出现，返回0 1 2 3表示有出现
        //管理员不限制
		
        //默认以下权限没有限制
		 $allow_ac = array(
			  //公用控制器
			 'Index-main','Index-top','Index-safe','Index-center','Index-left','Index-home','Index-safe','Index-userstop',
			  //添加咨询 模块
			 'Addzixun-zixunliandong','Addzixun-zixunpdxj',
			 'Addzixun-bzliandong','Addzixun-bingzhongpdxj',
			 'Addzixun-wzliandong',
			 'Addzixun-xxlyliandong','Addzixun-yuyueyyliandong','Addzixun-zxtimexg','Addzixun-shoujijc','Addzixun-zixunyuanliandong',
			 'Addzixun-add_insert','Addzixun-shoujijc_update','Addzixun-shoujisheng','Addzixun-shoujitj','Addzixun-ipdizhijc','Addzixun-djrliandong', 
			  // 查看咨询模块
			 'ManageZx-zixunyuanliandong','ManageZx-user_showmenu',//'ManageZx-manage_excle',
			  //修改咨询 模块
			 // 'Updatezixun-huifangjihua_insert',
			 // 'Updatezixun-huifangdengji',
			 // 'Updatezixun-huifangjihua_del',
			 // 'Updatezixun-huifangjihua',
			 'Updatezixun-zixun_update',
			 
			  // 系统设置模块
			       /*病种模块*/
				  'systembz-bz_insert','systembz-bz_submit',//修改启用删除全部保存	
				  /*咨询方式*/
				   'Systemquyu-zxfs_insert','Systemquyu-zxfs_submit',
				  /*短信模版设置*/
				   'SystemSite-message_insert','SystemSite-message_del','SystemSite-message_update',
				   
				   
			   // 医院设置模块
				   /*医院医生设置*/
				/*   'SystemSite-yydoctor_insert','SystemSite-system_doctor_stop','SystemSite-system_yydoctor_del','SystemSite-system_yydoctor_update', */
					
		
				  /*医院病种设置*/
				  'Yiyuanmanage-yybz_insert',
				  /*医院咨询方式设置*/
				  'Yiyuanmanage-yyzxfs_insert',
				  /*医院短信设置*/
				  'SystemSite-duanxinset_update',
				  /*医院个性化设置*/
				  'Role-system_qj_update',
			      /*渠道设置*/
				 'Yiyuanmanage-qudao_insert', 'Yiyuanmanage-qudao_update', 'Yiyuanmanage-qudao_del','Yiyuanmanage-qudao_tingyong',
				 /*设置对接人*/
				  'Duijieren-djr_add','Duijieren-djr_update','Duijieren-djr_del','Duijieren-djr_tingyong',
				  
				  
			  // 统计报表模块  (方法名小写)
			 'Tongji-excledc_tongji','Tongji-excledc_bingzhong','Tongji-excledc_fangshi','Tongji-excledc_qudao','Tongji-excledc_zixunyuan','Tongji-report_common',	
			 'Tongji-excledc_guanjianci','Tongji-excledc_shijianduan',
	
			 // 短信发送模块
			 'ManageZx-sendmessage_api',
			 //工作日志模块
			  'Employee-rizhi_add','Employee-jihua_add','Employee-employee_rizhi','Employee-yuangongliandong',
			  //用户管理模块
			   'Useradmin-system_zhimg',
		 );
	   
			 if(!in_array($now_ac,$allow_ac) && $_SESSION['user_roleob'] !=1 && strpos($auth_ac,$now_ac) === false){
				JS_alert('index/userstop','没有权限操作,请联系管理员');
				//$this->success('没有权限操作,请联系管理员',"".DQURL."index/UserStop",1);	
				exit();
			}  
		  
		}
		
		public function _empty(){		
			 echo "<script language='javascript'>alert('访问不存在');history.back();</script>";
		 }
		
		
		
}


?> 