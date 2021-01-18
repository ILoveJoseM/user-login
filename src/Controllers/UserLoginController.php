<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/11/21
 * Time: 15:02
 */

namespace JoseChan\UserLogin\Controllers;


use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constant\JWTKey;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constant\ErrorCode;
use JoseChan\UserLogin\Libraries\Wechat\MiniProgram\Application;
use JoseChan\UserLogin\Libraries\Wechat\Miniprogram\RegisterHandler\AbstractHandler;

class UserLoginController extends Controller
{

    /**
     * 用户密码登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['username', 'password']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {//登录失败
            return $this->response([], ErrorCode::LOGIN_FAIL, ErrorCode::msg(ErrorCode::LOGIN_FAIL));
        }

        $config = JWTKey::getConfigs();
        /** @var Model $user_model */
        $user_model = $config['user_model'];

        if (Auth::guard('admin')->attempt($credentials)) {//登录成功

            $user = $user_model::where("username", "=", $request->get("username"))->first();

            $user_id = $user ? $user->getKey() : null;

            if (!$user_id) {
                return $this->response([], ErrorCode::USER_NOT_FOUND, ErrorCode::msg(ErrorCode::USER_NOT_FOUND));
            }

            return $this->response(["token" => $this->generateJWT($user_id)], ErrorCode::SUCCESS, ErrorCode::msg(ErrorCode::SUCCESS));
        }

        return $this->response([], ErrorCode::LOGIN_FAIL, ErrorCode::msg(ErrorCode::LOGIN_FAIL));
    }

    /**
     * 微信小程序登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Wxapp(Request $request)
    {
        //参数校验
        $credentials = $request->only(['code']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            'code' => 'required',
        ]);

        if ($validator->fails()) {//登录失败
            return $this->response([], ErrorCode::LOGIN_FAIL, ErrorCode::msg(ErrorCode::LOGIN_FAIL));
        }

        //获取配置
        $config = config("user_login");

        if (!$config) {
            $config = require "../../config/user_login.php";
            $config = $config['mini_program'];
        }

        //获取用户信息
        $mini_program = new Application($config['mini_program']['app_id'], $config['mini_program']['app_secret']);

        if (!$info = $mini_program->login($request->get("code"))) {
            return $this->response([], ErrorCode::LOGIN_FAIL, ErrorCode::msg(ErrorCode::LOGIN_FAIL));
        }

        //获取用户表中的信息
        /** @var Model $user_model */
        $user_model = $config['jwt']['user_model'];
        if (isset($info['unionid'])) {
            //有unionid按unionid查
            /** @var Model $user */
            $user = $user_model::where("union_id", "=", $info['unionid'])->first();
            //查不出来
            if (empty($user) || !$user->exists) {
                //再用openid查
                /** @var Model $user */
                $user = $user_model::where("open_id", "=", $info['openid'])->first();
                if (!empty($user) && $user->exists) {
                    //查到了更新
                    $user->union_id = $info['unionid'];
                    $user->save();
                }
            }
        } else {
            $user = $user_model::where("open_id", "=", $info['openid'])->first();
            //查不出来
            if (empty($user) || !$user->exists) {
                //再用unionid查
                /** @var Model $user */
                $user = $user_model::where("union_id", "=", $info['unionid'])->first();
                if (!empty($user) && $user->exists) {
                    //查到了更新
                    $user->open_id = $info['openid'];
                    $user->save();
                }
            }
        }

        $user_id = $user ? $user->getKey() : null;

        //没有则注册
        if (!$user_id) {
            $register_handler = $config['mini_program']['register_handler'];

            /** @var AbstractHandler $handler */
            $handler = new $register_handler();

            $result = $handler->handler($info);

            //注册不成功，返回失败
            if (!$result) {
                return $this->response([], ErrorCode::LOGIN_FAIL, ErrorCode::msg(ErrorCode::LOGIN_FAIL));
            }

            $user_id = $result->getKey();
        }

        return $this->response(["token" => $this->generateJWT($user_id, $info)], ErrorCode::SUCCESS, ErrorCode::msg(ErrorCode::SUCCESS));
    }


    protected function generateJWT($uid, $data = [])
    {

        $config = JWTKey::getConfigs();

        $token = [
            'iss' => $config['iss'],
            'aud' => (string)$uid,
            'iat' => time(),
            'exp' => time() + $config['expired'], // 有效期
            'data' => $data
        ];

        return JWT::encode($token, $config['key'], $config['alg']);
    }

}

