<?php

use application\database\Database;

require 'database/Database.php';
require 'helpers/response_json.php';
class UserController
{

    public function upload(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Метод не разрешен');
        }
        if (empty($_FILES) || !isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            die('Ошибка загрузки файла');
        }

        $fileName = key($_FILES);
        $fileType = str_replace('\/', '/', $_FILES[$fileName]['type']);

        if ($fileType === "text/csv") {
            $config = require 'config/connection.php';
            $conn = Database::getInstance($config);
            $file = fopen($_FILES[$fileName]['tmp_name'], 'r');
            if ($file === false) {
                die('Не удалось открыть файл');
            }

            $sql = "INSERT INTO users (number, name) VALUES (:number, :name)";
            $stmt = $conn->prepare($sql);
            while (($row = fgetcsv($file, 100000, ",")) !== false) {
                if (isset($row[0]) && isset($row[1])) {
                    try {
                        $stmt->execute([':number' => $row[0], ':name' => $row[1]]);
                    } catch (PDOException $e) {
                        if ($e->errorInfo[1] == 1062) {
                            continue;
                        } else {
                            die('Ошибка базы данных: ' . $e->getMessage());
                        }
                    }
                }
            }

            fclose($file);
            jsonResponse([
                'status' => 'success',
                'message' => 'Файл успешно загружен',
            ]);
        } else {
            die('Неверный тип файла. Ожидается CSV.');
        }
    }

}