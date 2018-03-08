<?php

namespace App\Http\Controllers\Api;
use App\Api\Helpers\Api\Jssdk;
use App\Api\Helpers\Api\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Qcloud\Cos\Client;

class ApiController extends Controller
{
    protected $appid = '';
    protected $appsecret = '';

    use ApiResponse;

    // 其他通用的Api帮助函数

    /**
     * 获取微参数信
     * @return mixed
     */
    public function get_wx_params(){
        $jssdk = new JSSDK($this ->appid, $this ->appsecret);
        $signPackage = $jssdk->GetSignPackage();
        return  $this ->success($signPackage);
    }

    /**
     * 获取用户信息
     * @return mixed
     */
    public function get_user_info()
    {
        $code = request() ->get('code');
        $appId = $this ->appid;
        $appSecret = $this ->appsecret;
        $data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.
            '&secret='.$appSecret.'&code='.$code.'&grant_type=authorization_code');
        $access_token = json_decode($data,true);
        if (isset($access_token['errcode'])){
            return  $this ->failed($access_token['errmsg']);
        }else{
            $data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appId.
                '&grant_type=refresh_token&refresh_token='.$access_token['refresh_token']);
            $access_token = json_decode($data,true);
            $data = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.
                $access_token['access_token'].'&openid='.$access_token['openid'].'&lang=zh_CN');
            $user_data =  json_decode($data,true);
            if (isset($user_data['errcode'])){
                return  $this ->failed($user_data['errmsg']);
            }else{
                $id = DB::table('users') ->where('appid','=',$appId)
                    ->where('openid','=',$access_token['openid'])
                    ->value('id');
                if(!$id){
                    $user_data['appid'] = $appId;
                    $user_data['access_token'] = $access_token['access_token'];
                    $user_data['privilege'] = json_encode($user_data['privilege']);
                    $id = DB::table('users')->insertGetId(
                        $user_data
                    );
                }
                return  $this ->success(['nickname' => $user_data['nickname'],'headimgurl' => $user_data['headimgurl'],'user_id' => $id]);
            }
        }
    }

    /**
     * 上传文件
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function upload_file(Request $request)
    {
        $file = $request ->file('file');
        $allowed_extensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            $this ->failed('You may only upload png, jpg or gif.');
        }
        $extension = $file->getClientOriginalExtension();
        $fileName = 'file/'.date('YmdHis').str_random(10).'.'.$extension;
        $cosClient = new Client(array('region' => 'cd',
            'credentials'=> array(
                'secretId'    => 'AKIDlYFl8JxqD5rSvENYcbPSbO5bx8JRvO7j',
                'secretKey' => 'vMm3B9Qfpz1sY6iEcquoX9lbNyPgltur')));
        try {
            $result = $cosClient->upload(
            //bucket的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
                $bucket='benediction-1256073706',
                $key = $fileName,
                $body=fopen($file ->getRealPath(),'r+'));
            return $this ->setStatusCode(200) ->success(urldecode($result['Location']));
        } catch (\Exception $e) {
            return  $this ->failed($e ->getMessage());
        }
    }

}