<?php

namespace App\Services;

use GuzzleHttp\Client as guzzle;

class GoogleRecaptchaService
{
    public function checkRecaptcha($recaptcha)
    {
        $guzzle = new guzzle;
        $response = $guzzle->request('POST', config('services.recaptcha.url'), [
            'form_params' => [
                'secret' => config('services.recaptcha.secret'),
                'response' => $recaptcha,
            ]
        ]);

        $response = json_decode($response->getBody());

        return $response->success;
    }
}
