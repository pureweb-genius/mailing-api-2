<?php


use application\database\Database;
use app\Queue\MailingQueue;

require 'Queue/MailingQueue.php';
require 'config/connection.php';
class MailingService
{

    public function sendMailing($attributes)
    {
        $queue = new MailingQueue(require 'config/connection.php');
        $queue->handle($attributes['mailing_name'], $attributes['message']);

        return 'Рассылка успешно отправлена';

    }
}