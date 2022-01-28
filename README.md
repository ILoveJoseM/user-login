# 用户登录组件

## 安装

````ssh
composer require "jose-chan/user-login" -vvv
````

## 发布

````ssh
php artisan vendor:publish --tag=user-login
````

## 表结构导入

``
php artisan migrate --path=vendor/jose-chan/user-login/database/migrations
``


## 使用

默认路由是:
- 账密登录

``http://localhost/login``

- 小程序登录

`http://localhost/login/wxapp`

控制器位置在 `vendor/jose-chan/user-login/src/Controllers`，
命名空间为 `\JoseChan\UserLogin\Controllers`，
默认的路由位置为`vendor/jose-chan/user-login/routes/routes.php`，
如果需要自定义路由，请参照laravel的路由设置配置

#### 配置
修改配置文件`config/user_login.php`

````php
<?php
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
?>
````

#### 模型

- 模型必须实现UserModelInterface

#### 中间件

| 类 | 别称 | 功能 | 
|:----:|:----:|:----:|
| `\JoseChan\UserLogin\Middleware\Dispatch` | dispatch | 校验token并初始化用户信息（请求头Authorization传参） | 
| `\JoseChan\UserLogin\Middleware\JWTDispatch` | jwt.dispatch | 校验token并初始化用户信息（token参数传参） |
| `\JoseChan\UserLogin\Middleware\LoginCheck` | login.check | 检查用户登录状态 |


