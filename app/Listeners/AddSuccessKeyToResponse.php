<?php

namespace App\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;

class AddSuccessKeyToResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        if (!is_null($event->content)) {
            if (is_string($event->content)) {
                $event->content = array("message" => $event->content);
            }
            if (!isset($event->content['success'])) {
                $event->content = array("success" => true) + $event->content;
            }
        }
    }
}
