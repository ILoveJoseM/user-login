<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/8
 * Time: 15:05
 */

namespace JoseChan\UserLogin\Middleware;

use JoseChan\UserLogin\Constant\ErrorCode;
use Illuminate\Http\Request;
use JoseChan\UserLogin\Constants\User;

class LoginCheck
{
    public function handle(Request $request, \Closure $next, $gurad = null)
    {
        if (!empty(User::$info)) {
            $response = $next($request);
        } else {
            return \response()->json([
                ErrorCode::msg(ErrorCode::USER_NOT_LOGIN),
                ErrorCode::USER_NOT_LOGIN,
                [],
                200
            ]);
        }

        return $response;
    }
}
