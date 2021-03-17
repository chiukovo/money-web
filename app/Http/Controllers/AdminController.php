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

        $name = $data['name'] ?? '';
        $vx = $data['vx'] ?? '';
        $phone = $data['phone'] ?? '';
        $ip = $data['ip'] ?? '';
        $status = $data['status'] ?? '';

        $filters = [];

        if ($name != '') {
            $filters['name'] = $name;
        }

        if ($vx != '') {
            $filters['vx'] = $vx;
        }

        if ($phone != '') {
            $filters['phone'] = $phone;
        }

        if ($ip != '') {
            $filters['ip'] = $ip;
        }

        if ($status != '') {
            $filters['status'] = $status;
        }

        $webUserData = DB::table('web_users');

        if (!empty($filters)) {
            $webUserData->where($filters);
        }

        $webUserData = $webUserData
            ->get()
            ->toArray();

        return view('admin/webUser/list', [
            'webUserData' => $webUserData,
            'name' => $name,
            'vx' => $vx,
            'phone' => $phone,
            'ip' => $ip,
            'status' => $status,
        ]);
    }

    public function webUserDoDelete()
    {
        $id = Request::input('id', '');

        if ($id == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '此帳號無法刪除或id為空'
            ]);
        }

        DB::table('web_users')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function webUserUpdateStatus()
    {
        $id = Request::input('id', '');
        $status = Request::input('status', '');

        if ($id == '' || $status == '') {
            return response()->json([
                'status' => 'error',
                'msg' => '此帳號無法刪除或id為空'
            ]);
        }

        DB::table('web_users')
            ->where('id', $id)
            ->update([
                'status' => $status
            ]);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/admin/login');
    }
}
