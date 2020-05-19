<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-02
 * Time: 14:43
 */

namespace JoseChan\UserLogin\Handler;


use Illuminate\Support\Facades\Validator;
use JoseChan\UserLogin\Constant\ErrorCode;
use JoseChan\UserLogin\Handler\Gateway\AccountLogin;

/**
 * 登录门面
 * Class Login
 * @package JoseChan\UserLogin\Handler
 */
class Login
{
    protected static $extend = [
        "account" => AccountLogin::class,
    ];

    protected static $gateway = [];

    /**
     * 登录
     * @param string $login_style
     * @param array $form
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public static function login($login_style = "account", $form = [])
    {
        try{
            $gateway = self::makeGateway($login_style);

            /** @var \Illuminate\Validation\Validator $validator */
            $form = $gateway->getLoginData($form);
            $validator = Validator::make($form, $gateway->loginValidate());

            if ($validator->fails()) {//登录失败
                throw new \Exception("登录参数不正确");
            }

            $user = $gateway->login($form);

            if ($user && $user->exists) {
                return $gateway->successLoginHandler($user);
            }

            if ($gateway->autoRegister()) {
                return self::register($login_style, $form);
            } else {
                return $gateway->failsLoginHandler();
            }
        }catch (\Exception $exception){
            return self::handleException($exception);
        }

    }

    /**
     * 注册
     * @param string $login_style
     * @param array $form
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public static function register($login_style = "account", $form = [])
    {
        try{
            $gateway = self::makeGateway($login_style);

            /** @var \Illuminate\Validation\Validator $validator */
            $form = $gateway->getRegisterData($form);
            $validator = Validator::make($form, $gateway->registerValidate());

            if ($validator->fails()) {//登录失败
                throw new \Exception("注册参数不正确");
            }

            $user = $gateway->register($form);

            if ($user && $user->exists) {
                return $gateway->successRegisterHandler($user);
            }

            return $gateway->failsRegisterHandler();
        }catch (\Exception $exception){
            return self::handleException($exception);
        }
    }

    /**
     * 构造处理器对象
     * @param $extend
     * @return LoginAbstract
     * @throws \Exception
     */
    public static function makeGateway($extend)
    {
        if (isset(self::$gateway[$extend])) {
            return self::$gateway[$extend];
        }

        if (!isset(self::$extend[$extend])) {
            throw new \Exception("login style [{$extend}] is not support");
        }

        $gateway = self::$extend[$extend];

        self::$gateway[$extend] = new $gateway();

        return self::$gateway[$extend];
    }

    public static function extend($name, $class_name)
    {
        self::$extend[$name] = $class_name;
    }

    /**
     * 异常处理
     * @param \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected static function handleException(\Exception $exception)
    {
        return \response()->json([
            "code" => $exception->getCode() == 0 ? ErrorCode::SYSTEM_ERROR : $exception->getCode(),
            "msg" => $exception->getMessage(),
            "data" => [],
        ]);
    }
}
