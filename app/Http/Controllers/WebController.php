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

        if (!is_numeric($phone)) {
            return response()->json([
                'status' => 'error',
                'msg' => '非正确手机格式'
            ]);
        }

        //判斷字串長度是否過長
        if (
            strlen($name) > 100 ||
            strlen($vx) > 100 ||
            strlen($phone) > 100 ||
            strlen($msg) > 800
        ) {
            return response()->json([
                'status' => 'error',
                'msg' => '字串长度过长'
            ]);
        }

        //判斷是否新增太多筆
        $count = DB::table('web_users')->where('ip', $ip)->count();

        if ($count > 10) {
            return response()->json([
                'status' => 'error',
                'msg' => '亲 这麽想發财阿? 专人会立即连络您 请您耐心等候'
            ]);
        }

        //TG發訊息
        $sendMsg = '亲 有人来註册囉---o(*^▽^*)o' . '%0A';
        $sendMsg .= '%0A';
        $sendMsg .= '姓名: ' . $name . '%0A';
        $sendMsg .= 'vx: ' . $vx . '%0A';
        $sendMsg .= '手机号: ' . $phone . '%0A';
        $sendMsg .= '讯息: ' . $msg . '%0A';
        $sendMsg .= '来源ip: ' . $ip;

        $baseUrl = 'https://api.telegram.org/bot' . env('TG_TOKEN') . '/sendMessage?chat_id=' . env('TG_GROUP_ID') . '&text=' . $sendMsg;

        $response = Curl::to($baseUrl)->get();

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
