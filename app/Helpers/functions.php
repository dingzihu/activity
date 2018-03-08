<?php

/**
 * @return bool|mixed
 */
function get_img_source()
{
    if(($img_url = request() ->get('img_url'))) {
        if(! preg_match('/http:\/\/[^.]*\.qlogo\.cn/',$img_url) && ! preg_match('/myqcloud/',$img_url) ) {
            return false;
        }
        header('Content-type:image/jpeg;charset=utf-8');
        $ch = curl_init($img_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

/**
 * 发起http post请求(REST API), 并获取REST请求的结果
 * @param string $url
 * @param string $param
 * @return - http response body if succeeds, else false.
 */
function request_post($url = '', $param = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
}

/**
 * 获取百度access_token
 * @return mixed
 */
function get_baidu_access_token()
{
    $access_token = \Illuminate\Support\Facades\Cache::get('baidu_access_token');
    if (!$access_token){
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type']       = 'client_credentials';
        $post_data['client_id']      = 'I1OFQhApjbb9Nsjk7WGGwmWX';
        $post_data['client_secret'] = 'vt8lS42MomA3mc3flfYPMYc1Wa7x5m49';
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);
        $res = request_post($url, $post_data);
        $expiresAt = \Illuminate\Support\Carbon::now()->addMinutes(7000);
        \Illuminate\Support\Facades\Cache::put('baidu_access_token',$res,$expiresAt);
        $access_token = $res;
    }
    return json_decode($access_token,true);
}