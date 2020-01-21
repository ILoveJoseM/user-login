<?php
namespace JoseChan\UserLogin\Middleware;

use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constant\ErrorCode;
use JoseChan\UserLogin\Constant\JWTKey;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use JoseChan\UserLogin\Constants\User;

class JWTDispatch
{

    public function handle(Request $request, \Closure $next)
    {

        $content_type = $request->header("content_type");

        if($content_type == "application/json"){
            $body = (string)$request->getContent();

            $params = json_decode($body, true);

            if($params){
                $request->request->add($params);
            }
        }

        $token = $request->get('token');
        if ($token) {

            $config = JWTKey::getConfigs();
            // 只做拆分获取用户ID，不判断可用性
            try {
                $decoded = (array)JWT::decode($token, $config['key'], [$config['alg']]);
                $user_id = isset($decoded['aud']) ? (string)$decoded['aud'] : 0;
                $extra = isset($decoded['data']) ? (string)$decoded['data'] : [];
                if (!empty($user_id)) {
                    /** @var string $user_model */
                    $user_model = $config['user_model'];
                    /** @var Model $user */
                    $user = $user_model::find($user_id);
                    if (!empty($user)) {
                        User::$info = $user;
                        User::$extra = $extra;
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
