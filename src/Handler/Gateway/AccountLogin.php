<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-02
 * Time: 16:02
 */

namespace JoseChan\UserLogin\Handler\Gateway;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use JoseChan\UserLogin\Handler\LoginAbstract;

/**
 * 账密登录
 * Class AccountLogin
 * @package JoseChan\UserLogin\Handler\Gateway
 */
class AccountLogin extends LoginAbstract
{

    //不自动注册
    protected $auto_register = false;

    /**
     * 登录逻辑
     * @param array $form
     * @return Model
     * @throws \Exception
     */
    public function login(array $form): Model
    {
        $model = config("user_login.account.login_model");
        if (class_exists($model)) {
            $user = $model::query()->where("username", "=", $form["username"])->first();
            if (Hash::check($form['password'], $user->password)) {
                return $user;
            } else {
                throw new \Exception("账号密码不正确");
            }
        } else {
            return new $model();
        }
    }

    /**
     * 注册逻辑
     * @param array $form
     * @return Model
     * @throws \Exception
     */
    public function register(array $form): Model
    {
        if ($form['password'] != $form['password_confirm']) {
            return null;
        }
        $account_model = config("user_login.account.login_model");

        $user = $account_model::query()->where("username", "=", $form['username']);

        if ($user) {
            throw new \Exception("账号已注册");
        }

        $account = [
            'username' => $form['username'],
            'password' => Hash::make($form['password']),
        ];

        $user_profile = [
            "nickname" => $form['username'],
            "sex" => 0,
            "language" => "zh_cn",
            "city" => "",
            "country" => "",
            "province" => "",
            "headimgurl" => "",
            "channel_id" => isset($form['channel_id']) ? $form['channel_id'] : 0,
        ];

        $user_model = config("user_login.jwt.user_model");

        if (!class_exists($user_model) || !class_exists($account_model)) {
            throw new \Exception("系统错误");
        }

        /** @var Model $user */
        $user = new $user_model($user_profile);

        $connection = $user->getConnection();
        $connection->beginTransaction();

        if ($user->save()) {
            $user_id = $user->id;

            $account['user_id'] = $user_id;

            $user_account = new $account_model($account);

            if ($user_account->save()) {
                $connection->commit();
                return $user;
            }
        }

        $connection->rollBack();

        throw new \Exception("注册失败");

    }

    public function loginValidate(): array
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }

    public function registerValidate(): array
    {
        return [
            "username" => "required",
            "password" => "required",
            "password_confirm" => "required"
        ];
    }

    public function getLoginData(array $form): array
    {
        return $form;
    }

    public function getRegisterData(array $form): array
    {
        return $form;
    }
}
