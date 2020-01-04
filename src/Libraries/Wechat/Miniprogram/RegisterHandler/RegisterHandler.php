<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 11:22
 */

namespace JoseChan\UserLogin\Libraries\Wechat\Miniprogram\RegisterHandler;


use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constant\JWTKey;

class RegisterHandler extends AbstractHandler
{

    public function handler(array $user_info)
    {
        $config = JWTKey::getConfigs();
        $model = $config['user_model'];

        /** @var Model $user */
        $user = new $model();

        $user->openid = $user_info['openid'];
        if($user->save()){
            return $user;
        }
        
        return false;

    }
}