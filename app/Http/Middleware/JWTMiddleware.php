<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

    class JWTMiddleware extends BaseMiddleware
    {

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next, ...$roles)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                    return response()->json(['status' => 'Token is Invalid']);
                } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json(['status' => 'Token is Expired']);
                } else {
                    return response()->json(['status' => 'Authorization Token not found']);
                }
            }

            if ($user && in_array($user->isAdmin, $roles)) {
                return $next($request);
            }
            return $this->unauthorized();
        }

        private function unauthorized($message = null)
        {
            return response()->json([
                'message' => $message ? $message : 'You are unauthorized to access this resource',
                'success' => false
            ], 401);
        }
    }
