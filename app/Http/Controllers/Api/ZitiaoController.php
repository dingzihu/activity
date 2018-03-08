<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 字条小活动
 * Class ZitiaoController
 *
 * @package App\Http\Controllers\Api
 */
class ZitiaoController extends ApiController
{

    /**
     *
     *
     * ZitiaoController constructor.
     */
    public function __construct()
    {
        $this ->appid = 'wxbcdb21ee009055fe';
        $this ->appsecret = 'bfc0f7954767bed419b4e9d9df65c9c2';
    }

    /**
     * 获取消息列表
     *
     * @param Request $request
     *
     * @return mixed
     *
     */
    public function get_message(Request $request)
    {
        if ($request ->has('user_id') && $request ->has('add_user_id')){
            $data['user_count'] = DB::table('zitiao_count')
                ->where('user_id','=',$request ->get('user_id'))
                ->count();
            $data['user_info'] = DB::table('users')
                ->select('nickname','headimgurl')
                ->where('id', '=', $request ->get('user_id'))
                ->first();
            $data['gift'] = DB::table('zitiao_message') ->where('user_id', '=', $request ->get('user_id'))
                ->where('type', '=', 1)
                ->orderBy('id', 'desc')
                ->get();
            $data['message'] = DB::table('zitiao_message') ->where('user_id', '=', $request ->get('user_id'))
                ->where('type', '=', 0)
                ->orderBy('id', 'desc')
                ->get();
            $has_count = DB::table('zitiao_count')
                ->where('user_id','=',$request ->get('user_id'))
                ->where('add_user_id','=',$request ->get('add_user_id'))
                ->count();
            if (!$has_count){
                DB::table('zitiao_count') ->insert([
                    'user_id' => $request ->get('user_id'),
                    'add_user_id' => $request ->get('add_user_id')
                ]);
            }
            return $this ->success($data);
        }else{
            return $this ->failed('缺少参数!');
        }
    }

    /**
     * 删除消息
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function del_message(Request $request)
    {
        $post = $request ->post();
        if (isset($post['id'])){
            $result = DB::table('zitiao_message')
                ->where('id','=',$post['id'])
                ->delete();
            if ($result){
                return $this->success('删除成功!');
            }else{
                return $this ->failed('删除失败!');
            }
        }else{
            return $this ->failed('缺少参数!');
        }
    }

    /**
     * 发送消息
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function build_message(Request $request)
    {
        $post = $request ->post();
        if (isset($post['type']) && isset($post['contents']) && isset($post['user_id']) && isset($post['add_user_id'])){
            $result = DB::table('zitiao_message')
                ->insert($post);
            if ($result){
                return $this->success('发送成功!');
            }else{
                return $this ->failed('发送失败!');
            }
        }else{
            return $this ->failed('缺少参数!');
        }
    }
}