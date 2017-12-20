<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\MailQueue;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MailQueueController extends ApiController {

    public function getByStatus($status)
    {
        $status = intval($status);
        if ($status == MailQueue::STATUS_NOT_SENDED || $status == MailQueue::STATUS_SENDED) {
            $mailQueues = MailQueue::where('status', '=', $status)
                ->orderBy('dateCreate', '=', 'asc')
                ->get();

            $result = [];
            foreach ($mailQueues as $key => $mq) {
                $maleQueueView = new ModelViews\MailQueue($mq);
                $result[] = $maleQueueView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getByMessenger($messenger)
    {
        if ($messenger) {
            $mailQueues = MailQueue::where('messenger', '=', $messenger)
                ->where('status', '=', MailQueue::STATUS_NOT_SENDED)
                ->orderBy('dateCreate', 'asc')
                ->get();

            $result = [];
            foreach ($mailQueues as $key => $mq) {
                $maleQueueView = new ModelViews\MailQueue($mq);
                $result[] = $maleQueueView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $mailQueue = MailQueue::find($requestParams['id']);

            if ($mailQueue) {
                if ($mailQueue->delete()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                }
            } else {
                return Response(['error' => 'Resource not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'messenger' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if (! isset(MailQueue::MESSENGERS[$requestParams['messenger']])) {
                return Response(['error' => 'Wrong messenger name'], Response::HTTP_BAD_REQUEST);
            } else {
                $userIds = User::select('_id')->get();

                foreach ($userIds as $userId) {
                    if ($user = User::find($userId)) {
                        switch ($requestParams['messenger']) {
                            case MailQueue::MESSENGER_MAIL:
                                if ($user->settings['notifyAboutOtherNews']) {
                                    if ($settings = Settings::first()) {
                                        if (isset($settings->supportMail) && $settings->supportMail) {
                                            Mail::raw($requestParams['body'], function ($message) use ($user, $settings) {
                                                $message->from($settings->supportMail)->to($user->email)->subject('');
                                            });
                                        }
                                    }
                                }
                            break;
                            default:
                                $enable = false;
                                switch ($requestParams['messenger']) {
                                    case MailQueue::MESSENGER_VIBER:
                                        $enable = $user->settings['phoneViber'] ? $user->settings['phoneViber'] : false;
                                    break;
                                    case MailQueue::MESSENGER_WHATSAPP:
                                        $enable = $user->settings['phoneWhatsApp'] ? $user->settings['phoneWhatsApp'] : false;
                                    break;
                                    case MailQueue::MESSENGER_TELEGRAM:
                                        $enable = $user->settings['phoneTelegram'] ? $user->settings['phoneTelegram'] : false;
                                    break;
                                    case MailQueue::MESSENGER_FACEBOOK:
                                        $enable = $user->settings['phoneFB'] ? $user->settings['phoneFB'] : false;
                                    break;
                                }
                                if ($enable) {
                                    $mailQueue = new MailQueue();
                                    $mailQueue->phone = $user->settings['phoneFB'];
                                    $mailQueue->fio = $user->firstName . ' ' . $user->secondName;
                                    $mailQueue->body = $requestParams['body'];
                                    $mailQueue->username = $user->username;
                                    $mailQueue->messenger = $requestParams['messenger'];
                                    $mailQueue->lang = mb_strtolower($user->settings['selectedLang']);
                                    $mailQueue->timeZone = $user->settings['timeZone'];
                                    if (isset($requestParams['timeSendStart'])) {
                                        $mailQueue->timeSendStart = $requestParams['timeSendStart'];
                                    }
                                    if (isset($requestParams['timeSendFinish'])) {
                                        $mailQueue->timeSendFinish = $requestParams['timeSendFinish'];
                                    }
                                    $mailQueue->save();
                                }
                            break;
                        }
                    }
                }
            }

            return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        }
    }

    public function send(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($mailQueue = MailQueue::find($requestParams['id'])) {
                $mailQueue->status = 1;
                if ($mailQueue->save()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Resource not updated'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return Response(['error' => 'Resource not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}