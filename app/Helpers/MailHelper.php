<?php

namespace App\Helpers;

use ErrorException;
use Exception;
use Illuminate\Support\Facades\Mail;

class MailHelper {

    /**
     * Выполняет отправку письма с запросом на подтверждение адреса электронной почты.
     *
     * @param array $data Параметры письма.
     * @return bool
     * @throws ErrorException
     */
    public static function sendSupport($data) {
        try {
            Mail::send('emails.support', $data, function ($message) use ($data) {
                $message
                    ->to($data['email'])
                    ->subject($data['subject']);
            });

            return true;
        }
        catch (Exception $e) {
            ErrorHelper::throw()
                ->data('send_support', 'Ошибка отправки письма на почту.', $e)
                ->log('mail');
        }

        return false;
    }
}
