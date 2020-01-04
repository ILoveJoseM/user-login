<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 11:22
 */

namespace JoseChan\UserLogin\Libraries\Wechat\Miniprogram\RegisterHandler;


use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Mixed_;

abstract class AbstractHandler
{

    /**
     * 实现注册逻辑，返回注册是否成功，失败返回false，成功返回相应用户对象
     * @param array $user_info
     * @return bool|Model
     */
    abstract public function handler(array $user_info);
}