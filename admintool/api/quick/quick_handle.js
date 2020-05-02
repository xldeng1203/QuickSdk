const http_msg = require('../../logic/http_msg_handle')
const quick_conf = require('../config/quick_config')
const pb = require('../../base/public')
const plat_call_back = require('../plat_call_back')
const xml2js = require('xml2js')

//平台登录检测
function OnQuickLogin(res, data, callback) {
    let check = {
        token: decodeURIComponent(data.token),
        product_code: quick_conf.ProductCode,
        uid: data.account,
        channel_code: data.channelid,
    }

    pb.HttpPost(quick_conf.check_info_host, quick_conf.check_inbfo_path, check, (recv) => {
        if (parseInt(recv) == 1) {
            callback(`${data.account}_${data.channelid}`)
        } else {
            res.OnSendTips('qucik登录失败 => ' + recv)
        }
    }, false, false)
}

//处理充值
function OnQuickPay(res, post_data, get_data) {
    //uri转码
    post_data.nt_data = decodeURIComponent(post_data.nt_data)
    post_data.sign = decodeURIComponent(post_data.sign)

    //先验证
    if (pb.md5(post_data.nt_data + post_data.sign + quick_conf.Md5_Key) != post_data.md5Sign) {
        //验证失败
        res.OnSend('check sign faield')
        pb.err(`quick pay check sign faield!`)
        return
    }

    //解密
    let xml_data = qucikDecode(post_data.nt_data, quick_conf.Callback_Key)
    if (xml_data.length <= 0) {
        res.OnSend('decode xml data faield')
        pb.err(`quick decode xml data faield!`)
        return
    }

    xml2js.parseString(xml_data, {
        explicitArray: false,
        ignoreAttrs: true
    }, (err, result) => {
        if (err) {
            res.OnSend(`xml can't convert to json => ${err.message}`)
            pb.err(`quick pay xml can't convert to json => ${err.message}`)
        } else {
            //转换成功,处理订单
            let message = result.quicksdk_message.message
            let game_data = message.extras_params.split('|')
            if (game_data.length < 2) {
                res.OnSend(`extras_params error [${message.extras_params}]`)
                pb.err(`quick pay extras_params error [${message.extras_params}]`)
            } else {
                http_msg.PayMoney({
                    channelid: message.channel, //渠道ID
                    order: message.order_no, //订单号
                    account: message.channel_uid, //账号
                    amount: message.amount, //订单金额
                    idx: game_data[0], //充值角色所在服务器
                    actorid: game_data[1], //角色ID
                    time: message.pay_time, //平台处理时间
                }, (msg) => {
                    if (msg == 'SUCCESS')
                        pb.command('充值成功')
                    else
                        pb.err(`充值失败 => ${msg}`)
                    //返回结果
                    res.OnSend(msg)
                })
            }
        }
    })
}

//quick解密
function qucikDecode(str, key) {
    if (str.length <= 0) {
        return '';
    }

    var list = new Array();
    var resultMatch = str.match(/\d+/g);
    for (var i = 0; i < resultMatch.length; i++) {
        list.push(resultMatch[i]);
    }

    if (list.length <= 0) {
        return '';
    }

    var keysByte = stringToBytes(key);
    var dataByte = new Array();
    for (var i = 0; i < list.length; i++) {
        dataByte[i] = parseInt(list[i]) - (0xff & parseInt(keysByte[i % keysByte.length]));
    }

    if (dataByte.length <= 0) {
        return '';
    }

    var parseStr = bytesToString(dataByte);
    return parseStr;
}

function stringToBytes(str) {
    var ch, st, re = [];
    for (var i = 0; i < str.length; i++) {
        ch = str.charCodeAt(i);
        st = [];
        do {
            st.push(ch & 0xFF);
            ch = ch >> 8;
        } while (ch);
        re = re.concat(st.reverse());
    }
    return re;
}

function bytesToString(array) {
    return String.fromCharCode.apply(String, array);
}

//登陆消息
http_msg.RegLoginFunc(quick_conf.quick_spid, OnQuickLogin)
//平台回调
plat_call_back.register_plat_call_back_msg('qd', OnQuickPay)