<?php

namespace app\Queue;

require  'database/Database.php';
use application\database\Database;

class MailingQueue
{
    private $conn;
    public function __construct($config)
    {
        $this->conn = Database::getInstance($config);
    }

    public function handle($mailingName, $message)
    {
       $users = $this->getUsers();

         foreach ($users as $user) {
              if(!$this->isMailSent($user['id'], $mailingName)) {
                  $this->insertMailing($user['id'], $mailingName, $message);
              }
         }

    }


    private function isMailSent($userId, $mailingName)
    {
        $sql = "SELECT COUNT(*) FROM mailings WHERE user_id = :user_id AND mailing_name = :mailing_name AND sent = TRUE";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':mailing_name' => $mailingName]);
        return $stmt->fetchColumn() > 0;
    }

    private function getUsers()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    private function insertMailing($userId, $mailingName, $message)
    {
        $sql = "INSERT INTO mailings (user_id, mailing_name, message, sent) VALUES (:user_id, :mailing_name, :message, :sent)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':mailing_name' => $mailingName, ':message' => $message , ':sent' => 1]);
    }
}