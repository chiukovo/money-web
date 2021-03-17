<?php

namespace App\Http\Controllers;

use Request, DB, Log, Hash, Curl, Auth;

class WebController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function doSave()
    {
        $date = date('Y-m-d H:i:s');
        $ip = Request::getClientIp();
        $postData = Request::input();

        $name = $postData['name'] ?? '';
        $vx = $postData['vx'] ?? '';
        $phone = $postData['phone'] ?? '';
        $msg = $postData['msg'] ?? '';

        if ($name == '' || $vx == '' || $phone == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '名称 手机号 vx 不得为空'
            ]);
        }

        if (!preg_match('/^(1\s|1|)?((\(\d{3}\))|\d{3})(\-|\s)?(\d{3})(\-|\s)?(\d{4})$/', $phone)) {
            return response()->json([
                'status' => 'error',
                'msg' => '非正确手机格式'
            ]);
        }

        //判斷字串長度是否過長

        //判斷是否新增太多筆
        $count = DB::table('web_users')->where('ip', $ip)->count();

        if ($count > 10) {
            return response()->json([
                'status' => 'error',
                'msg' => '亲 这麽想發财阿? 专人会立即连络您 请您耐心等候'
            ]);
        }

        DB::table('web_users')->insert([
            'name' => $name,
            'vx' => $vx,
            'phone' => $phone,
            'msg' => $msg,
            'ip' => $ip,
            'created_at' => $date,
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }
}
