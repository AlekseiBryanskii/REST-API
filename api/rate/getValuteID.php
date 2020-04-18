<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов для соединения с БД и файл с объектом Category
include_once '../config/database.php';
include_once '../objects/rate.php';

// создание подключения к базе данных
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$valuteID = new Rate($db);

// запрос для категорий
$pdo = $valuteID->getValuteID();
$num = $pdo->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num>0) {

    // массив
    $valuteID_arr=array();
    $valuteID_arr["records"]=array();

    // получим содержимое нашей таблицы
    while ($row = $pdo->fetch(PDO::FETCH_ASSOC)){
        // извлекаем строку
        extract($row);

        $valuteID_item=array(
            'valuteID' => $valuteID,
            'name' => $name,
        );

        array_push($valuteID_arr["records"], $valuteID_item);
    }

    // код ответа - 200 OK
    http_response_code(200);

    // покажем данные категорий в формате json
    echo json_encode($valuteID_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что категории не найдены
    echo json_encode(array("message" => "Категории не найдены."), JSON_UNESCAPED_UNICODE);
}

//http://127.0.0.1:8081/bank-rest-api/api/rate/getValuteID.php
?>