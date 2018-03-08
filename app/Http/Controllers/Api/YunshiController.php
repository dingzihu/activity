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
        $this ->appid = 'wx7606d538ea23ccaa';
        $this ->appsecret = '5836c199d08efbc9d88737b2ad4fe7ce';
    }
}