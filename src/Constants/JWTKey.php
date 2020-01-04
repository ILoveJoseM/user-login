<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-02
 * Time: 15:45
 */

namespace JoseChan\UserLogin\Constant;


class JWTKey
{

    public static function getConfigs()
    {
        $config = config("user_login.jwt");

        if(!$config) {
            $config = require "../../config/user_login.php";
            $config = $config['jwt'];
        }

        return $config;
    }
}