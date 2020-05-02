<?php
header('Content-Type: text/html; charset=utf-8');//字符集

date_default_timezone_set ( 'PRC' );//时区
error_reporting(E_ERROR);
ini_set('display_startup_errors', 0); 
ini_set('display_errors', 1); 
ini_set('session.gc_maxlifetime', 86400);

//session cookie does not expire until the browser is closed
ini_set('session.cookie_lifetime', 0);
if (version_compare ( PHP_VERSION, '5.3.0' ) < 0) {
	set_magic_quotes_runtime ( 0 );
}

// 根路径
define('ROOT_DIR', dirname(dirname(__FILE__)).'/');

// 公共目录
define('COM_DIR', str_replace('admintool','common',ROOT_DIR));

define('CHECK_IP',1);//是否开启IP检测：1：开启0：关闭用于限制北京IP登录，具体原因不详

// global路径
define("Global_DIR", ROOT_DIR . 'global/');

// class路径
define("CLS_DIR", ROOT_DIR . 'cls/');

// lib路径
define("LIB_DIR", ROOT_DIR . 'lib/');

// 模板库路径
define("LIB_DIR_TEMP", LIB_DIR . 'smarty/');

// API接口路径
define("API_DIR", ROOT_DIR. 'api/');

// 日志路径
define("LOG_DIR", ROOT_DIR. 'log/');

//代码目录
define('SOURCE_DIR', ROOT_DIR.'source/');

//页面模板目录
define('TPL_DIR', ROOT_DIR.'templates/');

//XML缓存文件多少更新一次,按秒计算
define('XML_UPDATE_TIME',604800);

//XML缓存文件多久没有访问删除,按秒计算
define('XML_DELETE_TIME',604800);

//XML缓存文件多久去检查删除无用文件
define('XML_SEARCH_TIME',604800);

set_include_path(PATH_SEPARATOR.COM_DIR.PATH_SEPARATOR.get_include_path());

include_once(COM_DIR.'common.php');

session_start();

//默认语言
if(!defined('DEFAULT_LANG')){
	if(!file_exists(Global_DIR.'configure/language.'.$_SESSION['uid'].'.inc')){
	   $dlang = file_get_contents(Global_DIR.'configure/language.inc');
	}else{
	   $dlang = file_get_contents(Global_DIR.'configure/language.'.$_SESSION['uid'].'.inc');
	}
	define('DEFAULT_LANG', $dlang);
}

//默认语言包目录
define('LANG_DIR', ROOT_DIR.'language/'.DEFAULT_LANG.'/');

// global带语言路径
define("Global_LANG_DIR", ROOT_DIR . 'global/'.DEFAULT_LANG.'/');

//当前时间
define('TIME',$_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time() );

//自动转义状态
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc()); 

//获取服务器主机
define("PUBLICWEBROOT",'http://'.$_SERVER['SERVER_NAME']."/");
define("HOMEURL", 'http://'.$_SERVER["HTTP_HOST"].'/');

//数据中心KEY
define('JYDC_KEY', 'SDFvXDKCbm*dwSDF');

//360独代登录KEY
define('GLOBALLOGINKEY','XYKK@ZFeWnDKCbm*dwSHP');
//360独代充值KEY
define('GLOBALPAYKEY','IOT4vR#DXZyNEeCxBeEqvL');

//同步KEY
define('ADMIN_KEY','ZTQz@20111020_by_LSQ!^$^#&');

?>
