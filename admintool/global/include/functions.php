<?php
/*
 * 要用到的一些相关方法
 */


/**
 * 防止SQL注入，对传参进行字符过滤
 * @param string $key
 * @return string
 */
function request($key){
	return g(_request($key));
}

/**
 * 获取参数传值
 * @param string $key 参数传值
 * @return string
 */
function _request($key){
	return isset ( $_POST [$key] ) ? $_POST [$key] : (isset ( $_GET [$key] ) ? $_GET [$key] : null);
}
function alert($content = false, $url = "") {
	$javascript = "<script>";

	$javascript .= $content ? ("alert('" . $content . "');") : ("");								//链接警告窗口

	$javascript .= ($url == "") ? ("history.back();") : ("document.location.href='" . $url . "'"); 	//链接跳转信息

	$javascript .= "</script>";																		//链接尾部信息

	echo $javascript; exit;
}

//说明：计算 UTF-8 字符串长度（忽略字节的方案）
function strlen_utf8($str) {
	$i = 0;
	$count = 0;
	$len = strlen ( $str );
	while ( $i < $len ) {
		$chr = ord ( $str [$i] );
		$count ++;
		$i ++;
		if ($i >= $len)
		break;
		if ($chr & 0x80) {
			$chr <<= 1;
			while ( $chr & 0x80 ) {
				$i ++;
				$chr <<= 1;
			}
		}
	}
	return $count;
}

/*
 Utf-8、gb2312都支持的汉字截取函数
 cut_str(字符串, 截取长度, 开始长度, 编码);
 编码默认为 utf-8
 开始长度默认为 0
 */

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') {
	if ($code == 'UTF-8') {
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all ( $pa, $string, $t_string );

		if (count ( $t_string [0] ) - $start > $sublen)
		return join ( '', array_slice ( $t_string [0], $start, $sublen ) );
		return join ( '', array_slice ( $t_string [0], $start, $sublen ) );
	} else {
		$start = $start * 2;
		$sublen = $sublen * 2;
		$strlen = strlen ( $string );
		$tmpstr = '';

		for($i = 0; $i < $strlen; $i ++) {
			if ($i >= $start && $i < ($start + $sublen)) {
				if (ord ( substr ( $string, $i, 1 ) ) > 129) {
					$tmpstr .= substr ( $string, $i, 2 );
				} else {
					$tmpstr .= substr ( $string, $i, 1 );
				}
			}
			if (ord ( substr ( $string, $i, 1 ) ) > 129)
			$i ++;
		}
		if (strlen ( $tmpstr ) < $strlen)
		$tmpstr .= "...";
		return $tmpstr;
	}
}




//获取客户端IP
function get_client_ip() {
	$cip = getenv ( 'HTTP_CLIENT_IP' );
	$xip = getenv ( 'HTTP_X_FORWARDED_FOR' );
	$rip = getenv ( 'REMOTE_ADDR' );
	$srip = $_SERVER ['REMOTE_ADDR'];
	if ($cip && strcasecmp ( $cip, 'unknown' )) {
		$onlineip = $cip;
	} elseif ($xip && strcasecmp ( $xip, 'unknown' )) {
		$onlineip = $xip;
	} elseif ($rip && strcasecmp ( $rip, 'unknown' )) {
		$onlineip = $rip;
	} elseif ($srip && strcasecmp ( $srip, 'unknown' )) {
		$onlineip = $srip;
	}
	preg_match ( '/[\d\.]{7,15}/', $onlineip, $match );
	return $match [0] ? $match [0] : '';
}

function get_plat_name($plat_user_name){
	$plat = explode('_', $plat_user_name);
	unset($plat[count($plat)-1]);
	$plat_name = implode('_', $plat);
	return $plat_name;
}

function fetch_url($url, $type = 0, $post = '', $other_curl_opt = array(), $try_num = 0) {
	$curl_opt = array (
	CURLOPT_URL => $url,
	// CURLOPT_HTTPGET => TRUE, //默认为GET，无需设置
	CURLOPT_AUTOREFERER => TRUE,
	CURLOPT_RETURNTRANSFER => TRUE,
	CURLOPT_CONNECTTIMEOUT => 30, // 秒
	CURLOPT_TIMEOUT => 15 * 60  // The maximum number of seconds to allow cURL functions to execute.
	);
	if ($type == 1) {
		$curl_opt [CURLOPT_POST] = TRUE;
		$curl_opt [CURLOPT_POSTFIELDS] = $post; // username=abc&passwd=bcd,也可以为数组array('username'=>'abc','passwd'=>'bcd')
	}
	if ($other_curl_opt) {
		foreach ( $other_curl_opt as $key => $val ) {
			$curl_opt [$key] = $val;
		}
	}

	$ch = curl_init (); // 初始化curl会话
	curl_setopt_array ( $ch, $curl_opt ); // 以数组的形式为curl设置会话参数
	$contents = curl_exec ( $ch ); // 执行curl会话
	curl_close ( $ch ); // 关闭curl会话，它唯一的参数是curl_init()函数返回的句柄

	// fuck电信乱弹广告
	if (preg_match ( '#/dns\.php\?AIMT=#i', $contents ) && $try_num < 3) {
		$try_num ++;
		sleep ( 1 );
		return fetchUrl ( $url . '&fuckAd=' . time (), $other_curl_opt, $try_num );
	}

	return $contents;
}

//解析平台账号名
function parsePlatName($pname_index)
{
	$arr = explode('_',$pname_index);//$pname_index: For example: nobody_0
	$len = count($arr);
	$xnum = $len - 1;//下划线个数
	$server = $arr[$xnum];//服务器INDEX 0/1/2/3... abc_cde_aa_0

	$pname = "";
	for($i=0;$i<$xnum;$i++)
	{
		$pname .= $arr[$i]."_";
	}
	$array['pname'] = trim($pname, '_');
	$array['server']= $server;

	return $array;
}

// 构造服务器角色ID
function UidToInt($DB_INDEX,$Role_ID)
{
	$DB_INDEX=intval($DB_INDEX);
	$Role_ID=intval($Role_ID);
	$S_Role_ID=($DB_INDEX << 20)+$Role_ID;
	return $S_Role_ID;
}
// 解析服务器角色ID
function IntToUid($S_Role_ID)
{
	$S_Role_ID = intval($S_Role_ID);

	$ret_arr=array();
	$ret_arr["db_index"]= intval($S_Role_ID >> 20);
	$ret_arr["role_id"]=intval($S_Role_ID-($ret_arr["db_index"] << 20));
	return $ret_arr;
}



/**
 * 输出变量的内容，通常用于调试
 *
 * @package Core
 *
 * @param mixed $vars 要输出的变量
 * @param string $label
 * @param boolean $return
 */
function dump($vars, $label = '', $return = false) {
	if (ini_get ( 'html_errors' )) {
		$content = "<pre>\n";
		if ($label != '') {
			$content .= "<strong>{$label} :</strong>\n";
		}
		$content .= htmlspecialchars ( print_r ( $vars, true ) );
		$content .= "\n</pre>\n";
	} else {
		$content = $label . " :\n" . print_r ( $vars, true );
	}
	if ($return) {
		return $content;
	}
	echo $content;
	return null;
}

/**
 * 输出显示条目个数过滤
 *
 */
function displayNum($page,$num){
	$res = strrpos($page,'?');
	if($res){
		$page .= '&';
	}else{
		$page .= '?';
	}
	$str = '<a href="'.$page.'num=15">';
	if($num==15||!$num){
		$str .= '<font color="red"><b>15</b></font>';
	}else{
		$str .= '15';
	}
	$str .= '</a>　';
	$str .= '<a href="'.$page.'num=30">';
	if($num==30){
		$str .= '<font color="red"><b>30</b></font>';
	}else{
		$str .= '30';
	}
	$str .= '</a>　';
	$str .= '<a href="'.$page.'num=50">';
	if($num==50){
		$str .= '<font color="red"><b>50</b></font>';
	}else{
		$str .= '50';
	}
	$str .= '</a>　';
	$str .= '<a href="'.$page.'num=80">';
	if($num==80){
		$str .= '<font color="red"><b>80</b></font>';
	}else{
		$str .= '80';
	}
	$str .= '</a>　';
	return $str;
}

/**
 *正逆序调整
 */
function changeOtype($otype){
	if($otype == 0){$otype = 1;return $otype;break;}
	if($otype == 1){$otype = 0;return $otype;break;}
}

/**
 * 分页控制拦
 *
 * @param int $page				当前页
 * @param int $pagesize			每页记录数
 * @param int $count			记录总数
 * @param string $url			当前URL
 * @param string $orderby		排序字段
 * @param string $sort			排序方式
 * @return string
 */
function pagectrl($page = 1, $pagesize = 10, $count = 0, $url = '', $orderby = '', $sort = 'ASC') {
	if (empty ( $count ) || empty ( $pagesize )) {
		return '';
	} else {
		$pagecount = ceil ( $count / $pagesize );
	}

	$first = '首页';
	$prev = '上一页';
	$next = '下一页';
	$last = '末页';

	if (! empty ( $orderby )) {
		$sort = in_array ( strtoupper ( $sort ), array ('ASC', 'DESC' ) ) ? $sort : 'ASC';
		$orderby = "&orderby=$orderby&sort=$sort";
	}

	$url = $url . (strstr ( $url, '?' ) ? '&' : '?');
	$ctrlbar = '';
	if ($pagecount > 1) {
		$ctrlbar = "共{$count}条记录，每页{$pagesize}条，当前第$page/{$pagecount}页 ";

		if ($page > 1) {
			$ctrlbar .= '<a href="' . $url . 'page=1' . $orderby . '">' . $first . '</a> ';
			$ctrlbar .= '<a href="' . $url . 'page=' . ($page - 1) . $orderby . '">' . $prev . '</a> ';
		}
		if ($pagecount < 7) {
			for($pg = 1; $pg <= $pagecount; $pg ++) {
				if ($page == $pg)
				$ctrlbar .= '<a style="color:#FF6600;font-weight:bold;">' . $pg . '</a> ';
				else
				$ctrlbar .= '<a href="' . $url . 'page=' . $pg . $orderby . '">' . $pg . '</a> ';
			}
		} else {
			if ($page < 6) {
				$pg = 1;
			} else {
				if ($pagecount - $page < 3)
				$pg = $pagecount - 6;
				else
				$pg = $page - 3;
			}
			for($bg = $pg; $bg <= $pg + 6; $bg ++) {
				if ($page == $bg)
				$ctrlbar .= '<a style="color:#FF6600;font-weight:bold;">' . $bg . '</a> ';				else
				$ctrlbar .= '<a href="' . $url . 'page=' . $bg . $orderby . '">' . $bg . '</a> ';
			}
		}
		if ($page < $pagecount) {
			$ctrlbar .= '<a href="' . $url . 'page=' . ($page + 1) . $orderby . '">' . $next . '</a> ';
			$ctrlbar .= '<a href="' . $url . 'page=' . $pagecount . $orderby . '">' . $last . '</a> ';
		}
		$option = '跳转<select onchange="location.href=\''.$url.'page=\'+this.value+\''.$orderby.'\'">';
		for ($p = 1; $p <= $pagecount; $p++){
			if($p != $page)
			$option .= "<option value=\"$p\">$p/$pagecount</option>";
			else
			$option .= "<option value=\"$p\" selected=\"selected\">$p/$pagecount</option>";
		}
		$option .= '</select>';
		$ctrlbar .= $option;
	}

	return $ctrlbar;
}

/*
 * 根据得到的数组进行分页
 * 当前页：$curPage
 * 每页显示的数量：$perPage
 * 分页的连接地址：$url
 */
function arrayPage($array, $curPage, $perPage, $url)
{
	$allNumber = count($array); 					//总记录数
	$totalPage = ceil(count($array) / $perPage); 	//总页数

	/* array_slice(array,offset,length)
	 * array_slice函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
	 */
	$data = array_slice($array, ($curPage-1)*$perPage, $perPage);

	return array(
	"curpage" => $curPage,
	"totalpage" => $totalPage,
	"data" => $data,
	"totalnumber" => $allNumber,
	"url" => (strpos($url, "?") === false) ? ($url . "?page=") : (rtrim($url, "&") . "&page="),
	);
}

//快速排序
function quick_sort($array){
	if (count($array) <= 1) return $array;
	$key = $array[0];
	$left_arr = array();
	$right_arr = array();
	for ($i=1; $i<count($array); $i++){
		if ($array[$i] <= $key)
		$left_arr[] = $array[$i];
		else
		$right_arr[] = $array[$i];
	}
	$left_arr = quick_sort($left_arr);
	$right_arr = quick_sort($right_arr);

	return array_merge($left_arr, array($key), $right_arr);
}

/*
 * 根据数组元素排序 	 by 小强  2011-10-13 14:45
 * 第一个参数是需要排序的数组
 * 第二个参数是需要排序的元素
 * 第三个参数是排序的规则，倒叙还是顺序SORT_ASC/SORT_DESC
 * 第四第五个参数跟第二第三个参数一样（支持数组多元素排序）
 */
function array_orderby()
{
	$args = func_get_args();
	$data = array_shift($args);
	foreach($args as $n => $field)
	{
		if (is_string($field))
		{
			$tmp = array();
			foreach ($data as $key => $row)
			$tmp[$key] = $row[$field];
			$args[$n] = $tmp;
		}
	}
	$args[] = &$data;
	call_user_func_array('array_multisort', $args);
	return array_pop($args);
}

function getAssetsUrl(){
	global $XML_SAVE_PATH;
	return $XML_SAVE_PATH;
}

/*
 * 通过物品ID读取物品其他信息
 * 物品ID长度最长5位
 */
function getGoodsInfoById($item_id='')
{
	global $GOODS_XML;

	//解决因为excel或者xml在读取的时候省略物品ID 0前缀的问题
	if(!empty($item_id) && strlen($item_id) < 5){//物品ID长度最长5位
		$leng = 5 - strlen($item_id); //需要循环添加0前缀的次数
		$item_id_type = $item_id;
		for($i=0;$i<$leng;$i++){
			$item_id_type = "0".$item_id_type;
		}
		$goods_classify_id = empty($item_id_type) ? false : substr($item_id_type, 0, 2); //物品ID前两个数字表示物品的分类ID
	}else{
		$goods_classify_id = empty($item_id) ? false : substr($item_id, 0, 2); //物品ID前两个数字表示物品的分类ID
	}

	if($goods_classify_id == false || array_key_exists($goods_classify_id, $GOODS_XML) == false){
		return false;
	}

	$goods_xml = $GOODS_XML[$goods_classify_id]; //查询物品在哪个xml文件

	$cache = xml_cache_read('goods.'.$item_id.'.'.$goods_xml);
	if(is_array($cache) && !empty($cache)){
		return $cache;
	}else{
		$goods_xml_save_path = getAssetsUrl();
		$xml = (array)simplexml_load_file ( $goods_xml_save_path . $goods_xml );//以数组的形式读取物品所在的xml文件
		if(count($xml['good']) > 0){
			foreach($xml['good'] as $k => $v){
				$goods_id = (string)$v->attributes()->id;
				//记录物品详细信息的二维数组，key是物品ID
				$goods_info[$goods_id]['type'] = $goods_classify_id;
				if(isset($v->attributes()->id)) $goods_info[$goods_id]['id'] = (string)$v->attributes()->id;
				if(isset($v->attributes()->name))$goods_info[$goods_id]['name'] = (string)$v->attributes()->name;
				if(isset($v->attributes()->search_type))$goods_info[$goods_id]['search_type'] = (string)$v->attributes()->search_type;
				if(isset($v->attributes()->color))$goods_info[$goods_id]['color'] = (string)$v->attributes()->color;
				if(isset($v->attributes()->isbind))$goods_info[$goods_id]['isbind'] = (string)$v->attributes()->isbind;
				if(isset($v->attributes()->candiscard))$goods_info[$goods_id]['candiscard'] = (string)$v->attributes()->candiscard;
				if(isset($v->attributes()->cansell))$goods_info[$goods_id]['cansell'] = (string)$v->attributes()->cansell;
				if(isset($v->attributes()->sellprice))$goods_info[$goods_id]['sellprice'] = (string)$v->attributes()->sellprice;
				if(isset($v->attributes()->isdroprecord))$goods_info[$goods_id]['isdroprecord'] = (string)$v->attributes()->isdroprecord;
				if(isset($v->attributes()->isbroadcast))$goods_info[$goods_id]['isbroadcast'] = (string)$v->attributes()->isbroadcast;
				if(isset($v->attributes()->cancompose))$goods_info[$goods_id]['cancompose'] = (string)$v->attributes()->cancompose;
				if(isset($v->attributes()->compose_price))$goods_info[$goods_id]['compose_price'] = (string)$v->attributes()->compose_price;
				if(isset($v->attributes()->compose_another_id))$goods_info[$goods_id]['compose_another_id'] = (string)$v->attributes()->compose_another_id;
				if(isset($v->attributes()->compose_nobind))$goods_info[$goods_id]['compose_nobind'] = (string)$v->attributes()->compose_nobind;
				if(isset($v->attributes()->compose_isbind))$goods_info[$goods_id]['compose_isbind'] = (string)$v->attributes()->compose_isbind;
				if(isset($v->attributes()->gemstone_type))$goods_info[$goods_id]['gemstone_type'] = (string)$v->attributes()->gemstone_type;
				if(isset($v->attributes()->atrr_type))$goods_info[$goods_id]['atrr_type'] = (string)$v->attributes()->atrr_type;
				if(isset($v->attributes()->value))$goods_info[$goods_id]['value'] = (string)$v->attributes()->value;
				if(isset($v->attributes()->level))$goods_info[$goods_id]['level'] = (string)$v->attributes()->level;
				if(isset($v->attributes()->penal_id))$goods_info[$goods_id]['penal_id'] = (string)$v->attributes()->penal_id;
				if(isset($v->attributes()->description))$goods_info[$goods_id]['description'] = (string)$v->attributes()->description;
				if(isset($v->attributes()->scene_id))$goods_info[$goods_id]['scene_id'] = (string)$v->attributes()->scene_id;
				if(isset($v->attributes()->scene_x))$goods_info[$goods_id]['scene_x'] = (string)$v->attributes()->scene_x;
				if(isset($v->attributes()->scene_y))$goods_info[$goods_id]['scene_y'] = (string)$v->attributes()->scene_y;
				if(isset($v->attributes()->range))$goods_info[$goods_id]['range'] = (string)$v->attributes()->range;
				if(isset($v->attributes()->limit_level))$goods_info[$goods_id]['limit_level'] = (string)$v->attributes()->limit_level;
				if(isset($v->attributes()->task_id))$goods_info[$goods_id]['task_id'] = (string)$v->attributes()->task_id;
				if(isset($v->attributes()->forge_level))$goods_info[$goods_id]['forge_level'] = (string)$v->attributes()->forge_level;
				if(isset($v->attributes()->forge_type))$goods_info[$goods_id]['forge_type'] = (string)$v->attributes()->forge_type;
				if(isset($v->attributes()->token_level))$goods_info[$goods_id]['token_level'] = (string)$v->attributes()->token_level;
				if(isset($v->attributes()->activity_level))$goods_info[$goods_id]['activity_level'] = (string)$v->attributes()->activity_level;
				if(isset($v->attributes()->sub_type))$goods_info[$goods_id]['sub_type'] = (string)$v->attributes()->sub_type;
				if(isset($v->attributes()->icon_id))$goods_info[$goods_id]['icon_id'] = (string)$v->attributes()->icon_id;
				if(isset($v->attributes()->show_id))$goods_info[$goods_id]['show_id'] = (string)$v->attributes()->show_id;
			}
		}
		xml_cache_write('goods.'.$item_id.'.'.$goods_xml,$goods_info[$item_id]);
	}
	return $goods_info[$item_id];
}

/*
 * 可赠送玩家的物品列表
 */
function presentGoodsToUser(){
	global $PRESENT_GOODS_XML;
	$goods_xml_save_path = getAssetsUrl();
	$goodsinfo = array();
	foreach($PRESENT_GOODS_XML as $inx => $val){
		$cache = xml_cache_read('present.'.$val);
		if(is_array($cache) && !empty($cache)){
			foreach ($cache as $key => $value){
				if(isset($goodsinfo[$key]) && !empty($goodsinfo[$key])){//合并相同KEY的数组
					$goodsinfo[$key] += $value;
				}else{
					$goodsinfo[$key] = $value;
				}
			}
		}else{
			$goods = array();
			$xml = (array)simplexml_load_file ( $goods_xml_save_path . $val );//以数组的形式读取物品所在的xml文件
			foreach($xml['good'] as $k => $v){
				$search_type 									= (string)$v->attributes()->search_type; 	//物品类型
				$goods_id 										= (string)$v->attributes()->id;				//物品ID
				$goods[$search_type][$goods_id]['name'] 		= (string)$v->attributes()->name;
				$goods[$search_type][$goods_id]['color'] 		= (string)$v->attributes()->color;
				$goods[$search_type][$goods_id]['is_bind'] 		= (string)$v->attributes()->isbind;
				$goods[$search_type][$goods_id]['level'] 		= (string)$v->attributes()->limit_level;
				$goods[$search_type][$goods_id]['prof']         = (string)$v->attributes()->limit_prof;
				$goods[$search_type][$goods_id]['sex']          = (string)$v->attributes()->limit_sex;
				$goods[$search_type][$goods_id]['quality']      = (string)$v->attributes()->quality;	  //品质
			}
			xml_cache_write('present.'.$val,$goods);
			foreach ($goods as $key => $value){
				if(isset($goodsinfo[$key]) && !empty($goodsinfo[$key])){//合并相同KEY的数组
					$goodsinfo[$key] += $value;
				}else{
					$goodsinfo[$key] = $value;
				}
			}
			unset($xml);
		}
	}
	return $goodsinfo;
}

/**
 * 通过物品名称获取物品ID，模糊查询
 *
 * @return array item_ids
 */
function getItemName($item_name){
	$item_ids = array();
	if(!empty($item_name)){
		$itemarr = presentGoodsToUser(); //赠送物品列表
		foreach($itemarr as $key=>$value){
			foreach($value as $k=>$v){
				preg_match("/(.*)$item_name(.*)/", $v['name'],$matches);
				if(!empty($matches)){
					$item_ids[] = $k;
				}
			}
		}
	}
	return $item_ids;
}


/**
 * 仙法配置
 *
 * @return array
 */
function wudaoconfig(){
	$wudao_xml_save_path = getAssetsUrl();
	$wudao = array();
	$cache = xml_cache_read('xianfa.wudaoconfig.xml');
	if(is_array($cache) && !empty($cache)){
		return $cache;
	}else{
		$xml = (array)simplexml_load_file($wudao_xml_save_path.'wudaoconfig.xml');
		foreach ($xml['xianfa_list'] as $value){
			$wudao[(int)$value->id]['name'] = (string)$value->name;
			$wudao[(int)$value->id]['color'] = (string)$value->color;
			$wudao[(int)$value->id]['attr_type'] = (string)$value->attr_type;
			$wudao[(int)$value->id]['level'][1] = (string)$value->attr_value_level_1;
			$wudao[(int)$value->id]['level'][2] = (string)$value->attr_value_level_2;
			$wudao[(int)$value->id]['level'][3] = (string)$value->attr_value_level_3;
			$wudao[(int)$value->id]['level'][4] = (string)$value->attr_value_level_4;
			$wudao[(int)$value->id]['level'][5] = (string)$value->attr_value_level_5;
			$wudao[(int)$value->id]['level'][6] = (string)$value->attr_value_level_6;
			$wudao[(int)$value->id]['level'][7] = (string)$value->attr_value_level_7;
			$wudao[(int)$value->id]['level'][8] = (string)$value->attr_value_level_8;
			$wudao[(int)$value->id]['level'][9] = (string)$value->attr_value_level_9;
			$wudao[(int)$value->id]['level'][10] = (string)$value->attr_value_level_10;
			$wudao[(int)$value->id]['level'][11] = (string)$value->attr_value_level_11;
			$wudao[(int)$value->id]['level'][12] = (string)$value->attr_value_level_12;

		}
		xml_cache_write('xianfa.wudaoconfig.xml',$wudao);
		return $wudao;
	}
}

/**
 * 读取场景XML
 *
 */
function scenes(){
	$scenes = array();
	$cache = xml_cache_read('scenes.scenes.xml');
	if(is_array($cache) && !empty($cache)){
		return $cache;
	}else{
		$xml = (array)simplexml_load_file(PATH_ADMIN_CONFIG.'scenemanager.xml');
		foreach ($xml['scenes'] as $value){
			$id = (int)substr($value->path, 6, -4);
			$scenes[$id] = (string)$value->name;
		}
		xml_cache_write('scenes.scenes.xml',$scenes);
		return $scenes;
	}
}

/**
 * 读取物品配置
 *
 */
function item_list() {
	$item_cache = xml_cache_read('item_list.xml');
	if(is_array($item_cache) && !empty($item_cache)){
		return $item_cache;
	}else{
		$lines = file_get_contents(PATH_ADMIN_CONFIG.'item_list.txt');
		$lines = str_replace( "\r\n", "\n", $lines );
		$lines = explode( "\n", $lines );
		$item_list = array();
		foreach($lines as $line) {
			list ( $id, $name ) = explode('|', $line);
			$item_list[intval($id)] = $name;
		}
		xml_cache_write('item_list.xml', $item_list);
		return $item_list;
	}
}

/**
 * 读取任务配置
 *
 * @return unknown
 */
function tasks(){
	$task_cache = xml_cache_read('task_cache.xml');
	if(is_array($task_cache) && !empty($task_cache)){
		return $task_cache;
	}else{
		$task_list = array();
		$xml = (array)simplexml_load_file(PATH_ADMIN_CONFIG.'tasklist.xml');
		foreach ($xml['task_list'] as $value){
			$task_list[(int)$value->task_id]['id'] = (string)$value->task_id;
			$task_list[(int)$value->task_id]['name'] = (string)$value->task_name;
			$task_list[(int)$value->task_id]['type'] = (int)$value->task_type;
		}
		xml_cache_write('task_cache.xml',$task_list);
		return $task_list;
	}
}

/**
 * 读取成就配置
 * @return array $achieve 成就ID为下标的数组，包括成就名称和奖励内容
 */
function achieve(){
	$achieve_xml_save_path = getAssetsUrl();
	$achieve = array();
	$cache = xml_cache_read('achieve.achieve.xml');
	if(is_array($cache) && !empty($cache)){
		return $cache;
	}else{
		$xml = (array)simplexml_load_file($achieve_xml_save_path.'achieve.xml');
		foreach($xml['achieve'] as $value){
			$achieve[(int)$value->id]['id'] = (string)$value->id;
			$achieve[(int)$value->id]['name'] = (string)$value->name;
		}
		xml_cache_write('achieve.achieve.xml', $achieve);
		return $achieve;
	}
}

/**
 * 读取将魂配置
 * @return array $soul 返回将魂名称和将魂技能名称
 */
function soul()
{
	$soul_xml_save_path = getAssetsUrl();
	$soul = array();
	$cache = xml_cache_read('soul.soul.xml');
	if(is_array($cache) && !empty($cache)){
		return $cache;
	}else{
		$xml = (array)simplexml_load_file($soul_xml_save_path.'soul.xml');
		foreach($xml['soul'] as $value){
			$soul[(int)$value->soul_id]['name'] = (string)$value->name;
			$soul[(int)$value->soul_id]['skill_name'] = (string)$value->descript;
		}
		xml_cache_write('soul.soul.xml', $soul);
		return $soul;
	}
}
/**
 * XML文件读出缓存机制
 *
 * @param string $xml
 * @return unknown
 */
function xml_cache_read($xml){
	$lockfile = ROOT_DIR.'cache/xml.lock';//定义一个用来检查文件是否过久没用的标识
	if(!file_exists($lockfile)){
		file_put_contents($lockfile,'');
	}
	$atime = fileatime($lockfile);
	if(XML_SEARCH_TIME < time() - $atime){//如果标示文件访问时间大于当前时间N天，则进行文件检查和删除操作
		$lockdir = ROOT_DIR.'cache/';
		$dir = scandir($lockdir);
		foreach ($dir as $value){
			if($value == '.' || $value == '..' || $value == 'xml.lock') continue;
			if(XML_DELETE_TIME < time() - fileatime($lockdir.$value)){//如果文件在N天没有访问过，则删除
				unlink($lockdir.$value);
			}
		}
		touch($lockfile);
	}

	$filename = ROOT_DIR.'cache/'.$xml.'.cache';//缓存文件
	if(file_exists($filename)){
		$ftime = filemtime($filename);
	}else{
		$ftime = false;
	}
	if($ftime && time() - $ftime < XML_UPDATE_TIME){//如果文件存并且是N内没有修改过的,
		return unserialize(file_get_contents($filename));
	}else{
		return false;
	}
}

/**
 * 清除原有缓存文件
 */
function clear_cache(){
	$lockdir = ROOT_DIR.'cache/';
	$dir = scandir($lockdir);
	foreach ($dir as $value){
		if($value == '.' || $value == '..' || $value == 'xml.lock' || $value == '.svn') continue;
		unlink($lockdir.$value);
	}
}

/**
 * 将XML写进缓存
 *
 * @param string $xml
 * @param array $value
 * @return unknown
 */
function xml_cache_write($xml,$array){
	$filename = ROOT_DIR.'cache/'.$xml.'.cache';//缓存文件
	file_put_contents($filename,serialize($array));
	try{
		exec("chown -R www:www $filename");
	}catch(Exception $e){

	}
	return $filename;
}

function numformat($num,$format = 'round'){
	if($format == 'round'){
		return round($num);
	}elseif($format == 'floor'){
		return floor($num);
	}else{
		return ceil($num);
	}
}

function countarray($arr){
	return count($arr);
}

/**
 * 10进制转2进制
 *
 * @param unknown_type $num
 * @return unknown
 */
function numtonum($num){
	return decbin($num);
}

/**
 * 安全过滤字符串，防止SQL注入
 * _htmlspecialchars与_addslashes函数的合集
 * @param string|array $str
 * @return string|array
 */
function g($str)
{
	return _addslashes(_htmlspecialchars(_trim($str)), true);
}

/**
 * 扩展htmlspecialchars($str,ENT_QUOTES)函数，支持数组转换
 * 注意,本函数会将'号转换为&#39;
 * @param mixed $str 数组或字符串
 * @return mixed 转换后的结果
 */
function _htmlspecialchars($str)
{
	if (is_array($str))
	return array_map('_htmlspecialchars', $str);

	return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * 清理空格 - 支持数组
 * @param mixed $var
 * @return mixed
 */
function _trim($var)
{
	if (is_array($var))
	return array_map("_trim", $var);
	return trim($var);
}

/**
 * 扩展addslashes函数，支持多维数组
 * 当MAGIC_QUOTES_GPC已经开启时不会转义,需要设置$force参数
 * @param array|string $var 要转义的变量
 * @param bool $force 是否强制转义
 * @return array|string 处理后的$var
 */
function _addslashes($var, $force=false)
{
	if (MAGIC_QUOTES_GPC && !$force)
	return $var;

	if (is_array($var))
	{
		foreach($var as &$v)
		{
			$v = _addslashes($v, $force);
		}
		return $var;
	}

	return addslashes($var);

}

function fetchurl($url, $type = 0,$post = '',$other_curl_opt = array(),$try_num = 0){
	//echo "$url \n\n";
	$curl_opt = array(
	CURLOPT_URL => $url,
	//CURLOPT_HTTPGET => TRUE, //默认为GET，无需设置
	CURLOPT_AUTOREFERER => TRUE,
	CURLOPT_RETURNTRANSFER => TRUE,
	CURLOPT_CONNECTTIMEOUT => 30, //秒
	CURLOPT_TIMEOUT => 15*60, //The maximum number of seconds to allow cURL functions to execute.
	);
	if($type == 1){
		$curl_opt[CURLOPT_POST] = TRUE;
		$curl_opt[CURLOPT_POSTFIELDS] = $post;//username=abc&passwd=bcd,也可以为数组array('username'=>'abc','passwd'=>'bcd')
	}
	if($other_curl_opt)
	foreach ($other_curl_opt as $key => $val)
	$curl_opt[$key] = $val;

	$ch = curl_init(); //初始化curl会话
	curl_setopt_array($ch, $curl_opt); //以数组的形式为curl设置会话参数
	$contents = curl_exec($ch); //执行curl会话
	curl_close($ch); //关闭curl会话，它唯一的参数是curl_init()函数返回的句柄

	//fuck电信乱弹广告
	if(preg_match('#/dns\.php\?AIMT=#i', $contents) && $try_num < 3){
		$try_num++;
		sleep(1);
		return fetchUrl($url.'&fuckAd='.time(), $other_curl_opt, $try_num);
	}

	return $contents;
}

// 字符0-f转成对应值
function CharToNum($c)
{
	if ($c >= '0' && $c <= '9')
	return ord($c) - ord('0');

	if ($c >= 'a' && $c <= 'f')
	return ord($c) - ord('a') + 10;

	if ($c >= 'A' && $c <= 'F')
	return ord($c) - ord('A') + 10;

	return 0;
}


// 'A' 'B'按十六进制转成对应的值
function HexToByteValue($c1, $c2)
{
	return CharToNum($c1) * 16 + CharToNum($c2);
}

// ABCDEF 字符串转成 byte 数组
function HexStringToByteArray($s)
{
	$byte_array = array();

	for ($i = 0; $i < strlen($s); $i += 2)
	{
		array_push($byte_array, HexToByteValue($s[$i], $s[$i+1]));
	}

	return $byte_array;
}

//可放大图表
function renderZoomLine($categories, $dataset, $chartAttr='')
{
	//caption,subcaption
	$xml = "<chart $chartAttr compactDataMode='1' seriesNameInToolTip='1' formatNumberScale='0' showValues='0' dataSeparator='|' dynamicAxis='1' drawAnchors='0' bgColor='ffffff' showBorder='0' showVDivLines='1'>";

	foreach($dataset as $name => $vals)
	{
		$data = implode('|',$vals);
		$xml .= "<dataset seriesName='{$name}'>{$data}</dataset>";
	}

	$xml .= "<categories>".implode('|', $categories)."</categories>";

	$xml .= "</chart>";
	return $xml;
}

/**
 * 导出CSV文件数据
 */
function export_csv($filename,$data)
{
	$filename = $filename.'_'.date('YmdHis').".csv";//文件名
	header("Content-type:text/csv");
	header("Content-Disposition:attachment;filename=".$filename);
	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
	header('Expires:0');
	header('Pragma:public');
	echo i($data);
}

/**
 * 转正编码问题
 * @param unknown_type $strInput
 */
function i($strInput) {
	return iconv('utf-8',"gb2312//IGNORE",$strInput);//页面编码为utf-8时使用，否则导出的中文为乱码
}

/**
 * 调试用测试命令
 * @param unknown_type $str
 */
function xdebug($mixdata){
	$filename = LOG_DIR."xdebug.log";
	if (is_string($mixdata)){
		$text = $mixdata;
	}else{
		$text = var_export($mixdata, true);
	}

	$text = "\r\n". strftime ("[%Y-%m-%d %H:%M:%S] "). time() . "  ========================\r\n" . $text;
	$h = fopen($filename ,'a');
	if (!$h){ throw new exception('Could not open logfile:'.$filename); }
	if ( ! flock($h,LOCK_EX) ){ return false; }
	if (fwrite($h,$text)===false) { throw new exception('Could not write to logfile:'.$filename); }
	flock($h, LOCK_UN);
	fclose($h);
}


?>
