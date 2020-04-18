<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение к базе данных будет здесь
// подключение базы данных и файл, содержащий объекты
include_once '../config/database.php';
include_once '../objects/rate.php';

//получаем соединение с БД
$database = new Database();
$db = $database->getConnection();

//инициализируем обьект
$rate = new Rate($db);

// установим свойство ID записи для чтения
$rate->valuteID = isset($_GET['valuteID']) ? $_GET['valuteID'] : die();
$rate->dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : die();
$rate->dateEnd = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : die();

// чтение товаров будет здесь
// запрашиваем товары
$pdo = $rate->readSelectRate();
$num = $pdo->rowCount();

// проверка, найдено ли больше 0 записей
if ($num>0) {

    // массив товаров
    $rate_arr=array();
    $rate_arr["records"]=array();

    // получаем содержимое нашей таблицы
    // fetch() быстрее, чем fetchAll()
    while ($row = $pdo->fetch(PDO::FETCH_ASSOC)){

        // извлекаем строку
        extract($row);

        $rate_item=array(
            "valuteID" => $valuteID,
            "numCode" => $numCode,
            "charCode" => $charCode,
            "name" => $name,
            "value" => $value,
            "date" => $date
        );

        array_push($rate_arr["records"], $rate_item);
    }

    // устанавливаем код ответа - 200 OK
    http_response_code(200);

    // выводим данные о товаре в формате JSON
    echo json_encode($rate_arr);
}

// 'товары не найдены' будет здесь
else {

    // установим код ответа - 404 Не найдено
    http_response_code(404);

    // сообщаем пользователю, что товары не найдены
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}

// http://127.0.0.1:8081/bank-rest-api/api/rate/getData.php?valuteID=R01010&dateStart=2020/04/01&dateEnd=2020/04/18