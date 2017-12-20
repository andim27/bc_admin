<?php

namespace App\Jobs;

use App\Models\MailQueue;
use App\Models\User;
use App\Models\MailTemplate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use MongoDB\BSON\UTCDateTime;

class Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::all();

        $nowDate = Carbon::now()->setTime(0, 0, 0);

        $periods = [1, 3, 5];

        foreach ($users as $user) {
            if (is_string($user->expirationDateBS)) {
                $expirationDateBS = strtotime($user->expirationDateBS);
            } else {
                $expirationDateBS = $user->expirationDateBS->toDateTime()->getTimestamp();
            }
            if ($expirationDateBS > 0) {
                $userExpirationDateBS = Carbon::createFromTimestamp($expirationDateBS);
                $numDays = $userExpirationDateBS->diffInDays($nowDate);

                if ($user->defaultLang) {
                    $language = $user->defaultLang;
                } else {
                    $language = 'ru';
                }

                if (in_array($numDays, $periods)) {
                    $mailTemplate = MailTemplate::where('title', '=', 'endActivityBot')
                        ->where('lang', '=', $language)->first();

                    $mailTemplate->body = str_replace('[DAYS]', $numDays);
                    if ($mailTemplate) {
                        $this->_send($user, $mailTemplate);
                    }
                }

                if ($numDays == -1) {
                    $mailTemplate = MailTemplate::where('title', '=', 'stopActivityBot')
                        ->where('lang', '=', $language)->first();

                    if ($mailTemplate) {
                        $this->_send($user, $mailTemplate, $language);
                    }
                }
            }
        }
    }

    public function _send(User $user, MailTemplate $mailTemplate, $language)
    {
        foreach (MailQueue::MESSENGERS as $messenger) {
            switch ($messenger) {
                case MailQueue::MESSENGER_WHATSAPP:
                    $phone = $user->settings['phoneWhatsApp'];
                    break;
                case MailQueue::MESSENGER_VIBER:
                    $phone = $user->settings['phoneViber'];
                    break;
                case MailQueue::MESSENGER_FACEBOOK:
                    $phone = $user->settings['phoneTelegram'];
                    break;
                case MailQueue::MESSENGER_TELEGRAM:
                    $phone = $user->settings['phoneFB'];
                    break;
            }

            if (isset($phone)) {
                $mailQueue = new MailQueue();
                $mailQueue->phone = $phone;
                $mailQueue->fio            = $user->firstName . ' ' . $user->secondName;
                $mailQueue->username       = $user->username;
                $mailQueue->messenger      = $messenger;
                $mailQueue->body           = $mailTemplate->body;
                $mailQueue->lang           = $language;
                $mailQueue->timeZone       = $user->settings['timeZone'];
                $mailQueue->timeSendStart  = '10:00:00';
                $mailQueue->timeSendFinish = '10:30:00';

                $mailQueue->save();
            }
        }
    }
}
