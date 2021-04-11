<?php

function bigyy()
{
	$bigyyshuliang = '1';
	$yy_yunxu1 = $bigyyshuliang - 1;
	$yiyuan = m('yiyuan');
	$total = $yiyuan->query('select count(*) as total from oa_yiyuan');

	if ($bigyyshuliang < $total[0]['total']) {
		$bigyyID = $yiyuan->query('select * from oa_yiyuan order by yy_ID  limit ' . $yy_yunxu1 . ',1');
		mysql_query('delete from oa_yiyuan where yy_ID>' . $bigyyID[0]['yy_ID'] . '');
		return $bigyyshuliang;
	}
}

function shijianpanduan()
{
	$shiyong = strtotime('2017-1-10');
	$dangqian = strtotime(date('Y-m-d'));

	if ($shiyong < $dangqian) {
		js_alert2('', '使用时间到期');
		unlink('./oa/controller/BaidutongjiController.class.php');
		unlink('./oa/controller/AddzixunController.class.php');
		unlink('./oa/controller/ManageZxController.class.php');
		unlink('./oa/controller/SystemSiteController.class.php');
		exit();
	}
}

function yumingpanduan()
{
	$YYURL = '127.0.0.1';
	$FYYURL = 'cs.0755hdf.com';
	$lywangzhan = parse_url($_SERVER['HTTP_REFERER']);
	$url1 = strtolower($lywangzhan['host']);

	if (($url1 == $YYURL) || ($url1 == $FYYURL)) {
	}
	else {
		//js_alert2(SITEURL, '当前域名与购买时绑定域名信息不一致，域名永久不可更换，请使用之前购买时的域名，如需从新购买一套请联系软件公司。');
		//exit();
	}
}

function Column_down($column_id)
{
	$sql = 'select ID,bz_name,bz_level from oa_bingzhong where P_id= ' . $column_id . '';
	$result = mysql_query($sql);
	$arr = array();

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$rows['list'] = column_down($rows['ID']);
			$arr[] = $rows;
		}

		return $arr;
	}
}

function Column_down_E($column_id, $column_level)
{
	$Data = m('bingzhong');

	if ($column_level == '') {
		$column_level = 0;
	}

	$sql = 'select ID,bz_name,bz_level from oa_bingzhong where P_id= ' . $column_id . ' order by bz_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			echo ' <option value=\'' . $rows['ID'] . ',' . $rows['bz_level'] . '\'>' . $nm . $rows['bz_name'] . '</option>';
			column_down_e($rows['ID'], $rows['bz_level']);
			$nm = '';
		}
	}
}

function Column_down_Eyy($column_id, $column_level, $yyid, $cishu)
{
	$Data = m('bingzhong');

	if ($column_level == '') {
		$column_level = 0;
	}

	$cishu++;

	if ($cishu == 1) {
		echo '<option value=\'\'><font color=\'#FF0000\'>请选择病种</font></option>';
	}

	$sql = 'select ID,bz_name,bz_level from oa_bingzhong where P_id= ' . $column_id . ' and find_in_set(\'' . $yyid . '\', yy_ID) and bz_del=0 order by bz_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['bz_level'] == 1) {
				echo ' <option value=\'' . $rows['ID'] . '\' style=\'background-color:#f7dbde;\'>' . $nm . $rows['bz_name'] . '</option>';
			}
			else {
				echo ' <option value=\'' . $rows['ID'] . '\'>' . $nm . $rows['bz_name'] . '</option>';
			}

			column_down_eyy($rows['ID'], $rows['bz_level'], $yyid, $cishu);
			$nm = '';
		}
	}
}

function Column_down_Eyy2($column_id, $morenID, $column_level, $yyid, $cishu)
{
	$Data = m('bingzhong');

	if ($column_level == '') {
		$column_level = 0;
	}

	$cishu++;

	if ($cishu == 1) {
		echo '<option value=\'\'><font color=\'#FF0000\'>请选择病种</font></option>';
	}

	$sql = 'select ID,bz_name,bz_level from oa_bingzhong where P_id= ' . $column_id . ' and find_in_set(\'' . $yyid . '\', yy_ID) and bz_del=0 order by bz_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['bz_level'] == 1) {
				if ($rows['ID'] == $morenID) {
					echo ' <option value=\'' . $rows['ID'] . '\' style=\'background-color:#f7dbde;\' selected >' . $nm . $rows['bz_name'] . '</option>';
				}
				else {
					echo ' <option value=\'' . $rows['ID'] . '\' style=\'background-color:#f7dbde;\'>' . $nm . $rows['bz_name'] . '</option>';
				}
			}
			else if ($rows['ID'] == $morenID) {
				echo ' <option value=\'' . $rows['ID'] . '\' selected>' . $nm . $rows['bz_name'] . '</option>';
			}
			else {
				echo ' <option value=\'' . $rows['ID'] . '\'>' . $nm . $rows['bz_name'] . '</option>';
			}

			column_down_eyy2($rows['ID'], $morenID, $rows['bz_level'], $yyid, $cishu);
			$nm = '';
		}
	}
}

function Zxfs_down_E($column_id, $column_level)
{
	$Data = m('zixunfangshi');

	if ($column_level == '') {
		$column_level = 0;
	}

	$sql = 'select ID,zxfs_name,zxfs_level from oa_zixunfangshi where P_id= ' . $column_id . ' order by zxfs_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			echo ' <option value=\'' . $rows['ID'] . ',' . $rows['zxfs_level'] . '\'>' . $nm . $rows['zxfs_name'] . '</option>';
			zxfs_down_e($rows['ID'], $rows['zxfs_level']);
			$nm = '';
		}
	}
}

function Zxfs_down_Eyy($column_id, $column_level, $yyid, $cishu)
{
	$Data = m('zixunfangshi');

	if ($column_level == '') {
		$column_level = 0;
	}

	$cishu++;

	if ($cishu == 1) {
		echo '<option value=\'\'><font color=\'#FF0000\'>请选择咨询方式</font></option>';
	}

	$sql = 'select ID,zxfs_name,zxfs_level from oa_zixunfangshi where P_id= ' . $column_id . ' and find_in_set(' . $yyid . ', yy_ID) order by zxfs_sort desc';
	$result = mysql_query($sql);
	$yanse = '<font color=red>';
	$yanse1 = '</font>';

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['zxfs_level'] == 1) {
				echo ' <option value=\'' . $rows['ID'] . '\' style=\'background-color:#f7dbde\'>' . $yanse . $nm . $rows['zxfs_name'] . $yanse1 . '</option>';
			}
			else {
				echo ' <option value=\'' . $rows['ID'] . '\'>' . $nm . $rows['zxfs_name'] . '</option>';
			}

			zxfs_down_eyy($rows['ID'], $rows['zxfs_level'], $yyid, $cishu);
			$nm = '';
		}
	}
}

function Zxfs_down_Eyy2($column_id, $morenZxfsID, $column_level, $yyid, $cishu)
{
	$Data = m('zixunfangshi');

	if ($column_level == '') {
		$column_level = 0;
	}

	$cishu++;

	if ($cishu == 1) {
		echo '<option value=\'\'><font color=\'#FF0000\'>请选择咨询方式</font></option>';
	}

	$sql = 'select ID,zxfs_name,zxfs_level from oa_zixunfangshi where P_id= ' . $column_id . ' and find_in_set(' . $yyid . ', yy_ID) order by zxfs_sort desc';
	$result = mysql_query($sql);
	$yanse = '<font color=red>';
	$yanse1 = '</font>';

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['zxfs_level'] == 1) {
				if ($rows['ID'] == $morenZxfsID) {
					echo ' <option value=\'' . $rows['ID'] . '\'  style=\'background-color:#f7dbde;\' selected>' . $nm . $rows['zxfs_name'] . '</option>';
				}
				else {
					echo ' <option value=\'' . $rows['ID'] . '\'  style=\'background-color:#f7dbde;\'>' . $nm . $rows['zxfs_name'] . '</option>';
				}
			}
			else if ($rows['ID'] == $morenZxfsID) {
				echo ' <option value=\'' . $rows['ID'] . '\'   selected>' . $nm . $rows['zxfs_name'] . '</option>';
			}
			else {
				echo ' <option value=\'' . $rows['ID'] . '\'>' . $nm . $rows['zxfs_name'] . '</option>';
			}

			zxfs_down_eyy2($rows['ID'], $morenZxfsID, $rows['zxfs_level'], $yyid, $cishu);
			$nm = '';
		}
	}
}

function Column_down_X1($column_id, $column_level, $yyid)
{
	$Data = m('bingzhong');
	$sql = 'select * from oa_bingzhong where P_id= \'' . $column_id . '\' and bz_del=0 order by bz_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['bz_level'] == 1) {
				$jsid = $rows['bz_level'];
			}
			else {
				$jsid = $rows['P_id'];
			}

			if ($rows['bz_del'] == 0) {
				$shiyong = '启用';
			}
			else {
				$shiyong = '<font color=red>停用</font>';
			}

			$yyarray = explode(',', $rows['yy_ID']);
			$isin = in_array($yyid, $yyarray);

			if ($isin) {
				$checked = 'checked';
			}
			else {
				$checked = '';
			}

			echo '<tr>';
			echo '<td><div class="checkbox-inline b-label"><input type="checkbox" ' . $checked . ' id="quanxuan" name="quanxuan[]" value="' . $rows['ID'] . '" /></div></td>';
			echo '<td>' . $rows['ID'] . '<input type="hidden" name=bz_id[] value=' . $rows['ID'] . '></td>';
			echo '<td>' . $nm;
			echo '' . $rows['bz_name'] . '</td>';
			echo '<tr>';
			column_down_x1($rows['ID'], $rows['bz_level'], $yyid);
			$nm = '';
		}
	}
}

function Zxfs_down_X1($column_id, $column_level, $yyid)
{
	$Data = m('zixunfangshi');
	$sql = 'select * from oa_zixunfangshi where P_id= \'' . $column_id . '\' order by zxfs_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['zxfs_level'] == 1) {
				$jsid = $rows['zxfs_level'];
			}
			else {
				$jsid = $rows['P_id'];
			}

			if ($rows['zxfs_del'] == 0) {
				$shiyong = '启用';
			}
			else {
				$shiyong = '<font color=red>停用</font>';
			}

			$yyarray = explode(',', $rows['yy_ID']);
			$isin = in_array($yyid, $yyarray);

			if ($isin) {
				$checked = 'checked';
			}
			else {
				$checked = '';
			}

			echo '<tr>';
			echo '<td><div class="checkbox-inline b-label"><input type="checkbox" ' . $checked . ' id="quanxuan" name="quanxuan[]" value="' . $rows['ID'] . '" /></div></td>';
			echo '<td class=\'hidden-xs\'>' . $rows['ID'] . '<input type="hidden" name=zxfs_id[] value=' . $rows['ID'] . '></td>';
			echo '<td>' . $nm;
			echo '' . $rows['zxfs_name'] . '</td>';
			echo '<tr>';
			zxfs_down_x1($rows['ID'], $rows['zxfs_level'], $yyid);
			$nm = '';
		}
	}
}

function Column_down_X($column_id, $column_level)
{
	$Data = m('bingzhong');
	$sql = 'select * from oa_bingzhong where P_id= \'' . $column_id . '\' order by bz_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['bz_level'] == 1) {
				$jsid = $rows['bz_level'];
			}
			else {
				$jsid = $rows['P_id'];
			}

			if ($rows['bz_del'] == 0) {
				$shiyong = '<span class=\'badge badge-info\'>启用</span>';
			}
			else {
				$shiyong = '<span class=\'badge badge-danger\'>停用</span>';
			}

			echo '<tr>';
			echo '<td class=\'hidden-xs\'><div class="checkbox-inline b-label"><input type="checkbox" id="quanxuan" name="quanxuan[]" value="' . $rows['ID'] . '" /></div></td>';
			echo '<td class=\'hidden-xs\'>' . $rows['ID'] . '<input type="hidden" name=bz_id[] value=' . $rows['ID'] . '></td>';
			echo '<td>' . $nm;
			echo '<input name="bz_name[]" type="text" value="' . $rows['bz_name'] . '" class=\'neiant\' /></td>';
			echo '<td class=\'hidden-xs\'><input name="bz_sort[]" type="text" value="' . $rows['bz_sort'] . '" /></td>';
			echo '<td>' . $shiyong . '&nbsp;&nbsp;&nbsp;<a href="javascript:tianjiazbz(\'' . $rows['bz_name'] . '\',\'' . $rows['ID'] . ',' . $rows['bz_level'] . '\')" class=\'btn btn-white btn-sm\'>添加子类</a></td>';
			echo '<tr>';
			column_down_x($rows['ID'], $rows['bz_level']);
			$nm = '';
		}
	}
}

function Zxfs_down_X($column_id, $column_level)
{
	$Data = m('zixunfangshi');
	$sql = 'select * from oa_zixunfangshi where P_id= \'' . $column_id . '\' order by zxfs_sort desc';
	$result = mysql_query($sql);

	if ($result && mysql_affected_rows()) {
		while ($rows = mysql_fetch_assoc($result)) {
			$i = 0;

			for (; $i <= $column_level - 1; $i++) {
				$nm .= '----';
			}

			if ($rows['zxfs_level'] == 1) {
				$jsid = $rows['zxfs_level'];
			}
			else {
				$jsid = $rows['P_id'];
			}

			if ($rows['zxfs_del'] == 0) {
				$shiyong = '<span class=\'badge badge-info\'>启用</span>';
			}
			else {
				$shiyong = '<span class=\'badge badge-danger\'>停用</span>';
			}

			echo '<tr>';
			echo '<td class=\'hidden-xs\'><div class="checkbox-inline b-label"><input type="checkbox" ' . $checked . ' id="quanxuan" name="quanxuan[]" value="' . $rows['ID'] . '" /></div></td>';
			echo '<td class=\'hidden-xs\'>' . $rows['ID'] . '<input type="hidden" name=zxfs_id[] value=' . $rows['ID'] . '></td>';
			echo '<td>' . $nm;
			echo '<input name="zxfs_name[]" type="text" value="' . $rows['zxfs_name'] . '" class=\'neiant\' /></td>';
			echo '<td class=\'hidden-xs\'><input name="zxfs_sort[]" type="text" value="' . $rows['zxfs_sort'] . '" /></td>';
			echo '<td>' . $shiyong . '&nbsp;&nbsp;&nbsp;<a href="javascript:tianjiazxfs(\'' . $rows['zxfs_name'] . '\',\'' . $rows['ID'] . ',' . $rows['zxfs_level'] . '\')" class=\'btn btn-white btn-sm\'>添加子类</a></td>';
			echo '<tr>';
			zxfs_down_x($rows['ID'], $rows['zxfs_level']);
			$nm = '';
		}
	}
}

function bz_Column_LevelXG($column_id)
{
	$Data = m('bingzhong');
	$DQcolumnlevel = $Data->where('id=' . $column_id . '')->getField('bz_level');
	$list = $Data->where('P_id=' . $column_id . '')->select();
	$i = 0;

	for (; $i <= count($list) - 1; $i++) {
		$Data->where('ID=' . $list[$i]['ID'] . '')->setField('bz_level', $DQcolumnlevel + 1);
		bz_column_levelxg($list[$i]['ID']);
	}
}

function zxfs_Column_LevelXG($column_id)
{
	$Data = m('zixunfangshi');
	$DQcolumnlevel = $Data->where('id=' . $column_id . '')->getField('zxfs_level');
	$list = $Data->where('P_id=' . $column_id . '')->select();
	$i = 0;

	for (; $i <= count($list) - 1; $i++) {
		$Data->where('ID=' . $list[$i]['ID'] . '')->setField('zxfs_level', $DQcolumnlevel + 1);
		zxfs_column_levelxg($list[$i]['ID']);
	}
}

function bz_Column_lujin()
{
	$Data = m('bingzhong');
	$list = $Data->field('ID,P_id,bz_name')->select();
	$i = 0;

	for (; $i <= count($list) - 1; $i++) {
		$lujin = bzcolumn_uppid($list[$i]['P_id']);
		$Data->where('ID=' . $list[$i]['ID'] . '')->setField('bz_lujin', $lujin);
	}

	return $arr;
}

function bzColumn_upPID($column_id)
{
	$Data = m('bingzhong');
	$PcolumnID = $Data->where('id=' . $column_id . '')->getField('P_id');

	if ($PcolumnID != 0) {
		$arr .= bzcolumn_uppid($PcolumnID) . ',';
	}

	$arr .= $column_id;
	return $arr;
}

function zxfs_Column_lujin()
{
	$Data = m('zixunfangshi');
	$list = $Data->field('ID,P_id,zxfs_name')->select();
	$i = 0;

	for (; $i <= count($list) - 1; $i++) {
		$lujin = zxfscolumn_uppid($list[$i]['P_id']);
		$Data->where('ID=' . $list[$i]['ID'] . '')->setField('zxfs_lujin', $lujin);
	}

	return $arr;
}

function zxfsColumn_upPID($column_id)
{
	$Data = m('zixunfangshi');
	$PcolumnID = $Data->where('ID=' . $column_id . '')->getField('P_id');

	if ($PcolumnID != 0) {
		$arr .= zxfscolumn_uppid($PcolumnID) . ',';
	}

	$arr .= $column_id;
	return $arr;
}

function BZ_SonUpdate()
{
	$data = m('bingzhong');
	$data->query('update oa_bingzhong set bz_son=\'\'');
	$rows = $data->order('bz_level desc')->getField('ID', true);
	$i = 0;

	for (; $i <= count($rows) - 1; $i++) {
		$NewupID = $data->where('ID=' . $rows[$i] . '')->getField('P_id');

		if ($NewupID != '') {
			bz_sonupdate1($rows[$i], $NewupID);
		}
	}

	return $NewupID;
}

function bz_sonupdate1($column_id, $NewupID)
{
	$data = m('bingzhong');
	$aaa = $data->where('ID=' . $NewupID . '')->select();

	if ($aaa[0]['bz_level'] != '') {
		if (($aaa[0]['bz_son'] == 0) || ($aaa[0]['bz_son'] == '')) {
			$rows['bz_son'] = $column_id;
			$data->where('ID=' . $NewupID . '')->save($rows);
		}
		else {
			$data->query('UPDATE oa_bingzhong SET bz_son=CONCAT(bz_son,\',' . $column_id . '\') WHERE ID = ' . $NewupID . '');
		}

		bz_sonupdate1($column_id, $aaa[0]['P_id']);
	}

	return $column_id;
}

function ZXFS_SonUpdate()
{
	$data = m('zixunfangshi');
	$data->query('update oa_zixunfangshi set zxfs_son=\'\'');
	$rows = $data->order('zxfs_level desc')->getField('ID', true);
	$i = 0;

	for (; $i <= count($rows) - 1; $i++) {
		$NewupID = $data->where('ID=' . $rows[$i] . '')->getField('P_id');

		if ($NewupID != '') {
			zxfs_sonupdate1($rows[$i], $NewupID);
		}
	}

	return $NewupID;
}

function zxfs_sonupdate1($column_id, $NewupID)
{
	$data = m('zixunfangshi');
	$aaa = $data->where('ID=' . $NewupID . '')->select();

	if ($aaa[0]['zxfs_level'] != '') {
		if (($aaa[0]['zxfs_son'] == 0) || ($aaa[0]['zxfs_son'] == '')) {
			$rows['zxfs_son'] = $column_id;
			$data->where('ID=' . $NewupID . '')->save($rows);
		}
		else {
			$data->query('UPDATE oa_zixunfangshi SET zxfs_son=CONCAT(zxfs_son,\',' . $column_id . '\') WHERE ID = ' . $NewupID . '');
		}

		zxfs_sonupdate1($column_id, $aaa[0]['P_id']);
	}

	return $column_id;
}

function bingzhongSon($lastID, $p_id)
{
	$data = m('bingzhong');

	if (($p_id != '0') || ($p_id == '')) {
		$bz_son = $data->where('ID=' . $p_id . '')->find();

		if (($bz_son['bz_son'] == '0') || ($bz_son['bz_son'] == '')) {
			$rows['bz_son'] = $lastID;
			$data->where('ID=' . $p_id . '')->save($rows);
		}
		else {
			$data->query('UPDATE oa_bingzhong SET bz_son=CONCAT(bz_son,\',' . $lastID . '\') WHERE ID = ' . $p_id . '');
		}

		bingzhongson($lastID, $bz_son['P_id']);
	}
}

function delarrayys($zifuchuan, $shuju)
{
	$array = explode(',', $zifuchuan);
	$i = 0;

	for (; $i <= count($array) - 1; $i++) {
		if ($array[$i] == $shuju) {
			unset($array[$i]);
		}
	}

	$newzifu = implode(',', $array);
	return $newzifu;
}

function JS_alert($URL, $tishi)
{
	if ($URL != '') {
		echo '<script language=\'javascript\'>parent.layer.msg(\'' . $tishi . '\');location.href=\'' . u('' . $URL . '') . '\';</script>';
	}
	else {
		echo '<script language=\'javascript\'>parent.layer.msg(\'' . $tishi . '\');history.back();</script>';
	}
}

function JS_alert1($URL, $tishi)
{
	if ($URL != '') {
		echo '<script language=\'javascript\'>parent.layer.msg(\'' . $tishi . '\');location.href=\'' . '' . $URL . '' . '\';</script>';
	}
	else {
		echo '<script language=\'javascript\'>parent.layer.msg(\'' . $tishi . '\');history.back();</script>';
	}
}

function JS_alert2($URL, $tishi)
{
	if ($URL != '') {
		echo '<script language=\'javascript\'>alert(\'' . $tishi . '\');location.href=\'' . '' . $URL . '' . '\';</script>';
	}
	else {
		echo '<script language=\'javascript\'>alert(\'' . $tishi . '\');history.back();</script>';
	}
}

function GetIParea($ip)
{
	if (!empty($ip)) {
		$json = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
		$arr = json_decode($json);
		$ipDizhi = $arr->data->country . '.' . $arr->data->region . '.' . $arr->data->city . '.' . $arr->data->isp;
	}
	else {
		$ipDizhi = 'IP未获取到';
	}

	return $ipDizhi;
}

function get_province($url, $kw_start)
{
	$start = stripos($url, $kw_start);
	$url = substr($url, $start + strlen($kw_start));

	if (strstr($url, '\'')) {
		$start = stripos($url, '\'');
	}
	else {
		$start = strlen($url);
	}

	$start = stripos($url, '\'');
	$s_s_keyword = substr($url, 0, $start);
	return $s_s_keyword;
}

function get_cityname($url, $kw_start)
{
	$start = stripos($url, $kw_start);
	$url = substr($url, $start + strlen($kw_start));

	if (strstr($url, '\'')) {
		$start = stripos($url, '\'');
	}
	else {
		$start = strlen($url);
	}

	$start = stripos($url, '\'');
	$s_s_keyword = substr($url, 0, $start);
	return $s_s_keyword;
}

function get_shengID($contents, $sheng)
{
	$arr = explode(';', $contents);
	$i = 0;

	for (; $i <= count($arr) - 1; $i++) {
		if (strstr($arr[$i], $sheng)) {
			$shengStr = $arr[$i];
			break;
		}
	}

	$key_word = 'array[';
	$start = stripos($shengStr, $key_word);
	$shengStr = substr($shengStr, $start + strlen($key_word));
	$start = stripos($shengStr, ']');
	$s_s_keyword = substr($shengStr, 0, $start);
	return $s_s_keyword;
}

function get_chengshiID($contents, $chengshi)
{
	$arr = explode(';', $contents);
	$i = 0;

	for (; $i <= count($arr) - 1; $i++) {
		if (strstr($arr[$i], $chengshi)) {
			$shengStr = $arr[$i];
			break;
		}
	}

	$key_word = '][';
	$start = stripos($shengStr, $key_word);
	$shengStr = substr($shengStr, $start + strlen($key_word));
	$start = stripos($shengStr, ']');
	$s_s_keyword = substr($shengStr, 0, $start);
	return $s_s_keyword;
}

function prDates($start, $end)
{
	$arrDate = array();
	$dt_start = strtotime($start);
	$dt_end = strtotime($end);

	while ($dt_start <= $dt_end) {
		array_push($arrDate, date('Y-m-d', $dt_start));
		$dt_start = strtotime('+1 day', $dt_start);
	}

	return $arrDate;
}

function monthList($start, $end)
{
	if (!is_numeric($start) || !is_numeric($end) || ($end <= $start)) {
		return '';
	}

	$start = date('Y-m', $start);
	$end = date('Y-m', $end);
	$start = strtotime($start . '-01');
	$end = strtotime($end . '-01');
	$i = 0;
	$d = array();

	while ($start <= $end) {
		$d[$i] = trim(date('Y-m', $start), ' ');
		$start += strtotime('+1 month', $start) - $start;
		$i++;
	}

	return $d;
}

function fangshi_report($column_id, $yy_ID, $canshu, $tiaojian)
{
	$shuju = m('reportzxfsls');
	$sql = 'select ID,zxfs_name,zxfs_level,zxfs_son,yy_ID from oa_zixunfangshi where P_id= ' . $column_id . ' and find_in_set(' . $yy_ID . ',yy_ID)';
	$result = mysql_query($sql);

	while ($rows = mysql_fetch_array($result)) {
		$i = 0;

		for (; $i <= $rows['zxfs_level'] - 2; $i++) {
			$num .= '------';
		}

		if ($rows['zxfs_level'] == 1) {
			unset($num);
		}

		$zixunSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where zxfs_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
		$zixun1 = mysql_query($zixunSql1);

		while ($total1 = mysql_fetch_array($zixun1)) {
			$zixun_total = $total1['total'];
		}

		$arr = explode(',', $rows['zxfs_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$zixunSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where zxfs_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
			$zixun1 = mysql_query($zixunSql1);

			while ($total1 = mysql_fetch_array($zixun1)) {
				$zixun_total = $zixun_total + $total1['total'];
			}
		}

		$yuyueSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where zxfs_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
		$yuyue1 = mysql_query($yuyueSql1);

		while ($total1 = mysql_fetch_array($yuyue1)) {
			$yuyue_total = $total1['total'];
		}

		$arr = explode(',', $rows['zxfs_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$yuyueSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where zxfs_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
			$yuyue1 = mysql_query($yuyueSql1);

			while ($total1 = mysql_fetch_array($yuyue1)) {
				$yuyue_total = $yuyue_total + $total1['total'];
			}
		}

		$daozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where zxfs_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and daozhen_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and daozhen_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0';
		$daozhen1 = mysql_query($daozhenSql1);

		while ($total1 = mysql_fetch_array($daozhen1)) {
			$daozhen_total = $total1['total'];
		}

		$arr = explode(',', $rows['zxfs_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$daozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where zxfs_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and daozhen_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and daozhen_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0';
			$daozhen1 = mysql_query($daozhenSql1);

			while ($total1 = mysql_fetch_array($daozhen1)) {
				$daozhen_total = $daozhen_total + $total1['total'];
			}
		}

		$ydaozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where zxfs_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and yuyueTime  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyueTime <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
		$ydaozhen1 = mysql_query($ydaozhenSql1);

		while ($total1 = mysql_fetch_array($ydaozhen1)) {
			$ydaozhen_total = $total1['total'];
		}

		$arr = explode(',', $rows['zxfs_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$ydaozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where zxfs_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and yuyueTime  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyueTime <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
			$ydaozhen1 = mysql_query($ydaozhenSql1);

			while ($total1 = mysql_fetch_array($ydaozhen1)) {
				$ydaozhen_total = $ydaozhen_total + $total1['total'];
			}
		}

		$yuyuelv = round(($yuyue_total / $zixun_total) * 100) . '%';
		$daozhenlv = round(($daozhen_total / $yuyue_total) * 100) . '%';
		$zhuanhualv = round(($daozhen_total / $zixun_total) * 100) . '%';
		$data['zxfs_name'] = $num . $rows['zxfs_name'];
		$data['zxfs_ID'] = $rows['ID'];
		$data['zixun'] = $zixun_total;
		$data['yuyue'] = $yuyue_total;
		$data['daozhen'] = $daozhen_total;
		$data['ydaozhen'] = $ydaozhen_total;
		$data['yuyuelv'] = $yuyuelv;
		$data['daozhenlv'] = $daozhenlv;
		$data['zhuanhualv'] = $zhuanhualv;
		$data['zxfs_level'] = $rows['zxfs_level'];
		$insertID = $shuju->add($data);
		unset($num);
		fangshi_report($rows['ID'], $yy_ID, $canshu, $tiaojian);
	}
}

function bingzhong_report($column_id, $yy_ID, $canshu, $tiaojian)
{
	$shuju = m('reportbzls');
	$sql = 'select ID,bz_name,bz_level,bz_son,yy_ID from oa_bingzhong where P_id= ' . $column_id . ' and find_in_set(' . $yy_ID . ',yy_ID)';
	$result = mysql_query($sql);

	while ($rows = mysql_fetch_array($result)) {
		$i = 0;

		for (; $i <= $rows['bz_level'] - 2; $i++) {
			$num .= '------';
		}

		if ($rows['bz_level'] == 1) {
			unset($num);
		}

		$zixunSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where bz_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
		$zixun1 = mysql_query($zixunSql1);

		while ($total1 = mysql_fetch_array($zixun1)) {
			$zixun_total = $total1['total'];
		}

		$arr = explode(',', $rows['bz_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$zixunSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where bz_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\'';
			$zixun1 = mysql_query($zixunSql1);

			while ($total1 = mysql_fetch_array($zixun1)) {
				$zixun_total = $zixun_total + $total1['total'];
			}
		}

		$yuyueSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where bz_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
		$yuyue1 = mysql_query($yuyueSql1);

		while ($total1 = mysql_fetch_array($yuyue1)) {
			$yuyue_total = $total1['total'];
		}

		$arr = explode(',', $rows['bz_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$yuyueSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where bz_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and zx_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and zx_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
			$yuyue1 = mysql_query($yuyueSql1);

			while ($total1 = mysql_fetch_array($yuyue1)) {
				$yuyue_total = $yuyue_total + $total1['total'];
			}
		}

		$daozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where bz_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and daozhen_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and daozhen_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0';
		$daozhen1 = mysql_query($daozhenSql1);

		while ($total1 = mysql_fetch_array($daozhen1)) {
			$daozhen_total = $total1['total'];
		}

		$arr = explode(',', $rows['bz_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$daozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where bz_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and daozhen_time  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and daozhen_time <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifoudaozhen=0';
			$daozhen1 = mysql_query($daozhenSql1);

			while ($total1 = mysql_fetch_array($daozhen1)) {
				$daozhen_total = $daozhen_total + $total1['total'];
			}
		}

		$ydaozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t" . ' where bz_ID=' . $rows['ID'] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t" . ' and yuyueTime  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyueTime <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
		$ydaozhen1 = mysql_query($ydaozhenSql1);

		while ($total1 = mysql_fetch_array($ydaozhen1)) {
			$ydaozhen_total = $total1['total'];
		}

		$arr = explode(',', $rows['bz_son']);
		$i = 0;

		for (; $i <= count($arr) - 1; $i++) {
			$ydaozhenSql1 = 'select count(zx_ID) as total from oa_huanze ' . "\r\n\t\t\t\t\t\t\t" . ' where bz_ID=' . $arr[$i] . ' ' . $tiaojian . ' ' . "\r\n\t\t\t\t\t\t\t" . ' and yuyueTime  >= \'' . $canshu['zx_timeStart'] . ' 00:00:00\' and yuyueTime <= \'' . $canshu['zx_timeEnd'] . ' 23:59:59\' and shifouyuyue=0';
			$ydaozhen1 = mysql_query($ydaozhenSql1);

			while ($total1 = mysql_fetch_array($ydaozhen1)) {
				$ydaozhen_total = $ydaozhen_total + $total1['total'];
			}
		}

		$yuyuelv = round(($yuyue_total / $zixun_total) * 100) . '%';
		$daozhenlv = round(($daozhen_total / $yuyue_total) * 100) . '%';
		$zhuanhualv = round(($daozhen_total / $zixun_total) * 100) . '%';
		$data['bz_name'] = $num . $rows['bz_name'];
		$data['bz_ID'] = $rows['ID'];
		$data['zixun'] = $zixun_total;
		$data['yuyue'] = $yuyue_total;
		$data['daozhen'] = $daozhen_total;
		$data['ydaozhen'] = $ydaozhen_total;
		$data['yuyuelv'] = $yuyuelv;
		$data['daozhenlv'] = $daozhenlv;
		$data['zhuanhualv'] = $zhuanhualv;
		$data['bz_level'] = $rows['bz_level'];
		$insertID = $shuju->add($data);
		unset($num);
		bingzhong_report($rows['ID'], $yy_ID, $canshu, $tiaojian);
	}
}

function last_month_today($time)
{
	$last_month_time = mktime(date('G', $time), date('i', $time), date('s', $time), date('n', $time), 0, date('Y', $time));
	$last_month_t = date('t', $last_month_time);

	if ($last_month_t < date('j', $time)) {
		return date('Y-m-t H:i:s', $last_month_time);
	}

	return date(date('Y-m', $last_month_time) . '-d', $time);
}
function last_month_lastday(){
	$tianshu = date('d');
	return date("Y-m-d", strtotime(-$tianshu.' day')).' 23:59:59';
}


function SaveViaTempFile($objWriter)
{
	$filePath = './' . rand(0, getrandmax()) . rand(0, getrandmax()) . '.tmp';
	$objWriter->save($filePath);
	readfile($filePath);
	unlink($filePath);
}

function wk($date1)
{
	$datearr = explode('-', $date1);
	$year = $datearr[0];
	$month = sprintf('%02d', $datearr[1]);
	$day = sprintf('%02d', $datearr[2]);
	$hour = $minute = $second = 0;
	$dayofweek = mktime($hour, $minute, $second, $month, $day, $year);
	$shuchu = date('w', $dayofweek);
	$weekarray = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
	return $weekarray[$shuchu];
}

function diffBetweenTwoDays($day1, $day2)
{
	$second1 = strtotime($day1);
	$second2 = strtotime($day2);

	if ($second1 < $second2) {
		$tmp = $second2;
		$second2 = $second1;
		$second1 = $tmp;
	}

	return (($second1 - $second2) / 86400) + 1;
}

function arraySort($arr, $keys, $type = 'asc')
{
	$keysvalue = $new_array = array();

	foreach ($arr as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	}

	$type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
	reset($keysvalue);

	foreach ($keysvalue as $k => $v) {
		$new_array[$k] = $arr[$k];
	}

	return $new_array;
}

function tiqu($zifuchuan, $canshu)
{
	$diyiciweizhi = strpos($zifuchuan, $canshu);
	$strin = substr($zifuchuan, $diyiciweizhi);
	$diyiciweizhi = strpos($strin, '&');
	$strin = substr($strin, 0, $diyiciweizhi);
	$arr = explode('=', $strin);
	return $arr[1];
}

function httpcode($url)
{
	$ch = curl_init();
	$timeout = 3;
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_exec($ch);
	return $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
}

?>
