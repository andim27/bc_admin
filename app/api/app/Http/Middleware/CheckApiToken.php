<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (env('APP_ENV') === 'testing') {
            JWTAuth::setRequest($request);
        }

        return $this->getAuthUser($request, $next);
    }

    /**
     * @param $request
     * @param $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser($request, $next)
    {
        if(!$request->is('*authenticate*')){
            try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], Response::HTTP_NOT_FOUND);
                }

            } catch (TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

            } catch (TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

            }

            $token = JWTAuth::getToken();

            if(Cache::get($token) != $user->id){
                return response()->json(['token_invalid'], Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }

}
