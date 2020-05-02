<?php
/**
 * 新浪游戏充值接口
 * http://gamea.com/xxx.php?SPID=sina&ServerID=1&UserID=xxx&UserIP=111&RoleID=xxx&OrderID=xxx&Sign=xxx
 */
require_once 'config.php';

    $SPID = request('SPID');
    $UserID = request('UserID');
    $ServerID = request('ServerID');
    $RoleID = request('RoleID');
    $OrderID = request('OrderID');
    $UserIP = request('UserIP');
    $PayPoint= request('PayPoint');
    $GamePoint = request('GamePoint');
    $Sign = request('Sign');
    //http://s2.cssg.wanwan.sina.com/oss/admintool/api/sina/wanwan/get_player_info.php?SPID=sina&ServerID=2&UserID=1319588285&UserIP=61.135.152.194&Sign=649728d242530202eb12ea2c2fe409da
    if($ServerID){
   		$url = "http://s{$ServerID}.cssg.wanwan.sina.com/oss/admintool/api/sina/weiwan/pay_sid.php?RoleID=$RoleID&UserIP=$UserIP&OrderID=$OrderID&SPID=$SPID&UserID=$UserID&ServerID=$ServerID&PayPoint=$PayPoint&GamePoint=$GamePoint&Sign=$Sign";
    }else{
    	$url = "http://s1.cssg.wanwan.sina.com/oss/admintool/api/sina/weiwan/pay_sid.php?RoleID=$RoleID&UserIP=$UserIP&OrderID=$OrderID&SPID=$SPID&UserID=$UserID&ServerID=$ServerID&PayPoint=$PayPoint&GamePoint=$GamePoint&Sign=$Sign";
    }
	
    header("Location:$url");
?>





