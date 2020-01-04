<?php
namespace JoseChan\UserLogin\Middleware;

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
                if (!empty($user_id)) {
                    $user_model = $config['user_model'];
                    $user = $user_model::where($config['primary_key'], "=", $user_id)->get();
                    if (!empty($user)) {
                        User::$info = $user;
                    }
                }
            } catch (\Exception $e) {
                User::$info = null;
            }
        }

        try {
            $response = $next($request);
        } catch (\Exception $e) {
            $response = response()->json([
                "msg" => ErrorCode::msg(ErrorCode::SYSTEM_ERROR), "code"=>ErrorCode::SYSTEM_ERROR, "data"=>[]
            ]);
        }

        return $response;
    }
}
