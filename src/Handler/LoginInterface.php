<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-02
 * Time: 10:37
 */

namespace JoseChan\UserLogin\Handler;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

/**
 * 登录注册接口
 * Interface LoginInterface
 * @package JoseChan\UserLogin\Handler
 */
interface LoginInterface
{

    /**
     * 登录
     * @param $form
     * @return Model
     */
    public function login(array $form):Model;

    /**
     * 注册
     * @param $form
     * @return mixed
     */
    public function register(array $form):Model;

    /**
     * 注册失败处理
     * @return Response
     */
    public function failsRegisterHandler():Response;

    /**
     * 登录失败处理
     * @return Response
     */
    public function failsLoginHandler():Response;

    /**
     * 注册成功处理
     * @param Model $user
     * @return Response
     */
    public function successRegisterHandler(Model $user):Response;

    /**
     * 登录成功处理
     * @param Model $user
     * @return Response
     */
    public function successLoginHandler(Model $user):Response;

    /**
     * 登录参数校验
     * @return mixed
     */
    public function loginValidate():array;

    /**
     * 注册参数校验
     * @return mixed
     */
    public function registerValidate():array;

    /**
     * 获取登录参数
     * @param array $form
     * @return array
     */
    public function getLoginData(array $form) : array;

    /**
     * 获取注册参数
     * @param array $form
     * @return array
     */
    public function getRegisterData(array $form) : array;
}