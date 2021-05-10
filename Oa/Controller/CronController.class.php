<?php

namespace OA\Controller;

use OA\Think;
use Think\Cache\Driver\Memcache;


class CronController
{
    public function qxstain()
    {
        $managezx = m('managezx');
        $application = m('application');
        $data['shifoubaoliu'] = 0;
        $appdata['status'] = 0;
        try {
            m()->startTrans();
            $zxids = array();
            $zxidsstr = '';
            //审核时间到今天大雨两个月的所有审核通过的保留客户id
            $zxre = $application
                ->where('status=2 AND DATE_SUB(CURDATE(), INTERVAL 2 MONTH) > date(fback_time)')
                ->select();
//            echo M()->getLastSql();die();
            //客户id
            foreach ($zxre as $k) {
                $zxids[] = $k['zx_ID'];
            }
            if (!empty($zxids)) {
                $zxidsstr = implode(',', $zxids);
                $re = $application
                    ->where('status=2 AND zx_ID in(' . $zxidsstr . ')')
                    ->save($appdata);
                $re1 = $managezx
                    ->where('shifoubaoliu=1 AND  zx_ID in(' . $zxidsstr . ')')
                    ->save($data);
                if ($re && $re1) {
                    m()->commit();
                    $content = date('Y-m-d H:i:s') . "自动任务执行，修改客户id为" . $zxidsstr . "\n";
                } else {
                    m()->rollback();
                    $content = date('Y-m-d H:i:s') . "自动任务执行失败，修改客户为空\n";
                }
            } else {
                $content = date('Y-m-d H:i:s') . "自动任务执行，修改客户为空\n";
            }
        } catch (\Exception $e) {
            m()->rollback();
            $content = date('Y-m-d H:i:s') . "自动任务执行错误，修改客户为空\n";
        }
        $url = $_SERVER['DOCUMENT_ROOT'] . '/Runtime/Logs/stain.txt';   //定义创建路径
        $file = fopen($url, "a+");
        fwrite($file, $content);
        fclose($file);
    }
}

?>
