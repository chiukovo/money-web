<?php

namespace App\Http\Controllers;

use Request, DB, Log, Hash, Curl, Auth;

class WebController extends Controller
{
    public function index()
    {
        //check login
        $user = [];
        $isLogin = Auth::guard('front')->check();
        $code = '';

        if ($isLogin) {
            $user = Auth::guard('front')->user();
        }

        $settings = DB::table('system_setting')
            ->get()
            ->first();

        //看是否有代理碼
        $code = Request::input('t', '');

        if ($code == '') {
            if (!is_null($settings)) {
                $code = $settings->default_code;
            }
        }

        $au = '';
        $iu = '';
        $du = '';
        $act = '';
        $marquee = '';
        $fileName = '';

        if (!is_null($settings)) {
            $au = $settings->android_game_download_url;
            $iu = $settings->ios_game_download_url;
            $du = $settings->download_teach_url;
            $act = $settings->activity_url;
            $marquee = $settings->marquee_word;
            $fileName = $settings->activity_file_url;
        }

        return view('index', [
            'isLogin' => $isLogin,
            'user' => $user,
            'code' => $code,
            'au' => $au,
            'iu' => $iu,
            'du' => $du,
            'act' => $act,
            'marquee' => $marquee,
            'fileName' => $fileName,
        ]);
    }

    public function downloadUrl()
    {
        $settings = DB::table('system_setting')
            ->get()
            ->first();

        $au = '';
        $iu = '';
        $du = '';
        $act = '';
        $marquee = '';
        $fileName = '';

        $result = [];

        if (!is_null($settings)) {
            $au = $settings->android_game_download_url;
            $iu = $settings->ios_game_download_url;

            $result = [
                'au' => $au,
                'iu' => $iu,
            ];
        }

        return response()->json($result);
    }

    public function logout()
    {
        $t = Request::input('t', '');
        Auth::guard('front')->logout();

        return redirect('/?t=' . $t);
    }
    
    public function checkAccount()
    {
        $account = Request::input('account', '');

        if ($account == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '請輸入帳號'
            ]);
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $account)) {
            return response()->json([
                'status' => 'error',
                'msg' => '只允許輸入數字英文'
            ]);
        }

        $countAccount = strlen($account);

        if ($countAccount < 4 || $countAccount > 12) {
            return response()->json([
                'status' => 'error',
                'msg' => '限制4-12字元'
            ]);
        }

        $check = DB::table('web_users')
            ->where('account', $account)
            ->first();
        

        if (!is_null($check)) {
            return response()->json([
                'status' => 'error',
                'msg' => '帳號已被使用!'
            ]);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function sendPhoneCode()
    {
        $phone = Request::input('phone', '');
        $url = 'http://api.message.net.tw/send.php';
        $account = env('MSG_ACCOUNT', '');
        $password = env('MSG_PASSWORD', '');
        $code = rand(10001, 99999);

        $date = date('Y-m-d H:i:s');
        $next = date('Y-m-d H:i:s', time() + 60);

        $msg = '天下麻將會員註冊\n驗證碼: ' . $code . '\n有效時間為60秒內';


        if ($phone == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '請填寫手機號碼'
            ]);
        }

        if (!isTwPhone($phone)) {
            return response()->json([
                'status' => 'error',
                'msg' => '錯誤的手機格式'
            ]);
        }

        //判斷此組手機是否已註冊
        $checkPhone = DB::table('web_users')
            ->where('phone', $phone)
            ->first();
        
        if (!is_null($checkPhone)) {
            return response()->json([
                'status' => 'error',
                'msg' => '此組手機已被註冊!'
            ]);
        }

        $isDisabledPhone = isDisabledPhone($phone);
        
        if ($isDisabledPhone) {
            return response()->json([
                'status' => 'error',
                'msg' => '此組手機號碼已被禁用!'
            ]);
        }

        //檢查是否可發送
        $ip = Request::getClientIp();

        $checkIp = DB::table('send_ip')
            ->where('ip', $ip)
            ->orderBy('id', 'desc')
            ->get()
            ->first();

        if (!is_null($checkIp)) {
            $checkNext = strtotime($checkIp->next_send_time);
            $checkNow = strtotime($date);

            if ($checkNow < $checkNext) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '60秒後才能重新發送'
                ]);
            }
        }

        //檢查是否此ip已寄送多組
        $checkThisIpCount = DB::table('send_log')
            ->where('ip', $ip)
            ->whereRaw('Date(created_at) = CURDATE()')
            ->orderBy('id', 'desc')
            ->get()
            ->count();
        
        if ($checkThisIpCount > 20) {
            return response()->json([
                'status' => 'error',
                'msg' => '今日已寄送多次!'
            ]);
        }

        $response = Curl::to($url)
            ->withData([
                'id' => $account,
                'password' => $password,
                'msg' => $msg,
                'tel' => $phone,
                'encoding' => 'utf8',
            ])
            ->post();
        
        $response = str_replace("\n", " ", $response);
        $explode = explode(" ", $response);

        foreach ($explode as $data) {
            $check = explode("=", $data);
            $target = isset($check[0]) ? $check[0] : '';
            $status = isset($check[1]) ? $check[1] : '';
            
            if ($target == 'ErrorCode' && $status < 0) {
                //save log
                DB::table('send_log')->insert([
                    'ip' => $ip,
                    'phone' => $phone,
                    'msg' => $response,
                    'status' => 0,
                    'created_at' => $date,
                ]);

                //error
                return response()->json([
                    'status' => 'error',
                    'msg' => '發送簡訊失敗'
                ]);
            }
        }

        //發送成功
        DB::table('send_ip')->insert([
            'ip' => $ip,
            'phone' => $phone,
            'code' => $code,
            'next_send_time' => $next,
            'created_at' => $date,
        ]);

        //save log
        DB::table('send_log')->insert([
            'ip' => $ip,
            'phone' => $phone,
            'msg' => $response,
            'status' => 1,
            'created_at' => $date,
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function doLogin()
    {
        $postData = Request::input();
        $account = Request::input('account', '');
        $password = Request::input('password', '');

        $isLogin = Auth::guard('front')->attempt([
            'account' => $account,
            'password' => $password
        ]);

        if ($isLogin) {
            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'msg' => '帳號或密碼錯誤 請確認!'
        ]);
    }

    public function reg()
    {
        $postData = Request::input();

        $name = Request::input('name', '');
        $nickname = Request::input('nickname', '');
        $account = Request::input('account', '');
        $password = Request::input('password', '');
        $rePassword = Request::input('re_password', '');
        $email = Request::input('email', '');
        $code = Request::input('code', '');
        $phone = Request::input('phone', '');
        $verification = Request::input('verification', '');
        $date = date('Y-m-d H:i:s');
        $ip = Request::getClientIp();
        
        //判斷必填欄位
        if ($name == '' || $account == '' || $password == '' || $code == '' || $rePassword == '' ||  $phone == '' || $verification == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '必填欄位未輸入'
            ]);
        }

        if (!isTwPhone($phone)) {
            return response()->json([
                'status' => 'error',
                'msg' => '錯誤的手機格式'
            ]);
        }

        //掃一遍看是否有欄位亂輸入過長
        foreach($postData as $data) {
            if (strlen($data) > 50) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '輸入欄位過長'
                ]);
            }
        }

        if(!preg_match('/^[a-zA-Z0-9]+$/', $account)){
            return response()->json([
                'status' => 'error',
                'msg' => '只允許輸入數字英文'
            ]);
        }

        $countAccount = strlen($account);

        if ($countAccount < 4 || $countAccount > 12) {
            return response()->json([
                'status' => 'error',
                'msg' => '帳號限制4-12字元'
            ]);
        }

        $check = DB::table('web_users')
            ->where('account', $account)
            ->first();


        if (!is_null($check)) {
            return response()->json([
                'status' => 'error',
                'msg' => '帳號已被使用!'
            ]);
        }

        $check = DB::table('web_users')
            ->where('phone', $phone)
            ->first();

        if (!is_null($check)) {
            return response()->json([
                'status' => 'error',
                'msg' => '電話已被使用!'
            ]);
        }

        $isDisabledPhone = isDisabledPhone($phone);

        if ($isDisabledPhone) {
            return response()->json([
                'status' => 'error',
                'msg' => '此組手機號碼已被禁用!'
            ]);
        }

        $countPassword = strlen($password);

        if ($countPassword < 6 || $countPassword > 18) {
            return response()->json([
                'status' => 'error',
                'msg' => '密碼限制6-18字元'
            ]);
        }

        //判斷密碼
        if ($password != $rePassword) {
            return response()->json([
                'status' => 'error',
                'msg' => '重複密碼與密碼不同 請確認!'
            ]);
        }

        //手機驗證碼確認
        $checkIp = DB::table('send_ip')
            ->where('ip', $ip)
            ->where('phone', $phone)
            ->orderBy('id', 'desc')
            ->get()
            ->first();
        
        if (is_null($checkIp)) {
            return response()->json([
                'status' => 'error',
                'msg' => '請重送驗證碼或驗證碼錯誤'
            ]);
        } else {
            if ($checkIp->code != $verification) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '驗證碼輸入錯誤　請檢查是否輸入正確!'
                ]);
            }

            //檢查驗證碼是否有效
            $checkNext = strtotime($checkIp->next_send_time);
            $checkNow = strtotime($date);

            if ($checkNow > $checkNext) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '驗證碼已失效　請重送'
                ]);
            }
        }

        //insert
        $insertData = [
            'name' => $name,
            'nickname' => $nickname,
            'account' => $account,
            'password' => Hash::make($password),
            'real_password' => $password,
            'email' => $email,
            'code' => $code,
            'phone' => $phone,
            'ip' => Request::getClientIp(),
            'created_at' => $date,
        ];
 
        try {
            DB::table('web_users')->insert($insertData);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'status' => 'error',
                'msg' => '系統出現錯誤'
            ]);
        }

        //幫他自動登入
        Auth::guard('front')->attempt([
            'account' => $account,
            'password' => $password
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
