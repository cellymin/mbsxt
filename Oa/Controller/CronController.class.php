<?php

namespace OA\Controller;

use OA\Think;


class CronController
{
    public function qxstain()
    {
        $managezx = m('managezx');
        $data['shifoudaozhen'] = 1;
        m()->startTrans();
        $zxids = array();
        $zxidsstr = '';
        $zxre = $managezx
            ->where('shifoudaozhen=2 AND DATE_SUB(CURDATE(), INTERVAL 3 MONTH) > date(zx_time)')
            ->select();
        //客户id
        foreach ($zxre as $k) {
            $zxids[] = $k['zx_ID'];
        }
        if(!empty($zxids)){
            $zxidsstr = implode(',',$zxids);
        }else{
        }
        $re = $managezx
            ->where('shifoudaozhen=2 AND DATE_SUB(CURDATE(), INTERVAL 3 MONTH) > date(zx_time)')
            ->save($data);
        if($re){
            m()->commit();
            $content = date('Y-m-d H:i:s')."自动任务执行，修改客户id为".$zxidsstr."\n";
        }else{
            m()->rollback();
            $content = date('Y-m-d H:i:s')."自动任务执行，修改客户为空";
        }
        $url = $_SERVER['DOCUMENT_ROOT']. '/Runtime/Logs/stain.txt';   //定义创建路径
        $file = fopen($url, "a+");
        fwrite($file, $content);
        fclose($file);
	}
}

?>
