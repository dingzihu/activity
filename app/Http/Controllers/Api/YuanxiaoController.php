<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

/**
 * 元宵节小活动
 * Class YuanxiaoController
 *
 * @package App\Http\Controllers\Api
 */
class YuanxiaoController extends ApiController
{
    public function __construct()
    {
        $this ->appid = 'wx7606d538ea23ccaa';
        $this ->appsecret = '5836c199d08efbc9d88737b2ad4fe7ce';
    }

    /**
     * 获取图片头像定位信息
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function get_header_img(Request $request)
    {
        if ($request ->has('data')){
            $access_token = get_baidu_access_token();
            $url = 'https://aip.baidubce.com/rest/2.0/face/v1/detect?access_token=' . $access_token['access_token'];
            $bodys = array(
            'max_face_num' => 1,
            'face_fields' => "age,gender,beauty",
                'image' => $request ->post('data')
            );
            $res = request_post($url, $bodys);
            return $this ->success(json_decode($res,true));
        }else{
            return $this ->failed('缺少参数!');
        }
    }
}