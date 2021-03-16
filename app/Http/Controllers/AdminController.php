<?php

namespace App\Http\Controllers;

use Request, Auth, DB, Hash, Storage;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin/login');
    }

    public function doLogin()
    {
        $postData = Request::input();
        
        $account = $postData['account'] ?? '';
        $password = $postData['password'] ?? '';

        if (Auth::attempt([
            'account' => $account,
            'password' => $password
        ])) {
            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'msg' => '帳號或密碼錯誤'
        ]);
    }

    public function index()
    {
        $data = Request::input();

        $account = $data['account'] ?? '';
        $name = $data['name'] ?? '';

        $filters = [];
        
        if ($name != '') {
            $filters['name'] = $name;
        }

        if ($account != '') {
            $filters['account'] = $account;
        }

        $adminData = DB::table('admin_users');

        if (!empty($filters)) {
            $adminData->where($filters);
        }

        $adminData = $adminData
            ->get()
            ->toArray();

        return view('admin/adminUser/list', [
            'adminData' => $adminData,
            'account' => $account,
            'name' => $name,
        ]);
    }


    public function adminUserEdit()
    {
        $routeName = Request::route()->getName();
        
        $isEdit = false;
        $id = Request::input('id', '');
        $account = '';
        $name = '';

        if ($routeName == 'adminUserEdit') {
            $isEdit = true;
        }

        if ($id != '') {
            $adminUser = DB::table('admin_users')
                ->where('id', $id)
                ->get()
                ->first();
            
            if (is_null($adminUser)) {
                return redirect('/admin/');
            }

            $account = $adminUser->account;
            $name = $adminUser->name;
        }

        return view('admin/adminUser/edit', [
            'isEdit' => $isEdit,
            'name' => $name,
            'account' => $account,
            'id' => $id,
            'word' => $isEdit ? '編輯' : '新增'
        ]);
    }

    public function adminUserDoDelete()
    {
        $id = Request::input('id', '');

        if ($id == 1 || $id == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '此帳號無法刪除或id為空'
            ]);
        }

        DB::table('admin_users')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function adminUserDoEdit()
    {
        $postData = Request::input();
        $date = date('Y-m-d H:i:s');

        $id = $postData['id'] ?? '';
        $name = $postData['name'] ?? '';
        $account = $postData['account'] ?? '';
        $password = $postData['password'] ?? '';
        $re_password = $postData['re_password'] ?? '';

        if ($password != '' && $re_password != '') {
            if ($password != $re_password) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '重複密碼輸入不正確'
                ]);
            }
        }

        if ($id == '') {
            //create
            if ($name == '' || $account == '' || $password == '' || $re_password == '') {
                return response()->json([
                    'status' => 'error',
                    'msg' => '必填欄位尚未填寫'
                ]);
            }

            //檢查是否帳號已存在
            $checkAccount = DB::table('admin_users')
                ->where('account', $account)
                ->get()
                ->first();

            if (!is_null($checkAccount)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '此帳號已存在'
                ]);
            }

            DB::table('admin_users')->insert([
                'name' => $name,
                'account' => $account,
                'password' => Hash::make($password),
                'created_at' => $date,
                'updated_at' => $date
            ]);

            return response()->json([
                'status' => 'success',
            ]);
        } else {
            //update
            if ($name == '') {
                return response()->json([
                    'status' => 'error',
                    'msg' => '必填欄位尚未填寫'
                ]);
            }
            
            $updateData = [
                'name' => $name,
                'updated_at' => $date
            ];

            if ($password != '') {
                $updateData['password'] = Hash::make($password);
            }

            DB::table('admin_users')
                ->where('id', $id)
                ->update($updateData);

            return response()->json([
                'status' => 'success',
            ]);
        }
    }
    
    public function webUserList()
    {
        $data = Request::input();

        $account = $data['account'] ?? '';
        $code = $data['code'] ?? '';
        $phone = $data['phone'] ?? '';

        $filters = [];

        if ($code != '') {
            $filters['code'] = $code;
        }

        if ($account != '') {
            $filters['account'] = $account;
        }

        if ($phone != '') {
            $filters['phone'] = $phone;
        }

        $webUserData = DB::table('web_users');

        if (!empty($filters)) {
            $webUserData->where($filters);
        }

        $webUserData = $webUserData
            ->get([
                'account',
                'phone',
                'email',
                'name',
                'nickname',
                'code',
                'created_at',
            ])
            ->toArray();

        return view('admin/webUser/list', [
            'webUserData' => $webUserData,
            'code' => $code,
            'phone' => $phone,
            'account' => $account,
        ]);
    }


    public function webUserEdit()
    {
        $routeName = Request::route()->getName();

        $isEdit = false;
        $id = Request::input('id', '');
        $account = '';
        $name = '';
        $nickname = '';
        $email = '';
        $code = '';
        $phone = '';

        if ($routeName == 'webUserEdit') {
            $isEdit = true;
        }

        if ($id != '') {
            $webUser = DB::table('web_users')
                ->where('id', $id)
                ->get()
                ->first();

            if (is_null($webUser)) {
                return redirect('/admin/web/user/list');
            }

            $account = $webUser->account;
            $name = $webUser->name;
            $nickname = $webUser->nickname;
            $email = $webUser->email;
            $code = $webUser->code;
            $phone = $webUser->phone;
        }

        return view('admin/webUser/edit', [
            'isEdit' => $isEdit,
            'account' => $account,
            'name' => $name,
            'code' => $code,
            'nickname' => $nickname,
            'phone' => $phone,
            'email' => $email,
            'id' => $id,
            'word' => $isEdit ? '編輯' : '新增'
        ]);
    }

    public function webUserDoEdit()
    {
        $postData = Request::input();
        $id = Request::input('id', '');
        $name = Request::input('name', '');
        $nickname = Request::input('nickname', '');
        $account = Request::input('account', '');
        $password = Request::input('password', '');
        $re_password = Request::input('re_password', '');
        $email = Request::input('email', '');
        $code = Request::input('code', '');
        $phone = Request::input('phone', '');
        $verification = 'admin_create';
        $date = date('Y-m-d H:i:s');
        $ip = Request::getClientIp();

        if ($password != '' && $re_password != '') {
            if ($password != $re_password) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '重複密碼輸入不正確'
                ]);
            }

            $countPassword = strlen($password);

            if ($countPassword < 6 || $countPassword > 18) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '密碼限制6-18字元'
                ]);
            }
        }

        if ($id == '') {
            //create
            if ($name == '' || $account == '' || $password == '' || $re_password == '' || $code == '' || $phone == '' || $verification == '') {
                return response()->json([
                    'status' => 'error',
                    'msg' => '必填欄位未輸入'
                ]);
            }

            //掃一遍看是否有欄位亂輸入過長
            foreach ($postData as $data) {
                if (strlen($data) > 50) {
                    return response()->json([
                        'status' => 'error',
                        'msg' => '輸入欄位過長'
                    ]);
                }
            }

            if (!preg_match('/^[a-zA-Z0-9]+$/', $account)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => '只允許輸入數字英文'
                ]);
            }

            $countAccount = strlen($account);

            if ($countAccount < 4 || $countAccount > 12
            ) {
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

            return response()->json([
                'status' => 'success',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/admin/login');
    }

    public function settings()
    {
        $code = '';
        $au = '';
        $iu = '';
        $du = '';
        $act = '';
        $dp = '';
        $marquee = '';
        $fileName = '';

        $settings = DB::table('system_setting')
            ->get()
            ->first();
        
        if (!is_null($settings)) {
            $code = $settings->default_code;
            $au = $settings->android_game_download_url;
            $iu = $settings->ios_game_download_url;
            $du = $settings->download_teach_url;
            $act = $settings->activity_url;
            $marquee = $settings->marquee_word;
            $fileName = $settings->activity_file_url;
            $dp = $settings->disabled_phone;
        }

        return view('admin/settings', [
            'code' => $code,
            'au' => $au,
            'iu' => $iu,
            'du' => $du,
            'dp' => $dp,
            'marquee' => $marquee,
            'fileName' => $fileName,
            'act' => $act,
        ]);
    }

    public function saveSettings()
    {
        $code = Request::input('default_code', '');
        $au = Request::input('android_game_download_url', '');
        $iu = Request::input('ios_game_download_url', '');
        $du = Request::input('download_teach_url', '');
        $act = Request::input('activity_url', '');
        $file = Request::file('act_file', '');
        $dp = Request::input('disabled_phone', '');
        $marquee = Request::input('marquee_word', '');
        $date = date('Y-m-d H:i:s');
        $fileName = '';

        //file
        if ($file != '') {
            $fileName = 'activity_upload.jpg';
            Storage::put('public/activity_upload.jpg', $file->get());
        }

        $updateData = [
            'default_code' => $code,
            'android_game_download_url' => $au,
            'ios_game_download_url' => $iu,
            'download_teach_url' => $du,
            'disabled_phone' => $dp,
            'activity_url' => $act,
            'marquee_word' => $marquee,
            'updated_at' => $date
        ];

        if ($fileName != '') {
            $updateData['activity_file_url'] = $fileName;
        }

        DB::table('system_setting')
            ->where('id', 1)
            ->update($updateData);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
