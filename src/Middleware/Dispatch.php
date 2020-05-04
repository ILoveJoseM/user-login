<?php

namespace JoseChan\UserLogin\Middleware;

use Firebase\JWT\ExpiredException;
use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constant\ErrorCode;
use JoseChan\UserLogin\Constant\JWTKey;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use JoseChan\UserLogin\Constants\User;

class Dispatch
{

    public function handle(Request $request, \Closure $next)
    {

        $token = $request->header('Authorization');
        if ($token) {

            $config = JWTKey::getConfigs();
            // 只做拆分获取用户ID，不判断可用性
            try {
                $decoded = (array)JWT::decode($token, $config['key'], [$config['alg']]);
                $user_id = isset($decoded['aud']) ? (string)$decoded['aud'] : 0;
                $extra = isset($decoded['data']) ? (array)$decoded['data'] : [];
                if (!empty($user_id)) {
                    /** @var string $user_model */
                    $user_model = $config['user_model'];
                    if((new $user_model()) instanceof Model){
                        /** @var Model $user */
                        $user = $user_model::find($user_id);
                        if (!empty($user)) {
                            User::$info = $user;
                            User::$extra = $extra;
                        }
                    }
                }
            } catch (ExpiredException $exception) {
                return \response()->json([
                    "msg" => ErrorCode::msg(ErrorCode::USER_NOT_LOGIN),
                    "code" => ErrorCode::USER_NOT_LOGIN,
                    "data" => [],
                ]);

            } catch (\Exception $e) {
                User::$info = null;
            }
        }

        try {
            $response = $next($request);
        } catch (\Exception $e) {
            $response = response()->json([
                "msg" => ErrorCode::msg(ErrorCode::SYSTEM_ERROR), "code" => ErrorCode::SYSTEM_ERROR, "data" => []
            ]);
        }

        return $response;
    }
}
