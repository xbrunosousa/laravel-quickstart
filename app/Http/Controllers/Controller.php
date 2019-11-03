<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    public function success($message, $statusCode = 200)
    {
        return $this->response->array([
            'success' => true,
            'message' => $message,
            'status' => $statusCode
        ])->setStatusCode($statusCode);
    }

    public function error($message, $statusCode = 200)
    {
        return $this->response->array([
            'success' => false,
            'message' => $message,
            'status' => $statusCode
        ])->setStatusCode($statusCode);
    }

    public function successData($data, $statusCode = 200)
    {
        return $this->response->array([
            'success' => true,
            'data' => $data,
            'status' => $statusCode
        ])->setStatusCode($statusCode);
    }

    public function sendEmail($view, $viewParams, $to, $subject)
    {
        Mail::send(
            $view,
            $viewParams,
            function ($mail) use ($to, $subject) {
                $mail->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
                $mail->to($to['email'], $to['name']);
                $mail->subject($subject);
            }
        );
    }
}
