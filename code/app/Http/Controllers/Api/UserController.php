<?php namespace App\Http\Controllers\Api;

use App\Jobs\CountPartners;
use App\Models\Doc;
use App\Models\MailQueue;
use App\Models\MailTemplate;
use App\Models\News;
use App\Models\Note;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use MongoDB\BSON\ObjectID;
use App\Http\Views\Api as ModelViews;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Mail;
use MongoDB\Driver\Exception\InvalidArgumentException;

class UserController extends ApiController {

    /**
     * @SWG\Post(
     *   path="/api/user",
     *   tags={"Users"},
     *   operationId="registration",
     *   summary="User registration",
     *   @SWG\Parameter(
     *     name="sponsor",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="username",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="fname",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="sname",
     *     in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="country",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="finPassword",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="skype",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phoneViber",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phoneWhatsApp",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phoneTelegram",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phoneFB",
     *     in="formData",
     *     required=false,
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
     *     description="Sponsor not found",
     *   ),
     *   @SWG\Response(
     *     response=409,
     *     description="Login or email already exists",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function register(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'sponsor' => 'required',
            'username' => 'required',
            'email' => 'required',
            'fname' => 'required',
            'sname' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'finPassword' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $sponsor = User::orWhere('username', '=', $requestParams['sponsor'])
                ->orWhere('email', '=', $requestParams['sponsor'])
                ->orWhere('accountId', '=', $requestParams['sponsor'])
                ->first();

            if (! $sponsor) {
                return Response(['error' => 'Sponsor not found'], Response::HTTP_NOT_FOUND);
            } else {
                if ($user = User::createUser($sponsor, $requestParams)) {

                    dispatch(new CountPartners($user));

                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                } else {
                    return Response(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }
    }

    /**
     * @SWG\Get(
     *   path="/api/user/{param}",
     *   tags={"Users"},
     *   operationId="user_info",
     *   summary="User information",
     *   @SWG\Parameter(
     *     name="param",
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
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function user($param)
    {
        if (! $param) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            $user = User::find($param);

            if (!$user) {
                $phone = str_replace('+', '', $param);
                $user = User::orWhere('username', '=', $param)
                    ->orWhere('accountId', '=', intval($param))
                    ->orWhere('email', '=', $param)
                    ->orWhere('phoneNumber', '=', $phone)
                    ->orWhere('phoneNumber', '=', '+' . $phone)
                    ->orWhere('phoneNumber2', '=', $phone)
                    ->orWhere('phoneNumber2', '=', '+' . $phone)
                    ->orWhere('phoneWellness', '=', $phone)
                    ->orWhere('phoneWellness', '=', '+' . $phone)
                    ->first();
            }

            if ($user) {
                $userView = new ModelViews\User($user);

                return Response($userView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function users($offset = 0, $limit)
    {
        $offset = intval($offset);
        $limit = intval($limit);
        if ($limit) {
            $users = User::offset($offset)
                ->limit($limit)
                ->get();

            $result = [];
            foreach ($users as $user) {
                $userView = new ModelViews\User($user);
                $result[] = $userView->get();
            }
            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @SWG\Post(
     *   path="/api/user/mobileRegistration",
     *   tags={"Users"},
     *   operationId="mobileRegistration",
     *   summary="User registration for mobile",
     *   @SWG\Parameter(
     *     name="sponsor_phone",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="name",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="country",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
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
     *     description="Sponsor not found",
     *   ),
     *   @SWG\Response(
     *     response=409,
     *     description="Login or email already exists",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function mobileRegister(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'sponsor_phone' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'country' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $sponsorPhone = str_replace('+', '', $requestParams['sponsor_phone']);
            $email = $requestParams['email'];
            $username = mb_strtolower($requestParams['name']);
            $phone = str_replace('+', '', $requestParams['phone']);

            if (User::orWhere('phoneNumber', '=', $phone)
                ->orWhere('phoneNumber', '=', '+' . $phone)
                ->orWhere('phoneNumber2', '=', $phone)
                ->orWhere('phoneNumber2', '=', '+' . $phone)
                ->orWhere('phoneWellness', '=', $phone)
                ->orWhere('phoneWellness', '=', '+' . $phone)
                ->orWhere('email', '=', $email)
                ->orWhere('username', '=', $username)
                ->first())
            {
                return Response(['error' => 'User already exists'], Response::HTTP_CONFLICT);
            } else {
                if (! $sponsor = User::orWhere('phoneNumber', '=', $sponsorPhone)
                    ->orWhere('phoneNumber', '=', '+' . $sponsorPhone)
                    ->orWhere('phoneNumber2', '=', $sponsorPhone)
                    ->orWhere('phoneNumber2', '=', '+' . $sponsorPhone)
                    ->orWhere('phoneWellness', '=', $sponsorPhone)
                    ->orWhere('phoneWellness', '=', '+' . $sponsorPhone)
                    ->first()
                ) {
                    return Response(['error' => 'Sponsor not found'], Response::HTTP_NOT_FOUND);
                } else {
                    $requestParams['username'] = $requestParams['name'];
                    if (User::createUser($sponsor, $requestParams)) {
                        return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                    } else {
                        return Response(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
            }
        }
    }

    public function linkedAccounts($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                return Response($user->linkedAccounts, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function personalPartners($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                return Response($user->getRepository()->getPersonalPartners(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function mobilePersonalPartners($userId)
    {
        $users = User::limit(20)
            ->select('_id', 'country', 'created')
            ->orderBy('created', 'desc')
            ->get();

        $settings = Settings::first();

        if ($settings) {
            $countries = [];
            foreach ($settings->countries as $country) {
                $countries[mb_strtolower($country['alpha2'])] = $country['name'];
            }
        }

        foreach ($users as $key => $user) {
            if ($user->country && isset($countries[$user->country]) && $countries[$user->country]) {
                $user->country = $countries[$user->country];
            }
            if ($user->created && ! is_string($user->created)) {
                $user->created = $user->created->toDateTime()->format('d.m.Y H:i:s');
            } else {
                $user->created = '';
            }
        }

        return Response($users, Response::HTTP_OK);
    }

    public function checkFinancialPassword($email, $password)
    {
        if ($email && $password) {
            if ($user = User::where('email', '=', mb_strtolower($email))->first()) {
                if ($user->getRepository()->checkFinancialPassword($password)) {
                    return Response(['id' => $user->_id], Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Invalid financial password'], Response::HTTP_FORBIDDEN);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkByMessenger($messenger, $phone)
    {
        if ($messenger && $phone) {
            $phone = str_replace('+', '', $phone);
            switch($messenger) {
                case User::MESSENGER_FACEBOOK:
                    $user = User::orWhere('settings.phoneFB', '=', $phone)->orWhere('settings.phoneFB', '=', '+' . $phone)->first();
                break;
                case User::MESSENGER_TELEGRAM:
                    $user = User::orWhere('settings.phoneTelegram', '=', $phone)->orWhere('settings.phoneTelegram', '=', '+' . $phone)->first();
                break;
                case User::MESSENGER_VIBER:
                    $user = User::orWhere('settings.phoneViber', '=', $phone)->orWhere('settings.phoneViber', '=', '+' . $phone)->first();
                break;
                case User::MESSENGER_WHATSAPP:
                    $user = User::orWhere('settings.phoneWhatsApp', '=', $phone)->orWhere('settings.phoneWhatsApp', '=', '+' . $phone)->first();
                break;
                default:
                    return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
                break;
            }
            if (isset($user) && $user) {
                $userView = new ModelViews\User($user);

                return Response($userView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkForVipzona($param)
    {
        if (! $param) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            $param = mb_strtolower($param);

            $user = User::orWhere('username', '=', $param)->orWhere('email', '=', $param)->first();

            if ($user) {
                if ($sale = Sale::where('idUser', '=', new ObjectID($user->_id))
                    ->whereIn('product', [1, 2, 3, 19, 20, 21, 22, 23, 35])
                    ->select('dateCreate')
                    ->orderBy('dateCreate', 'desc')
                    ->first()
                ) {
                    $result = [
                        'productDate' => $sale->dateCreate->toDateTime()->format('d.m.Y H:i:s'),
                        'sponsor' => [
                            'firstName'   => $user->sponsor ? $user->sponsor->firstName   ? $user->sponsor->firstName   : '' : '',
                            'secondName'  => $user->sponsor ? $user->sponsor->secondName  ? $user->sponsor->secondName  : '' : '',
                            'username'    => $user->sponsor ? $user->sponsor->username    ? $user->sponsor->username    : '' : '',
                            'email'       => $user->sponsor ? $user->sponsor->email       ? $user->sponsor->email       : '' : '',
                            'phoneNumber' => $user->sponsor ? $user->sponsor->phoneNumber ? $user->sponsor->phoneNumber : '' : '',
                            'status'      => $user->sponsor ? $user->sponsor->statistics['pack'] : 0
                        ]
                    ];

                    return Response($result, Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Sale not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        if ((isset($requestParams['accountId']) && $requestParams['accountId']) || (isset($requestParams['iduser']) && $requestParams['iduser'])) {
            if (isset($requestParams['accountId']) && intval($requestParams['accountId'])) {
                $user = User::where('accountId', '=', intval($requestParams['accountId']))->first();
            }

            if (!isset($user)) {
                if (isset($requestParams['iduser']) && $requestParams['iduser']) {
                    $user = User::find($requestParams['iduser']);
                }
            }

            if (isset($user) && $user) {
                if (isset($requestParams['username']) && $requestParams['username']) {
                    $username = mb_strtolower($requestParams['username']);
                    if (! User::where('username', '=', $username)->where('_id', '!=', new ObjectID($user->_id))->first()) {
                        $user->username = $username;
                    } else {
                        return Response(['error' => 'User already exists'], Response::HTTP_CONFLICT);
                    }
                }
                if (isset($requestParams['email']) && $requestParams['email']) {
                    $email = mb_strtolower($requestParams['email']);
                    if (! User::where('email', '=', $email)->where('_id', '!=', new ObjectID($user->_id))->first()) {
                        $user->email = $email;
                    } else {
                        return Response(['error' => 'User already exists'], Response::HTTP_CONFLICT);
                    }
                }
                if (isset($requestParams['fname'])) {
                    $user->firstName = $requestParams['fname'];
                }
                if (isset($requestParams['sname'])) {
                    $user->secondName = $requestParams['sname'];
                }
                if (isset($requestParams['phone'])) {
                    $user->phoneNumber = $requestParams['phone'];
                }
                if (isset($requestParams['phone2'])) {
                    $user->phoneNumber2 = $requestParams['phone2'];
                }
                if (isset($requestParams['phoneWellness'])) {
                    $user->phoneWellness = $requestParams['phoneWellness'];
                }
                if (isset($requestParams['birthday']) && $birthday = strtotime($requestParams['birthday'])) {
                    $user->birthday = new UTCDateTime($birthday * 1000);
                }
                if (isset($requestParams['skype'])) {
                    $user->skype = $requestParams['skype'];
                }
                if (isset($requestParams['country'])) {
                    $user->country = mb_strtolower($requestParams['country']);
                }
                if (isset($requestParams['state'])) {
                    $user->state = $requestParams['state'];
                }
                if (isset($requestParams['city'])) {
                    $user->city = $requestParams['city'];
                }
                if (isset($requestParams['address'])) {
                    $user->address = $requestParams['address'];
                }
                if (isset($requestParams['zipCode'])) {
                    $user->zipCode = $requestParams['zipCode'];
                }
                if (isset($requestParams['cardNumber'])) {
                    $user->cardNumber = $requestParams['cardNumber'];
                }
                if (isset($requestParams['layout'])) {
                    $user->setAttribute('settings.layout', $requestParams['layout']);
                }
                if (isset($requestParams['showMobile'])) {
                    $user->setAttribute('settings.showMobile', 1);
                }
                if (isset($requestParams['showEmail'])) {
                    $user->setAttribute('settings.showEmail', 1);
                }
                if (isset($requestParams['showName'])) {
                    $user->setAttribute('settings.showName', 1);
                }
                if (isset($requestParams['deliveryEMail'])) {
                    $user->setAttribute('settings.deliveryEMail', $requestParams['deliveryEMail']);
                }
                if (isset($requestParams['deliverySMS'])) {
                    $user->setAttribute('settings.deliverySMS', $requestParams['deliverySMS']);
                }
                if (isset($requestParams['notifyAboutCheck'])) {
                    $user->setAttribute('settings.notifyAboutCheck', $requestParams['notifyAboutCheck']);
                }
                if (isset($requestParams['notifyAboutReceiptsMoney'])) {
                    $user->setAttribute('settings.notifyAboutReceiptsMoney', $requestParams['notifyAboutReceiptsMoney']);
                }
                if (isset($requestParams['notifyAboutReceiptsPoints'])) {
                    $user->setAttribute('settings.notifyAboutReceiptsPoints', $requestParams['notifyAboutReceiptsPoints']);
                }
                if (isset($requestParams['notifyAboutOtherNews'])) {
                    $user->setAttribute('settings.notifyAboutOtherNews', $requestParams['notifyAboutOtherNews']);
                }
                if (isset($requestParams['notifyAboutEndActivity'])) {
                    $user->setAttribute('settings.notifyAboutEndActivity', $requestParams['notifyAboutEndActivity']);
                }
                if (isset($requestParams['notifyAboutJoinPartner'])) {
                    $user->setAttribute('settings.notifyAboutJoinPartner', $requestParams['notifyAboutJoinPartner']);
                }
                if (isset($requestParams['selectedLang'])) {
                    $user->setAttribute('settings.selectedLang', $requestParams['selectedLang']);
                }
                if (isset($requestParams['charityPercent'])) {
                    $user->setAttribute('settings.charityPercent', $requestParams['charityPercent']);
                }
                if (isset($requestParams['manualRegistrationControl'])) {
                    $user->setAttribute('settings.manualRegistrationControl', $requestParams['manualRegistrationControl']);
                }
                if (isset($requestParams['phoneWhatsApp'])) {
                    $user->setAttribute('settings.phoneWhatsApp', $requestParams['phoneWhatsApp']);
                }
                if (isset($requestParams['phoneViber'])) {
                    $user->setAttribute('settings.phoneViber', $requestParams['phoneViber']);
                }
                if (isset($requestParams['phoneTelegram'])) {
                    $user->setAttribute('settings.phoneTelegram', $requestParams['phoneTelegram']);
                }
                if (isset($requestParams['phoneFB'])) {
                    $user->setAttribute('settings.phoneFB', $requestParams['phoneFB']);
                }
                if (isset($requestParams['timeZone'])) {
                    $user->setAttribute('settings.timeZone', $requestParams['timeZone']);
                }
                if (isset($requestParams['onMapX'])) {
                    $user->setAttribute('settings.onMapX', $requestParams['onMapX']);
                }
                if (isset($requestParams['onMapY'])) {
                    $user->setAttribute('settings.onMapY', $requestParams['onMapY']);
                }
                if (isset($requestParams['site'])) {
                    $user->setAttribute('links.site', mb_strtolower($requestParams['site']));
                }
                if (isset($requestParams['odnoklassniki'])) {
                    $user->setAttribute('links.odnoklassniki', mb_strtolower($requestParams['odnoklassniki']));
                }
                if (isset($requestParams['vk'])) {
                    $user->setAttribute('links.vk', mb_strtolower($requestParams['vk']));
                }
                if (isset($requestParams['fb'])) {
                    $user->setAttribute('links.fb', mb_strtolower($requestParams['fb']));
                }
                if (isset($requestParams['youtube'])) {
                    $user->setAttribute('links.youtube', mb_strtolower($requestParams['youtube']));
                }
                if (isset($requestParams['cards'])) {
                    $user->cards = $requestParams['cards'];
                }
                if (isset($requestParams['avatar'])) {
                    $user->avatar = $requestParams['avatar'];
                }
                if (isset($requestParams['isAdmin'])) {
                    $user->isAdmin = $requestParams['isAdmin'];
                }
                if (isset($requestParams['sideToNextUser'])) {
                    $user->sideToNextUser = intval($requestParams['sideToNextUser']);
                }
                if (isset($requestParams['landingAnalytics'])) {
                    $user->setAttribute('landing.analytics', $requestParams['landingAnalytics']);
                }
                if (isset($requestParams['landingAnalytics2'])) {
                    $user->setAttribute('landing.analytics2', $requestParams['landingAnalytics2']);
                }
                if (isset($requestParams['landingAnalyticsVipVip'])) {
                    $user->setAttribute('landing.analytics_vipvip', $requestParams['landingAnalyticsVipVip']);
                }
                if (isset($requestParams['landingAnalyticsWebwellnessRu'])) {
                    $user->setAttribute('landing.analytics_webwellness_ru', $requestParams['landingAnalyticsWebwellnessRu']);
                }
                if (isset($requestParams['landingAnalyticsWebwellnessNet'])) {
                    $user->setAttribute('landing.analytics_webwellness_net', $requestParams['landingAnalyticsWebwellnessNet']);
                }

                if (isset($requestParams['nextRegistration']) && $requestParams['nextRegistration']) {
                    if ($nextRegistrationUser = User::where('username', '=', $requestParams['nextRegistration'])->select('_id')->first()) {
                        if ($spilover = $user->getRepository()->getSpilover(true)) {
                            foreach ($spilover as $s) {
                                $spiloverId = strval($s);
                                if ($spiloverId == $nextRegistrationUser->_id) {
                                    $spiloverUser = User::find($s);
                                    if ($spiloverUser) {
                                        $user->nextRegistration = $nextRegistrationUser;
                                    }
                                }
                            }
                        }
                    }
                }

                if (isset($requestParams['autoExtensionBS'])) {
                    $user->autoExtensionBS = $requestParams['autoExtensionBS'];
                }

                if ($user->save()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                } else {
                    return Response(['error' => 'User not updated'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function withTokens()
    {
        $users = User::where('statistics.tokens', '>', 0)
            ->orderBy('username', 'asc')
            ->select('username', 'firstName', 'secondName', 'country', 'city', 'statistics.tokens')
            ->get();

        return Response($users, Response::HTTP_OK);
    }

    public function readNews(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'idUser' => 'required',
            'idNews' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $user = User::find($requestParams['idUser']);

            if (! $user) {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $news = News::find($requestParams['idNews']);

            if (! $news) {
                return Response(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
            } else {
                if (! in_array($news->_id, $user->system['readNews'])) {
                    $user->push('system.readNews', $news->_id);

                    $news = [];
                    foreach ($user->system['readNews'] as $readNews) {
                        try {
                            $news[] = new ObjectID($readNews);
                        } catch (InvalidArgumentException $exception) {
                        }
                    }
                    $unreadNews = News::raw()->count([
                        '_id' => ['$nin' => $news],
                        'lang' => $user->settings['selectedLang'],
                        'dateOfPublication' => ['$lte' => new UTCDateTime(time() * 1000)]
                    ]);
                    $user->setAttribute('statistics.unreadNews', $unreadNews);

                    $user->save();
                }

                $newsView = new ModelViews\News($news);

                return Response($newsView->get(), Response::HTTP_OK);
            }
        }
    }

    public function readPromotion(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'idUser' => 'required',
            'idPromotion' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $user = User::find($requestParams['idUser']);

            if (! $user) {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $promotion = Promotion::find($requestParams['idPromotion']);

            if (! $promotion) {
                return Response(['error' => 'Promotion not found'], Response::HTTP_NOT_FOUND);
            } else {
                if (! in_array($promotion->_id, $user->system['readPromotions'])) {
                    $user->push('system.readPromotions', $promotion->_id);

                    $promotions = [];
                    foreach ($user->system['readPromotions'] as $readPromotion) {
                        try {
                            $promotions[] = new ObjectID($readPromotion);
                        } catch (InvalidArgumentException $exception) {
                        }
                    }

                    $unreadPromotions = Promotion::raw()->count([
                        '_id' => ['$nin' => $promotions],
                        'lang' => $user->settings['selectedLang'],
                        'dateStart' => ['$lte' => new UTCDateTime(time() * 1000)],
                        'dateFinish' => ['$gte' => new UTCDateTime(time() * 1000)]
                    ]);

                    $user->setAttribute('statistics.unreadPromotions', $unreadPromotions);

                    $user->save();
                }

                $promotionView = new ModelViews\Promotion($promotion);

                return Response($promotionView->get(), Response::HTTP_OK);
            }
        }
    }

    public function userList()
    {
        $users = User::select(
            '_id',
            'accountId',
            'username',
            'firstName',
            'secondName',
            'email',
            'skype',
            'phoneNumber',
            'phoneNumber2',
            'phoneWellness',
            'created',
            'isDelete',
            'qualification',
            'country',
            'city',
            'status',
            'rank',
            'expirationDateBS'
        )->get();

        $result = [];
        foreach ($users as $user) {
            $userView = new ModelViews\User($user);
            $result[] = $userView->get();
        }

        return Response($result, Response::HTTP_OK);
    }

    public function userListQualification()
    {
        $users = User::where('rank', '>', 1)
        ->select(
            'accountId',
            'username',
            'created',
            'firstName',
            'secondName',
            'rank',
            'country',
            'city'
        )->orderBy('rank', 'asc')->get();

        $result = [];
        foreach ($users as $user) {
            $userView = new ModelViews\User($user);
            $result[] = $userView->get();
        }

        return Response($result, Response::HTTP_OK);
    }

    public function userListFull()
    {
        $users = User::select(
                'accountId',
                'username',
                'created',
                'firstName',
                'secondName',
                'rank',
                'country',
                'city'
            )->get();

        $result = [];
        foreach ($users as $user) {
            $userView = new ModelViews\User($user);
            $result[] = $userView->get();
        }

        return Response($result, Response::HTTP_OK);
    }

    public function adminList()
    {
        $users = User::where('isAdmin', '=', true)
            ->select(
                '_id',
                'accountId',
                'username',
                'firstName',
                'secondName',
                'email',
                'skype',
                'phoneNumber',
                'phoneNumber2',
                'phoneWellness',
                'moneys',
                'pointsLeft',
                'pointsRight',
                'lastDateLogin',
                'created',
                'sideToNextUser',
                'parentId',
                'chldrnLeftId',
                'chldrnRightId',
                'leftSideNumberUsers',
                'rightSideNumberUsers',
                'isDelete',
                'qualification',
                'zipCode',
                'country',
                'city',
                'state',
                'address',
                'side',
                'status',
                'sponsor',
                'settings',
                'links',
                'qualification',
                'firstPurchase',
                'bs',
                'autoExtensionBS',
                'personalBonus',
                'structBonus',
                'potential',
                'rank',
                'expirationDateBS',
                'partnersWithPurchases',
                'birthday',
                'statistics',
                'nextRegistration',
                'promotions',
                'warehouseName'
            )->get();

        $result = [];
        foreach ($users as $user) {
            $userView = new ModelViews\User($user);
            $result[] = $userView->get();
        }

        return Response($result, Response::HTTP_OK);
    }

    public function lastUser($userId, $side)
    {
        if ($userId && ($side == User::SIDE_RIGHT || $side == User::SIDE_LEFT)) {
            if ($user = User::find($userId)) {
                if ($lastUser = $user->getRepository()->getLastUser($side)) {
                    return Response($lastUser, Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Last user not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function docs($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                $docs = Doc::where('idUser', '=', new ObjectID($userId))
                    ->where('isDelete', '=', false)
                    ->orderBy('dateCreate', 'desc')
                    ->get();

                $result = [];
                foreach ($docs as $key => $d) {
                    $docView = new ModelViews\Doc($d);
                    $result[] = $docView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function notes($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                $notes = Note::where('author', '=', new ObjectID($userId))
                    ->where('isDelete', '=', false)
                    ->orderBy('dateCreate', 'desc')
                    ->orderBy('dateUpdate', 'desc')
                    ->get();

                $result = [];
                foreach ($notes as $key => $n) {
                    $noteView = new ModelViews\Note($n);
                    $result[] = $noteView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function careerHistory($dateFrom, $dateTo)
    {
        $dateFrom = strtotime($dateFrom);
        $dateTo = strtotime($dateTo);

        if ($dateFrom && $dateTo) {
            $dateTo = Carbon::createFromTimestamp($dateTo);
            $dateTo->setTime(23, 59, 59);
            $dateTo = $dateTo->timestamp;

            $users = User::where('careerHistory', '!=', [])->get();

            $result = [];
            foreach ($users as $user) {
                foreach ($user->careerHistory as $careerHistory) {
                    $careerHistoryDate = $careerHistory['date']->toDateTime();
                    if ($dateFrom <= $careerHistoryDate->getTimestamp() && $dateTo >= $careerHistoryDate->getTimestamp()) {
                        if (isset($careerHistory['career'])) {
                            $career = [
                                'rank' => $careerHistory['career']['rank'],
                                'date' => $careerHistoryDate->format('d.m.Y H:i:s')
                            ];
                        } else {
                            $career = [
                                'rank' => $careerHistory['rank'],
                                'date' => $careerHistoryDate->format('d.m.Y H:i:s')
                            ];
                        }

                        $result[] = [
                            'username' => $user->username,
                            'email' => $user->email,
                            'firstName' => $user->firstName,
                            'secondName' => $user->secondName,
                            'avatar' => $user->avatar,
                            'country' => $user->country,
                            'city' => $user->city,
                            'career' => $career
                        ];
                    }
                }
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function personalSpilover($userId, $level, $view)
    {
        if ($userId && $level && $view) {
            if ($user = User::find($userId)) {
                $personalSpilovers = $user->getRepository()->getPersonalSpilover($level);

                $result = [];
                foreach ($personalSpilovers as $personalSpilover) {
                    $userView = new ModelViews\User($personalSpilover);
                    $result[] = $userView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function upSpilover($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                $upSpilovers = $user->getRepository()->getUsersToMain();

                $result = [];
                foreach ($upSpilovers as $upSpilover) {
                    $userView = new ModelViews\User($upSpilover);
                    $result[] = $userView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function unlinkAccounts(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'idFrom' => 'required',
            'idTo' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $userFrom = User::find($requestParams['idFrom']);
            if (! $userFrom) {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
            $userTo = User::find($requestParams['idTo']);
            if (! $userTo) {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $linkedAccounts = [];
            if ($userTo->linkedAccounts) {
                foreach ($userTo->linkedAccounts as $userToLinkedAccount) {
                    $userToLinkedAccountId = $userToLinkedAccount['accountId'];
                    $userFromAccountId = $userFrom->accountId;
                    if ($userToLinkedAccountId != $userFromAccountId) {
                        $linkedAccounts[] = $userToLinkedAccount;
                    }
                }
            }

            $userTo->linkedAccounts = $linkedAccounts;

            if ($userTo->save()) {
                $userView = new ModelViews\User($userTo);

                return Response($userView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function linkAccounts(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'idFrom' => 'required',
            'idTo' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $userFrom = User::find($requestParams['idFrom']);
            if (! $userFrom) {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
            $userTo = User::find($requestParams['idTo']);
            if (! $userTo) {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            if (!isset($userTo->linkedAccounts)) {
                $userTo->linkedAccount = [];
            }

            $isset = false;
            foreach ($userTo->linkedAccounts as $userlinkedAccount) {
                if ($userlinkedAccount['accountId'] == $userFrom->accountId) {
                    $isset = true;
                }
            }

            if (!$isset) {
                $userTo->push('linkedAccounts', [
                    'accountId' => $userFrom->accountId,
                    'username' => $userFrom->username,
                    'email' => $userFrom->email,
                    'linkDate' => time() * 1000
                ]);
            }

            if ($userTo->save()) {
                $userView = new ModelViews\User($userTo);

                return Response($userView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function changePassword(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'iduser' => 'required',
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($user = User::find($requestParams['iduser'])) {
                switch($requestParams['type']) {
                    case User::PASSWORD_TYPE_MAIN:
                        $result = $user->getRepository()->changePassword($requestParams['oldPassword'], $requestParams['newPassword']);
                    break;
                    case User::PASSWORD_TYPE_FINANCIAL:
                        $result = $user->getRepository()->changeFinancialPassword($requestParams['oldPassword'], $requestParams['newPassword']);
                    break;
                }
                return $result ? Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK) : Response(Response::$statusTexts[Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function changeAndGetPassword($userId, $oldPassword, $newPassword, $type)
    {
        if ($userId && $oldPassword && $newPassword && ($type == User::PASSWORD_TYPE_MAIN || $type == User::PASSWORD_TYPE_FINANCIAL)) {
            if ($user = User::find($userId)) {
                switch($type) {
                    case User::PASSWORD_TYPE_MAIN:
                        $result = $user->getRepository()->changePassword($oldPassword, $newPassword);
                        break;
                    case User::PASSWORD_TYPE_FINANCIAL:
                        $result = $user->getRepository()->changeFinancialPassword($oldPassword, $newPassword);
                        break;
                }
                return $result ? Response($result, Response::HTTP_OK) : Response(Response::$statusTexts[Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function forLifestyle($email)
    {
        if ($email) {
            if (
                $user = User::where('email', '=', mb_strtolower($email))
                    ->select('avatar', 'firstName', 'secondName', 'username', 'email', 'phoneNumber', 'statistics.dateBuyPack', 'bs', 'expirationDateBS')
                    ->first()
            ) {
                if (isset($user->expirationDateBS)) {
                    if ($user->expirationDateBS && !is_string($user->expirationDateBS)) {
                        $expirationDate = $user->expirationDateBS->toDateTime()->format('d.m.Y H:i:s');
                    } else {
                        $expirationDate = '';
                    }
                }
                if (isset($user->statistics['dateBuyPack'])) {
                    if ($user->statistics['dateBuyPack'] && !is_string($user->statistics['dateBuyPack'])) {
                        $dateBuyPack = $user->statistics['dateBuyPack']->toDateTime()->format('d.m.Y H:i:s');
                    } else {
                        $dateBuyPack = '';
                    }
                }

                $result = [
                    'avatar' => $user->avatar,
                    'firstName' => $user->firstName,
                    'secondName' => $user->secondName,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phoneNumber' => $user->phoneNumber,
                    'dateBuyPack' => $dateBuyPack,
                    'isActive' => boolval($user->bs),
                    'expirationDate' => $expirationDate
                ];
                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function spilover($userId, $levels = 5)
    {
        if ($userId && $levels) {
            if ($user = User::find($userId)) {
                $spilover = $user->getRepository()->getSpilover(false, $levels);
                $result = [];
                foreach ($spilover as $s) {
                    $userView = new ModelViews\User($s);
                    $result[] = $userView->get();
                }
                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkUserInSpilover($param, $userId)
    {
        if ($param && $userId) {
            $user = User::find($param);

            if (!$user) {
                $phone = str_replace('+', '', $param);
                $user = User::orWhere('username', '=', $param)
                    ->orWhere('accountId', '=', intval($param))
                    ->orWhere('email', '=', $param)
                    ->orWhere('phoneNumber', '=', $phone)
                    ->first();
                if ($user) {
                    if ($spilover = $user->getRepository()->getSpilover(true)) {
                        foreach ($spilover as $s) {
                            $spiloverId = strval($s);
                            if ($spiloverId == $user->_id) {
                                $spiloverUser = User::find($s);
                                $userView = new ModelViews\User($spiloverUser);
                                return Response($userView->get(), Response::HTTP_OK);
                            }
                        }
                        return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                    } else {
                        return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                }
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetAndGetPassword($email, $type)
    {
        if ($email && ($type == User::PASSWORD_TYPE_MAIN || $type == User::PASSWORD_TYPE_FINANCIAL)) {
            if ($user = User::where('email', '=', $email)->first()) {
                $password = str_random();
                $hashedPassword = hash_hmac('sha1', $password, $user->_id);
                switch ($type) {
                    case User::PASSWORD_TYPE_MAIN:
                        $user->_plainPassword = $password;
                        $user->hashedPassword = $hashedPassword;
                    break;
                    case User::PASSWORD_TYPE_FINANCIAL:
                        $user->_plainFinPassword = $password;
                        $user->hashedFinPassword = $hashedPassword;
                    break;
                }
                if ($user->save()) {
                    if (isset($user->defaultLang) && $user->defaultLang) {
                        $userLanguage = mb_strtolower($user->defaultLang);
                    } else {
                        $userLanguage = 'ru';
                    }
                    if ($mailTemplate = MailTemplate::where('title', '=', 'resetPassword')->where('lang', '=', $userLanguage)->first()) {
                        if ($settings = Settings::first()) {
                            if (isset($settings->supportMail) && $settings->supportMail) {
                                $mailBody = str_replace('[PASSWORD]', $password, $mailTemplate->body);
                                Mail::send([], [], function ($message) use ($user, $settings, $mailTemplate, $mailBody) {
                                    $message->from($settings->supportMail)->to($user->email)->subject($mailTemplate->subject)->setBody($mailBody, 'text/html');
                                });
                            }
                        }
                    }
                    return Response($password, Response::HTTP_OK);
                } else {
                    return Response(['error' => 'User not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'email' => 'required',
            'type' => ['required', Rule::in([0, 1])]
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($user = User::where('email', '=', $requestParams['type'])->first()) {
                $password = str_random();
                $hashedPassword = hash_hmac('sha1', $password, $user->_id);
                switch ($requestParams['type']) {
                    case User::PASSWORD_TYPE_MAIN:
                        $user->_plainPassword = $password;
                        $user->hashedPassword = $hashedPassword;
                    break;
                    case User::PASSWORD_TYPE_FINANCIAL:
                        $user->_plainFinPassword = $password;
                        $user->hashedFinPassword = $hashedPassword;
                    break;
                }
                if ($user->save()) {
                    if (isset($user->defaultLang) && $user->defaultLang) {
                        $userLanguage = mb_strtolower($user->defaultLang);
                    } else {
                        $userLanguage = 'ru';
                    }
                    if ($mailTemplate = MailTemplate::where('title', '=', 'resetPassword')->where('lang', '=', $userLanguage)->first()) {
                        if ($settings = Settings::first()) {
                            if (isset($settings->supportMail) && $settings->supportMail) {
                                $mailBody = str_replace('[PASSWORD]', $password, $mailTemplate->body);
                                Mail::send([], [], function ($message) use ($user, $settings, $mailTemplate, $mailBody) {
                                    $message->from($settings->supportMail)->to($user->email)->subject($mailTemplate->subject)->setBody($mailBody, 'text/html');
                                });
                            }
                        }
                    }
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                } else {
                    return Response(['error' => 'User not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function resetPasswordByMessenger(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'messenger' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $requestParams['phone']);
            $messenger = mb_strtolower($requestParams['messenger']);
            switch ($messenger) {
                case 'facebook':
                    $fieldName = 'settings.phoneFB';
                    $messenger = MailQueue::MESSENGER_FACEBOOK;
                break;
                case 'telegram':
                    $fieldName = 'settings.phoneTelegram';
                    $messenger = MailQueue::MESSENGER_TELEGRAM;
                break;
                case 'viber':
                    $fieldName = 'settings.phoneViber';
                    $messenger = MailQueue::MESSENGER_VIBER;
                break;
                case 'whatsapp':
                    $fieldName = 'settings.phoneWhatsApp';
                    $messenger = MailQueue::MESSENGER_WHATSAPP;
                break;
            }
            if (isset($fieldName)) {
                if ($user = User::orWhere($fieldName, '=', $phone)->orWhere($fieldName, '=', '+' . $phone)->first()) {
                    if (isset($user->settings['selectedLang'])) {
                        $userLanguage = mb_strtolower($user->settings['selectedLang']);
                    } else {
                        $userLanguage = 'ru';
                    }
                    if ($mailTemplate = MailTemplate::where('title', '=', 'resetPasswordByMessenger')->where('lang', '=', $userLanguage)->first()) {
                        $password = str_random();
                        $hashedPassword = hash_hmac('sha1', $password, $user->_id);
                        $user->_plainPassword = $password;
                        $user->hashedPassword = $hashedPassword;
                        if ($user->save()) {
                            $mailQueue = new MailQueue();

                            $mailQueue->phone          = '+' . $phone;
                            $mailQueue->fio            = $user->firstName . ' ' . $user->secondName;
                            $mailQueue->username       = $user->username;
                            $mailQueue->messenger      = $messenger;
                            $mailQueue->body           = str_replace('[PASSWORD]', $password, $mailTemplate->body);
                            $mailQueue->lang           = $userLanguage;
                            $mailQueue->timeZone       = $user->settings['timeZone'];
                            $mailQueue->timeSendStart  = '00:00:00';
                            $mailQueue->timeSendFinish = '23:59:59';
                            $mailQueue->status         = MailQueue::STATUS_NOT_SENDED;
                            $mailQueue->dateCreate     = new UTCDateTime(time() * 1000);

                            if ($mailQueue->save()) {
                                return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                            } else {
                                return Response(['error' => 'Mail queue not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                            }
                        } else {
                            return Response(['error' => 'User not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    } else {
                        return Response(['error' => 'Mail template not found'], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
            }
        }
    }

}