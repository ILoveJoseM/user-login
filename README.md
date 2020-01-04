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

`http://localhost/login/wxapp`

控制器位置在 `vendor/jose-chan/user-login/src/Controllers`，
命名空间为 `\JoseChan\UserLogin\Controllers`，
默认的路由位置为`vendor/jose-chan/user-login/routes/routes.php`，
如果需要自定义路由，请参照laravel的路由设置配置

