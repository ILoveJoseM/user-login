# 用户登录组件

## 安装

````ssh
composer require "jose-chan/user-login" -vvv
````

## 发布

````ssh
php artisan vendor:publish --tag=user-login
````

## 使用

默认路由是:
- 账密登录

``http://localhost/login``

- 小程序登录

> @deprecated 小程序模块已从组件移除 since 3.0

`http://localhost/login/wxapp`

控制器位置在 `vendor/jose-chan/user-login/src/Controllers`，
命名空间为 `\JoseChan\UserLogin\Controllers`，
默认的路由位置为`vendor/jose-chan/user-login/routes/routes.php`，
如果需要自定义路由，请参照laravel的路由设置配置

## 扩展登录方式

#### 定义登录方式类

继承组件中的`\JoseChan\UserLogin\Handler\LoginAbstract`类实现自己的登录/注册逻辑

````php
<?php 
namespace JoseChan\UserLogin\Handler\Gateway;

use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Handler\LoginAbstract;

class LoginDemo extends LoginAbstract
{
    /** @var bool $auto_register 是否自动注册 */
    protected $auto_register = false;

    /**
     * 登录逻辑
     * @param array $form
     * @return Model
     * @throws \Exception
     */
    public function login(array $form): Model
    {
        
    }

    /**
     * 注册逻辑
     * @param array $form
     * @return Model
     * @throws \Exception
     */
    public function register(array $form): Model
    {
        
    }
    
    /**
     * 校验登录需要的参数
     * @return array
     */
    public function loginValidate():array 
    {
        return [
            "username" => "required"
           
        ];
    }
    
    /**
     * 校验注册需要的参数
     * @return array
     */
    public function registerValidate():array 
    {
     // TODO: Implement registerValidate() method.
    }
}

````

#### 在provider中加载你的组件

````php
<?php

use Illuminate\Support\ServiceProvider;
use JoseChan\UserLogin\Handler\Login;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Login::extend("demo", LoginDemo::class);
    }
}

````
