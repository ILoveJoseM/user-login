<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-03
 * Time: 17:17
 */

return [
    "jwt" => [
        "key" => "jose_chan", //密钥
        "iss" => "http://localhost",
        "alg" => "HS256",
        "expired" => 7200, //过期时间
        "user_model" => \JoseChan\UserLogin\Models\Users::class, //用户模型
    ],

    //小程序配置
    "mini_program" => [
        "app_id" => env("MINI_PROGRAM_APP_ID", ""), //app_id
        "app_secret" => env("MINI_PROGRAM_APP_SECRET", ""), //app_secret
        "register_handler" => \JoseChan\UserLogin\Libraries\Wechat\Miniprogram\RegisterHandler\RegisterHandler::class //注册处理器
    ]
];