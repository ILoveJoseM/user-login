<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-02
 * Time: 10:45
 */

namespace JoseChan\UserLogin\Handler;


use Firebase\JWT\JWT;
use JoseChan\UserLogin\Constant\JWTKey;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use JoseChan\UserLogin\Constant\ErrorCode;

/**
 * 所有登录扩展都要继承该类
 * Class LoginAbstract
 * @package JoseChan\UserLogin\Handler
 */
abstract class LoginAbstract implements LoginInterface
{
    /** @var bool $auto_register */
    protected $auto_register = false;


    /**
     * 是否自动注册
     * @return bool
     */
    public function autoRegister()
    {
        return $this->auto_register;
    }


    /**
     * 注册失败处理
     * @return Response
     */
    public function failsRegisterHandler(): Response
    {
        return \response()->json([
            "code" => ErrorCode::REGISTER_FAIL,
            "msg" => ErrorCode::msg(ErrorCode::REGISTER_FAIL),
            "data" => [],
        ]);
    }

    /**
     * 登录失败处理
     * @return Response
     */
    public function failsLoginHandler(): Response
    {
        return \response()->json([
            "code" => ErrorCode::LOGIN_FAIL,
            "msg" => ErrorCode::msg(ErrorCode::LOGIN_FAIL),
            "data" => [],
        ]);
    }

    /**
     * 注册成功处理
     * @param Model $user
     * @return Response
     */
    public function successRegisterHandler(Model $user): Response
    {
        return \response()->json([
            "code" => 1,
            "msg" => "success",
            "data" => [
                "token" => $this->generateJWT($user->id)
            ],
        ]);
    }

    /**
     * 登录成功处理
     * @param Model $user
     * @return Response
     */
    public function successLoginHandler(Model $user): Response
    {
        return \response()->json([
            "code" => 1,
            "msg" => "success",
            "data" => [
                "token" => $this->generateJWT($user->id)
            ],
        ]);
    }

    /**
     * 获取登录数据
     * @param array $form
     * @return array
     */
    public function getLoginData(array $form): array
    {
        return $form;
    }

    public function getRegisterData(array $form): array
    {
        return $form;
    }

    /**
     * 生成token
     * @param $uid
     * @param array $data
     * @return string
     */
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
