<?php namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Session;

class AuthController extends ApiController {

    /**
     * @SWG\Get(
     *   path="/api/auth/{param}&{password}",
     *   tags={"Auth"},
     *   operationId="auth",
     *   summary="User authentication",
     *   @SWG\Parameter(
     *     name="param",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found",
     *   ),
     *   @SWG\Response(
     *     response="403",
     *     description="Access denied",
     *   ),
     * )
     */
    public function auth($param, $password)
    {
        if (! $param || ! $password) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            if ($user = User::orWhere('username', '=', $param)->orWhere('email', '=', $param)->first()) {
                return $this->_auth($user, $password);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @SWG\Get(
     *   path="/api/authVipVip/{phone_vipvip}&{password}",
     *   tags={"Auth"},
     *   operationId="auth_vipvip",
     *   summary="User VipVip authentication",
     *   @SWG\Parameter(
     *     name="phone_vipvip",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found",
     *   ),
     *   @SWG\Response(
     *     response=402,
     *     description="User does not have pack",
     *   ),
     *   @SWG\Response(
     *     response="403",
     *     description="Access denied",
     *   ),
     * )
     */
    public function authVipVip($phoneVipVip, $password)
    {
        if (! $phoneVipVip || ! $password) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $phoneVipVip);
            if ($user = User::orWhere('phoneNumber2', '=', $phone)->orWhere('phoneNumber2', '=', '+' . $phone)->first()) {
                if ($user->getRepository()->havePack()) {
                    $salesCount = Sale::where('idUser', '=', new ObjectID($user->_id))
                            ->where('reduced', '=', true)
                            ->where('type', '=', Sale::TYPE_CREATED)
                            ->whereIn('product', [1, 2, 3, 21, 22, 36, 37, 38, 39])
                            ->count();
                    return $salesCount > 0 ? $this->_auth($user, $password) : Response(['error' => 'User does not have pack'], Response::HTTP_PAYMENT_REQUIRED);
                } else {
                    return Response(['error' => 'User does not have pack'], Response::HTTP_PAYMENT_REQUIRED);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @SWG\Get(
     *   path="/api/authWellness/{phone_wellness}&{password}",
     *   tags={"Auth"},
     *   operationId="auth_wellness",
     *   summary="User Wellness authentication",
     *   @SWG\Parameter(
     *     name="phone_wellness",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found",
     *   ),
     *   @SWG\Response(
     *     response=402,
     *     description="User does not have pack",
     *   ),
     *   @SWG\Response(
     *     response="403",
     *     description="Access denied",
     *   ),
     * )
     */
    public function authWellness($phoneWellness, $password)
    {
        if (! $phoneWellness || ! $password) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $phoneWellness);
            if ($user = User::orWhere('phoneWellness', '=', $phone)->orWhere('phoneWellness', '=', '+' . $phone)->first()) {
                if ($user->getRepository()->havePack()) {
                    $salesCount = Sale::where('idUser', '=', new ObjectID($user->_id))
                        ->where('reduced', '=', true)
                        ->where('type', '=', Sale::TYPE_CREATED)
                        ->whereIn('product', [3, 19, 16, 17, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 31, 32, 34, 35, 36, 37, 38, 39])
                        ->count();
                    return $salesCount > 0 ? $this->_auth($user, $password) : Response(['error' => 'User does not have pack'], Response::HTTP_PAYMENT_REQUIRED);
                } else {
                    return Response(['error' => 'User does not have pack'], Response::HTTP_PAYMENT_REQUIRED);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @SWG\Get(
     *   path="/api/auth/admin/{email}&{password}",
     *   tags={"Auth"},
     *   operationId="auth_admin",
     *   summary="User Admin authentication",
     *   @SWG\Parameter(
     *     name="email",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="path",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found",
     *   ),
     *   @SWG\Response(
     *     response=402,
     *     description="User is not admin",
     *   ),
     *   @SWG\Response(
     *     response="403",
     *     description="Access denied",
     *   ),
     * )
     */
    public function authAdmin($email, $password)
    {
        if (! $email || ! $password) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            if ($user = User::where('email', '=', $email)->first()) {
                return $user->isAdmin ? $this->_auth($user, $password) : Response(['error' => 'User is not admin'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @param User $user
     * @param $password
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    private function _auth(User $user, $password)
    {
        if ($user->getRepository()->checkPassword($password)) {
            Session::put('user_id', $user->_id);
            $user->lastDateLogin = new UTCDateTime(time() * 1000);
            $user->save();
            return Response(['id' => $user->_id], Response::HTTP_OK);
        } else {
            return Response(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }
    }
}