<?php

require 'services/MailingService.php';
require 'helpers/response_json.php';
class MailingController
{
    public function send()
    {
        $service = new MailingService();
        $attributes = $_GET;

        if (empty($attributes['mailing_name']) || empty($attributes['message'])) {
            jsonResponse(['error' => 'Отсутствуют необходимые параметры.']);
        }

        jsonResponse(['message' => $service->sendMailing($attributes)]);
    }

}