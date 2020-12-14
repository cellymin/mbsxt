<?php	
include("conn.php"); 	
class person 
{ 
	
	public function yymd($sjhm,$hzname,$yyh){ 
	    if(empty($sjhm) and empty($hzname) and empty($yyh)){
			   return "NoParameter";
			   exit();
			}
	
		if(!empty($sjhm)){
	          $tiaojian .= " and oa_managezx.shouji='".$sjhm."' ";
			}
	    if(!empty($hzname)){
	          $tiaojian .= " and oa_managezx.huanzeName='".$hzname."' ";
			}
	    if(!empty($yyh)){
	          $tiaojian .= " and oa_managezx.yuyuehao='".$yyh."' ";
			}	
		$sqlstr = "select oa_managezx.zx_ID,oa_managezx.huanzeName,
				   oa_managezx.shouji,oa_huanzeinfo.liaotianjilu,
				   oa_managezx.yuyueTime,oa_managezx.yuyuehao, 
				   oa_managezx.bz_name,oa_managezx.zx_time,oa_managezx.yuyueyishengID 
             from oa_managezx  left outer join oa_huanzeinfo on oa_managezx.zx_ID=oa_huanzeinfo.zx_ID 
			 where  oa_managezx.shifouyuyue=0 and oa_managezx.shifoudaozhen=1 and oa_managezx.yy_ID =1 ".$tiaojian."";	
		
		 
		 $info = array();		
		 $query = mysql_query($sqlstr);
		 $i=0;
		 while($info1 = mysql_fetch_array($query)){ 
		      $info[$i]['huanzeName']=$info1['huanzeName'];
			  $info[$i]['zx_ID']=$info1['zx_ID'];
			  $info[$i]['shouji']=$info1['shouji'];
			  $info[$i]['zx_time']=$info1['zx_time'];
			  $info[$i]['yuyueTime']=$info1['yuyueTime'];
			  $info[$i]['yuyuehao']=$info1['yuyuehao'];
			  $info[$i]['bz_name']=$info1['bz_name'];
			  
			  $querydoctor = mysql_query("select doctor_name from oa_doctor where doctor_ID='".$info1['yuyueyishengID']."'");
			    while($yyname = mysql_fetch_array($querydoctor)){
				     $doctorname = $yyname['doctor_name'];
				}
			  $info[$i]['yuyueyisheng'] =$doctorname;
			  $info[$i]['bz_name']=$info1['bz_name'];
			   $querybeizhu = mysql_query("select yuyueBeizhu from oa_huanzeyuyue where zx_ID='".$info1['zx_ID']."'");
			    while($beizhu = mysql_fetch_array($querybeizhu)){
				     $yuyuebeizhu = $beizhu['yuyueBeizhu'];
				}
			  $info[$i]['yuyuebeizhu']=$yuyuebeizhu;
			  //$info[$i]['liaotianjilu']=$info1['liaotianjilu'];   
			  $i++;
		 }
		 for($i=0;$i<count($info);$i++){
			  $fanhui .=" ".$info[$i]['huanzeName']."|".$info[$i]['shouji']."|".$info[$i]['yuyuehao']."|".$info[$i]['zx_time']."|".$info[$i]['yuyueTime']."|".$info[$i]['bz_name']."|".$info[$i]['yuyueyisheng']."|".$info[$i]['yuyueBeizhu']."|".$info[$i]['zx_ID']."|$";
		 }
		 //return json_encode($info);
		 return $fanhui;
    }
	
	public function daozhen($zx_ID,$pw){
		 if(empty($zx_ID) or empty($pw)){
			   return "NoParameter";
			   exit();
			}
		if($pw!="fjdkls39cmxdu3rjcxcDFDS23D"){
			return "password error";
			   exit();
		}
	
		$strsql = "UPDATE oa_huanze SET shifoudaozhen = 0,daozhen_time='".date("Y-m-d H:i:s")."' WHERE zx_ID = ".$zx_ID."";
		$strsql1 = "UPDATE oa_managezx SET shifoudaozhen = 0,daozhen_time='".date("Y-m-d H:i:s")."' WHERE zx_ID = ".$zx_ID."";
		$strsql2 = "update oa_huanzecaozuo set daozhenUserID = '999' where zx_ID=".$zx_ID."";
		if(mysql_query($strsql) and mysql_query($strsql1)){
			mysql_query($strsql2);
			return "修改到诊状态成功";
		}else{
			$strsql = "UPDATE oa_huanze SET shifoudaozhen = 1,daozhen_time='' WHERE zx_ID = ".$zx_ID."";
		    $strsql1 = "UPDATE oa_managezx SET shifoudaozhen = 1,daozhen_time='' WHERE zx_ID = ".$zx_ID."";
			mysql_query($strsql);
			mysql_query($strsql1);
			return "修改到诊状态失败";
		}		
	}
	
	public function qubiao($starttime,$endtime,$yyid,$pw){
		
		   if(empty($starttime) or empty($endtime) or empty($yyid) or empty($pw)){
			   return "NoParameter";
			   exit();
			}
		  if($pw!="fjdkls39cmxdu3rjcxcDFDS23D"){
			return "password error";
			exit();
		  }
		 $conn_user=@mysql_connect("localhost:33107","root","f4d89saf98fsdfw") or die ("链接错误");
				mysql_select_db("oa",$conn_user);
				mysql_query("set names 'utf8'");
				
		   $sqlstr = "select oa_managezx.zx_ID,oa_managezx.huanzeName,
				   oa_managezx.shouji,oa_huanzeinfo.liaotianjilu,
				   oa_managezx.yuyueTime,oa_managezx.yuyuehao, 
				   oa_managezx.bz_name,oa_managezx.zx_time,oa_managezx.yuyueyishengID 
             from oa_managezx  left outer join oa_huanzeinfo on oa_managezx.zx_ID=oa_huanzeinfo.zx_ID 
			 where  oa_managezx.shifouyuyue=0 and oa_managezx.shifoudaozhen=1 and oa_managezx.yy_ID =".$yyid." 
			 and oa_managezx.zx_time between '".$starttime." 00:00:00' and '".$endtime." 23:59:59' ";
			
		 $info = array();		
		 $query = mysql_query($sqlstr);
		 $i=0;
		 while($info1 = mysql_fetch_array($query)){ 
		      $info[$i]['huanzeName']=$info1['huanzeName'];
			  $info[$i]['zx_ID']=$info1['zx_ID'];
			  $info[$i]['shouji']=$info1['shouji'];
			  $info[$i]['zx_time']=$info1['zx_time'];
			  $info[$i]['yuyueTime']=$info1['yuyueTime'];
			  $info[$i]['yuyuehao']=$info1['yuyuehao'];
			  $info[$i]['bz_name']=$info1['bz_name'];
			
			  $info[$i]['yuyueyisheng'] =$doctorname;
			  $info[$i]['bz_name']=$info1['bz_name'];
			   $querybeizhu = mysql_query("select yuyueBeizhu from oa_huanzeyuyue where zx_ID='".$info1['zx_ID']."'");
			    while($beizhu = mysql_fetch_array($querybeizhu)){
				     $yuyuebeizhu = $beizhu['yuyueBeizhu'];
				}
			  $info[$i]['yuyuebeizhu']=$yuyuebeizhu;
			  //$info[$i]['liaotianjilu']=$info1['liaotianjilu'];   
			  $i++;
		 }
		
		  //return $sqlstr;
		      $heji = count($info);
			 for($i=0;$i<$heji;$i++){
			  @$fanhui .=" ".$info[$i]['huanzeName']."|".$info[$i]['shouji']."|".$info[$i]['yuyuehao']."|".$info[$i]['zx_time']."|".$info[$i]['yuyueTime']."|".$info[$i]['bz_name']."|".$info[$i]['yuyueyisheng']."|".$info[$i]['yuyueBeizhu']."|".$info[$i]['zx_ID']."|$";
		    }
		   return $fanhui; 	 
	}
} 
  
?>