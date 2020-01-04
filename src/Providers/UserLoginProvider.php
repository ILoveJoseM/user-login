<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-03
 * Time: 18:08
 */

namespace JoseChan\UserLogin\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use JoseChan\UserLogin\Middleware\Dispatch;
use JoseChan\UserLogin\Middleware\LoginCheck;

class UserLoginProvider extends ServiceProvider
{
    /** 定义命名空间 **/
    protected $namespace = "JoseChan\UserLogin\Controllers";

    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/user_login.php' => config_path("user_login.php")], "user-login");
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Route::aliasMiddleware("dispatch", Dispatch::class);
        Route::aliasMiddleware("login.check", LoginCheck::class);
        Route::namespace($this->namespace)
            ->group(__DIR__ . "/../../routes/routes.php");

    }


}