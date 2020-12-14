<?php
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true); 

// 定义应用目录
define('APP_PATH','./');
//define('BIND_MODULE','OA'); // 默认模块

//定义PUBLIC目录
define('SITE_URL','/');
define('CSS_URL',SITE_URL.'public/css');
define('IMG_URL',SITE_URL.'public/img');
define('JS_URL',SITE_URL.'public/js');
define('FENYE_URL',SITE_URL.'index.php/Oa/');
define('ED_URL',SITE_URL.'editor/');


//定义当前控制器URL
define('DQURL',"http://".$_SERVER['HTTP_HOST'].FENYE_URL."");
define('SITEURL',"http://".$_SERVER['HTTP_HOST']."");

// 引入ThinkPHP入口文件
require 'ThinkPHP3.2.2/ThinkPHP.php';



