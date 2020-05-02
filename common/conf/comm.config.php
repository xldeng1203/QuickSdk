<?php
/**
@desciption: 公共模块配置文件
*/
define('COMM_DEBUG',true); //开启调试模式
define('COMM_BENCHMARK',true); //开启性能测试模式

//=======================每次开服需修改的配置文件=============================

define('API_MODE','521g');      // API模式

define('MD5_KEY','ucb_s1@$asimov#^#'); //客户端接口MD5加密KEY，与客户端的offlinekey对应

define('GETCARD',2);                                  //新手卡领取类型,1：后台生成之后发放给平台；2：平台自己根据玩家的账号生成32位MD5串

define('RATE',10);                                    //RMB兑换YB兑率

define('PLATNAME','ucb');                   //平台名

define('SERVERNAME','s1');                 //服务器ID

define('LOGINKEY','b4c3cb14386a5048bd4184cc30bcb60e');                //登录KEY  

define('PAYKEY','');            //充值KEY

define('OFFICIALURL','');       //平台官网

//define('PATH_ADMIN_LOG','/xxqy/agent/xxqy_ucb_s1/data/admin/log/');                      //后台日志路径
define('PATH_ADMIN_LOG','c:/xxqy_ucb_s1/log/');                      //后台日志路径
define('PATH_ADMIN_CONFIG','/xxqy/agent/xxqy_ucb_s1/data/admin/config/');                    //后台临时路径
define('PATH_SERVER_LOG','/xxqy/agent/xxqy_ucb_s1/data/server/log/');                    //服务器日志路径
define('PATH_SERVER_LOG_BACKUP','/xxqy/agent/xxqy_ucb_s1/backup/server/log/');      //服务器日志备份路径
define('PATH_SERVER_SCRIPT','/xxqy/agent/xxqy_ucb_s1/script/');              //服务器脚本路径
define('PATH_LOCAL_GAME_CONFIG','/assets/config/config/zh_CN/');      //游戏配置路径

define('GAME_CONFIG_CDN_URL','http://119.146.200.235/xxqy_ucb_s1');      //游戏配置-CDN URL
define('GAME_CONFIG_GATEWAY_NUM','1');     //游戏配置-gateway进程数
define('GAME_CONFIG_GAMEWORLD_NUM','2'); //游戏配置-gameword进程数

//==========================================================================
