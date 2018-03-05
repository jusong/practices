<?php
/**
 ************************************************************
 * Copyright (c) 2017 Baidu.com, Inc. All Rights Reserved
 *
 * Authors: Liu,Dongxue(liudongxue01@baidu.com)
 * Date: 2017/06/26
 ************************************************************
 */

/**
 * 获取API访问授权码
 * @param ak: ak from baidu cloud app
 * @param sk: sk from baidu cloud app
 * @return - access_token string if succeeds, else false.
 */
function access_token($ak, $sk) {
	$url = 'https://aip.baidubce.com/oauth/2.0/token';

    $post_data = array();
    $post_data['grant_type']  = 'client_credentials';
    $post_data['client_id']   = $ak;
    $post_data['client_secret'] = $sk;

    $res = request_post($url, $post_data);
    if (!!$res) {
        $res = json_decode($res, true);
        return $res['access_token'];
    } else {
        return false;
    }
}

function request_post($url, $data) {
    $ch	= curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    return curl_exec($ch);
}