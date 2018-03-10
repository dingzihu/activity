<?php

namespace App\Http\Controllers\Api;

/**
 * 狗年运势小活动
 *
 * Class YunshiController
 *
 * @package App\Http\Controllers\Api
 */
class YunshiController extends ApiController
{
    public function __construct()
    {
        if ($_SERVER["HTTP_HOST"] == 'tow.deruixuan.com.cn'){
            $this ->appid = 'wx380246362e9f23e7';
            $this ->appsecret = '9c5ffc0561c00760d866b800eb82be95';
        }else if($_SERVER["HTTP_HOST"] == 'seif.fsherun.cn'){
            $this ->appid = 'wx7606d538ea23ccaa';
            $this ->appsecret = '5836c199d08efbc9d88737b2ad4fe7ce';
        }
    }
}