<?php

  namespace Component;

class PageLF {
   
      function fenye1($table,$tj,$page,$page_size1) //查询列表
		{
		
		$page=$_GET[page];
		
		if($page==""){
		 $page=1;
		}
	   
		$page_size = $_REQUEST[page_size];
		if($page_size==""){
		$page_size=20;
		}
		if($page_size1 !=''){
		 $page_size=$page_size1;
		}
		
		$sqlstr="select * from ".$table." where 1=1 ".$tj."  limit ".(($page-1) * $page_size).",".$page_size."";
		//echo $sqlstr;
		//exit;
		if($page_size == 'all'){
		$sqlstr="select * from ".$table." where 1=1 ".$tj."";
		}
		
		$m =M('');
		$list_article = $m->query($sqlstr);
		/*$result=mysql_query($sqlstr);
		$list_article =array();
		while($list1=mysql_fetch_array($$result)){
		   array_push($list_article,$list1);
		}*/
		return $list_article;	 
	}
	
	
		function fenye2($table,$tj,$page,$page_size1,$urlcanshu){	//底部分页	
	   	
		$page=$_GET[page];
		if($page==""){
		 $page=1;
		}
	
		$page_size=$_REQUEST[page_size];
		if($page_size==""){
		$page_size=20;
		}
	   if($page_size1 !=''){
		 $page_size=$page_size1;
		}
		
		 $sqlstr="select count(*) as total from ".$table." where 1=1 ".$tj."";
		 //echo $sqlstr;
		 $zong_tiaoshu1=mysql_query($sqlstr);				//执行SQL语句
		 $rs=mysql_fetch_array($zong_tiaoshu1);
		 
		 if($page_size != 'all'){
	     $page_count=ceil($rs[total]/$page_size);//总页数=总条数/每页显示数
	     }
		 else{
		  $page_count = "1";
		 }
		 
		 $url = "http://" .$_SERVER['SERVER_NAME']."".$_SERVER["PHP_SELF"];
		 //echo  "<form name='fy' method='post'>";
		 
		 $fanhui .=  "<div class='ui-jqgrid' style='border:0px;'><div class='ui-jqgrid-pager' style='border:0px;'><div class='ui-pager-control'><table><tbody><tr><td id='pager_list_1_left'align='left'></td><td id='pager_list_1_center'align='center'style='white-space:pre;'><table class='ui-pg-table ui-common-table ui-paging-pager'><tbody><tr>";

         $fanhui .= "<td class='ui-pg-button' title='首页' style='cursor: default;'><a href='".$url."?page=1&page_size=".$page_size.$urlcanshu."'><span class='icon icon-step-backward'></span></a></td>";
		 $fanhui .= "<td class='ui-pg-button' title='上一页' style='cursor: default;'>";
	if($page-1>=1){
		 $fanhui .= "<a href='".$url."?page=".($page-1)."&page_size=".$page_size.$urlcanshu."'><span class='icon icon-backward'></span></a>";
	}
	else{
		 $fanhui .= "<span class='icon icon-backward'></span>";
	}
		 $fanhui .= "</td><td class='ui-pg-button ui-disabled'><span class='ui-separator'></span></td>";
		 $fanhui .= "<td>";

        $fanhui .=  "<select class='ui-pg-selbox form-control' name='Types' onchange=\"if (this.options[this.selectedIndex].value!=''){window.open(this.options[this.selectedIndex].value,'_self')};\">";
		for($i=1;$i<=$page_count;$i++){
		  if($page==$i)
          $fanhui .=  "<option value='".$url."?page=".$i."&page_size=".$page_size.$urlcanshu."' selected>".$i."</option>";
		  else
		  $fanhui .=  "<option value='".$url."?page=".$i."&page_size=".$page_size.$urlcanshu."'>".$i."</option>";
        }  
		 $fanhui .= "</select>";

		 $fanhui .= "</td>";
		 
		 $fanhui .= "<td class='ui-pg-button ui-disabled'><span class='ui-separator'></span></td>";
		 $fanhui .= "<td class='ui-pg-button' title='下一页' style='cursor: default;'>";
		 
			  if($page+1>$page_count){	
		 $fanhui .= "<span class='icon icon-forward'></span>";
			  } 
			  else
			  {
		 $fanhui .= "<a href='".$url."?page=".($page+1)."&page_size=".$page_size.$urlcanshu."'><span class='icon icon-forward'></span></a>";
			  } 
		 $fanhui .= "</td><td class='ui-pg-button' title='尾页' style='cursor: default;'>";
		 $fanhui .=   "<a href='".$url."?page=".$page_count."&page_size=".$page_size.$urlcanshu."'><span class='icon icon-step-forward'></span></a>";
		 
		 
		 $fanhui .= "</td>";

		    switch($_REQUEST[page_size]){
			   case "10";
			    $selected1="selected";
			  break; 
			  case "20";
			    $selected2="selected";
			  break; 
			  case "50";
			    $selected3="selected";
			  break;
	 
			  default:
			   $selected2="selected";
			  break;
			  
			}

        $fanhui .=  "<td class='hidden-xs'><select class='ui-pg-selbox form-control' name='Types' onchange=\"if (this.options[this.selectedIndex].value!=''){window.open(this.options[this.selectedIndex].value,'_self')};\">";
        $fanhui .=  "<option value='".$url."?page=".$page."&page_size=10".$urlcanshu."' ".$selected1.">10条</option>";
        $fanhui .=  "<option value='".$url."?page=".$page."&page_size=20".$urlcanshu."' ".$selected2.">20条</option>";
        $fanhui .=  "<option value='".$url."?page=".$page."&page_size=50".$urlcanshu."' ".$selected3.">50条</option>";
 
        $fanhui .= "</select>";

		$fanhui .=  "</td></tr></tbody></table></td><td><div class='ui-paging-info'>".$page." - ".$page_count."　共 ".$rs[total]." 条</div></td>";
		$fanhui .= "</tr></tbody></table></div></div></div>";
		
		 // echo "</form>";	
		 //echo $urlcanshu;
		 return $fanhui;	
	   }

	
	
	function fenye_lhcx1($sql,$page,$page_size1) //查询列表
		{
		
		$page=$_GET[page];
		
		if($page==""){
		 $page=1;
		}
	   
		$page_size = $_REQUEST[page_size];
		if($page_size==""){
		$page_size=20;
		}
		if($page_size1 !=''){
		 $page_size=$page_size1;
		}
		
		$sqlstr=$sql. " limit ".(($page-1) * $page_size).",".$page_size."";
		//echo $sqlstr;
		//exit;
		if($page_size == 'all'){
		$sqlstr=$sql;
		}
		//echo $sqlstr;
		$m =M('');
		$list_article = $m->query($sqlstr);
		//print_r($list_article);
		/*$result=mysql_query($sqlstr);
		$list_article =array();
		while($list1=mysql_fetch_array($$result)){
		   array_push($list_article,$list1);
		}*/
		return $list_article;	 
	}
	
	function fenye_lhcx2($sql,$page,$page_size1,$urlcanshu){	//底部分页	
	   	
		$page=$_GET['page'];
		if($page==""){
		 $page=1;
		}
	
		$page_size=$_REQUEST['page_size'];
		if($page_size==""){
		$page_size=20;
		}
	   if($page_size1 !=''){
		 $page_size=$page_size1;
		}
		
		 $sqlstr = $sql;
		 //echo $sqlstr;
		 $zong_tiaoshu1=mysql_query($sqlstr);				//执行SQL语句
		 $rs=mysql_fetch_array($zong_tiaoshu1);
		// print_r($rs);
		 
		 if($page_size != 'all'){
	     $page_count=ceil($rs[total]/$page_size);//总页数=总条数/每页显示数
	     }
		 else{
		  $page_count = "1";
		 }
		 
		 $url = "http://" .$_SERVER['SERVER_NAME']."".$_SERVER["PHP_SELF"];
		 //echo  "<form name='fy' method='post'>";
		 
		 $fanhui .=  "<div class='ui-jqgrid' style='border:0px;'><div class='ui-jqgrid-pager' style='border:0px;'><div class='ui-pager-control'><table><tbody><tr><td id='pager_list_1_left'align='left'></td><td id='pager_list_1_center'align='center'style='white-space:pre;'><table class='ui-pg-table ui-common-table ui-paging-pager'><tbody><tr>";

         $fanhui .= "<td class='ui-pg-button' title='首页' style='cursor: default;'><a href='".$url."?page=1&page_size=".$page_size.$urlcanshu."'><span class='icon icon-step-backward'></span></a></td>";
		 $fanhui .= "<td class='ui-pg-button' title='上一页' style='cursor: default;'>";
	if($page-1>=1){
		 $fanhui .= "<a href='".$url."?page=".($page-1)."&page_size=".$page_size.$urlcanshu."'><span class='icon icon-backward'></span></a>";
	}
	else{
		 $fanhui .= "<span class='icon icon-backward'></span>";
	}
		 $fanhui .= "</td><td class='ui-pg-button ui-disabled'><span class='ui-separator'></span></td>";
		 $fanhui .= "<td>";

        $fanhui .=  "<select class='ui-pg-selbox form-control' name='Types' onchange=\"if (this.options[this.selectedIndex].value!=''){window.open(this.options[this.selectedIndex].value,'_self')};\">";
		for($i=1;$i<=$page_count;$i++){
		  if($page==$i)
          $fanhui .=  "<option value='".$url."?page=".$i."&page_size=".$page_size.$urlcanshu."' selected>".$i."</option>";
		  else
		  $fanhui .=  "<option value='".$url."?page=".$i."&page_size=".$page_size.$urlcanshu."'>".$i."</option>";
        }  
		 $fanhui .= "</select>";

		 $fanhui .= "</td>";
		 
		 $fanhui .= "<td class='ui-pg-button ui-disabled'><span class='ui-separator'></span></td>";
		 $fanhui .= "<td class='ui-pg-button' title='下一页' style='cursor: default;'>";
		 
			  if($page+1>$page_count){	
		 $fanhui .= "<span class='icon icon-forward'></span>";
			  } 
			  else
			  {
		 $fanhui .= "<a href='".$url."?page=".($page+1)."&page_size=".$page_size.$urlcanshu."'><span class='icon icon-forward'></span></a>";
			  } 
		 $fanhui .= "</td><td class='ui-pg-button' title='尾页' style='cursor: default;'>";
		 $fanhui .=   "<a href='".$url."?page=".$page_count."&page_size=".$page_size.$urlcanshu."'><span class='icon icon-step-forward'></span></a>";
		 
		 
		 $fanhui .= "</td>";

		    switch($_REQUEST[page_size]){
			   case "10";
			    $selected1="selected";
			  break; 
			  case "20";
			    $selected2="selected";
			  break; 
			  case "50";
			    $selected3="selected";
			  break;
	 
			  default:
			   $selected2="selected";
			  break;
			  
			}

        $fanhui .=  "<td class='hidden-xs'><select class='ui-pg-selbox form-control' name='Types' onchange=\"if (this.options[this.selectedIndex].value!=''){window.open(this.options[this.selectedIndex].value,'_self')};\">";
        $fanhui .=  "<option value='".$url."?page=".$page."&page_size=10".$urlcanshu."' ".$selected1.">10条</option>";
        $fanhui .=  "<option value='".$url."?page=".$page."&page_size=20".$urlcanshu."' ".$selected2.">20条</option>";
        $fanhui .=  "<option value='".$url."?page=".$page."&page_size=50".$urlcanshu."' ".$selected3.">50条</option>";
 
        $fanhui .= "</select>";

		$fanhui .=  "</td></tr></tbody></table></td><td><div class='ui-paging-info'>".$page." - ".$page_count."　共 ".$rs[total]." 条</div></td>";
		$fanhui .= "</tr></tbody></table></div></div></div>";
		
		 // echo "</form>";	
		 //echo $urlcanshu;
		 return $fanhui;	
	  }
	  	  
}
?>