<?php
/**
@desciption: 控制器加载地图
*/
//下标 与 文件名 与 类名 要一致,
if(file_exists(COMM_DIR.'cache/map.cache.php') && filemtime(COMM_DIR.'cache/map.cache.php')+86400 > THIS_DATETIME){
	include(COMM_DIR.'cache/map.cache.php');
}else{
	$INCMAP = array();
	listdir(COMM_DIR.'control',$INCMAP);

	$str = "<?php //自动生成于".date('Y-m-d H:i:s')."，重新生成请删除本文件\r\n";
	foreach($INCMAP as $k=>$v){
		$str .= '$INCMAP[\''.$k.'\'] = \''.$v.'\''.";\r\n";
	}
	file_put_contents(COMM_DIR.'cache/map.cache.php',$str);
}

function listdir($dir,&$arr){
	foreach (scandir($dir) as $f){
		if($f == '.' || $f == '..') continue;
		if(is_dir($dir.'/'.$f)) {
			listdir($dir.'/'.$f,$arr);
		}else{
			$ext = pathinfo($f,PATHINFO_EXTENSION);
			if(strtolower($ext) == 'php') {
				$arr[basename($f,'.'.$ext)] = str_replace(COMM_DIR,'',$dir).'/'.$f;
			}
		}
	}
}