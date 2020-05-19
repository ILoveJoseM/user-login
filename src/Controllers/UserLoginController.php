<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/11/21
 * Time: 15:02
 */

namespace JoseChan\UserLogin\Controllers;

use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Handler\Login;
use Symfony\Component\HttpFoundation\Response;

class UserLoginController extends Controller
{

    /**
     * 用户密码登录
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $login_type = $request->get("login_type", "account");
        $response = Login::login($login_type, $request->all());
        return $response;
    }

    /**
     * 注册
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $login_type = $request->get("login_type", "account");
        $response = Login::register($login_type, $request->all());
        return $response;
    }
}

