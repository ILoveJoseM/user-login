<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-02
 * Time: 16:02
 */

namespace JoseChan\UserLogin\Handler\Gateway;


use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use JoseChan\UserLogin\Handler\LoginAbstract;
use JoseChan\UserLogin\Models\Users;

class AccountLogin extends LoginAbstract
{

    public function login(array $form): Model
    {
        if (Auth::guard('web')->attempt($form)) {//登录成功

            $user = Users::query()->where("username", "=", $form["username"])->first();

            return $user;
        }

        return new Users();
    }

    public function register(array $form): Model
    {
        // TODO: Implement register() method.
    }

    public function userInfo(): Model
    {
        // TODO: Implement userInfo() method.
    }

    public function failsRegisterHandler(): Response
    {
        // TODO: Implement failsRegisterHandler() method.
    }

    public function failsLoginHandler(): Response
    {
        // TODO: Implement failsLoginHandler() method.
    }

    public function successRegisterHandler(Model $user): Response
    {
        // TODO: Implement successRegisterHandler() method.
    }

    public function successLoginHandler(Model $user): Response
    {
        // TODO: Implement successLoginHandler() method.
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
        // TODO: Implement registerValidate() method.
    }

    public function getLoginData(array $form): array
    {
        // TODO: Implement getLoginData() method.
    }

    public function getRegisterData(array $form): array
    {
        // TODO: Implement getRegisterData() method.
    }
}
