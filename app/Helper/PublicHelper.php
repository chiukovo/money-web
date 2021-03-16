<?php

if (!function_exists('testHelper')) {

    /**
     * 測試helper function 是否能啟用
     *
     * @return string
     */
    function testHelper()
    {
        return 'ok';
    }
}

if (!function_exists('adminLoginInfo')) {

    function adminLoginInfo()
    {
        if (!Auth::check()) {
            return [];
        }

        return Auth::user();
    }
}

if (!function_exists('isTwPhone')) {

    function isTwPhone($phone)
    {
        if (preg_match("/^09[0-9]{2}-[0-9]{3}-[0-9]{3}$/", $phone)) {
            return true;    // 09xx-xxx-xxx
        } else if (preg_match("/^09[0-9]{2}-[0-9]{6}$/", $phone)) {
            return true;    // 09xx-xxxxxx
        } else if (preg_match("/^09[0-9]{8}$/", $phone)) {
            return true;    // 09xxxxxxxx
        } else {
            return false;
        }
    }
}

if (!function_exists('isDisabledPhone')) {

    function isDisabledPhone($phone)
    {
        //檢查是否為黑名單
        $settings = DB::table('system_setting')
            ->get()
            ->first();

        $disabledPhone = $settings->disabled_phone;

        $explode = explode(",", $disabledPhone);

        foreach ($explode as $disabledPhone) {
            if ($phone == $disabledPhone) {
                return true;
            }
        }

        return false;
    }
}