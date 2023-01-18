<?php

namespace Modules\Email\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use DateTimeZone;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Email\Entities\Email;
use Modules\Email\Http\Requests\EmailRequest;
use Nette\Utils\DateTime;

class EmailController extends ApiController
{
    public function send(EmailRequest $request)
    {
        $api_key = '58447d82e43fcc43e06964cabea24381';

        $from_mail = env('MAIL_FROM_ADDRESS', 'nick@gmail.com');
        $from_name = env('MAIL_FROM_NAME', 'Nick');

        $to_mail = $request->get('to_mail') ?? 'default@gmail.com';
        $to_name = $request->get('to_name') ?? 'Def';
        $subject = $request->get('subject') ?? 'Mail API';
        $text    = $request->get('text')    ?? 'Hello, I\'m sending default message via Laravel';
        $date    = new DateTime("now", new DateTimeZone('Asia/Tbilisi'));
        
        $mail_body = 
        '{
            "to": [
            {
                "email": "' . $to_mail . '",
                "name": "' . $to_name . '"
            }],
            "cc": [
            {
                "email": "jane_doe@example.com",
                "name": "Jane Doe"
            }],
            "bcc": [
            {
                "email": "james_doe@example.com",
                "name": "Jim Doe"
            }],
            "from": {
                "email": "' . $from_mail . '",
                "name": "' . $from_name . '"
            },
            "custom_variables": {
                "user_id": "45982",
                "batch_id": "PSJ-12"
            },
            "headers": {
                "X-Message-Source": "dev.mydomain.com"
            },
            "subject": "' . $subject . '" ,
            "text": "' . $text . '" ,
            "category": "API Test"
        }';

        //curl api connection and conf.
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://sandbox.api.mailtrap.io/api/send/2039729",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $mail_body,
            CURLOPT_HTTPHEADER     => [
                "Api-Token: " . $api_key,
                "Content-Type: application/json"
                ],
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        
        //check curl and connection errors
        if ($err) {
            return $this->respondForbidden("cURL Error #:" . $err);
        }

        //add data to database
        if (!$err) {
            $mailForm = array(
                'username' => $from_name,
                'subject'  => $subject,
                'body'     => $mail_body,
                'text'     => $text,
                'from'     => $from_mail,
                'to'       => $to_mail,
                'datetime' => $date,
            );

            //database insert check
            try {
                Email::create($mailForm);
                return $this->respondSuccess(json_decode($response));
            } catch (\Exception $e) {
                return $this->respondInternalError($e->getMessage());
            }
        }

    }


}
