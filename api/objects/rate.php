<?php

class Rate {

    //подключение к бд и таблице currency
    private $conn;
    private $tableName = "currency";

    //свойства обьекта
    public $valuteID;
    public $numCode;
    public $charCode;
    public $name;
    public $value;
    public $date;
    public $dateStart;
    public $dateEnd;

    //конструктор для соединения с БД
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //метод чтения данных из бд
    function readSelectRate() {
        // запрос для чтения записей из БД
        $sql = "SELECT * FROM " . $this->tableName .  " WHERE valuteID = ? AND date BETWEEN ? AND ?";

        // подготовка запроса
        $pdo = $this->conn->prepare($sql);

        // привязываем id товара, который будет обновлен
        $pdo->bindParam(1, $this->valuteID);
        $pdo->bindParam(2, $this->dateStart);
        $pdo->bindParam(3, $this->dateEnd);

        // выполняем запрос
        $pdo->execute();

        return $pdo;
    }

    //метод получения ИД валют
    function getValuteID(){
        //запрос уникальных значений ид валют
        $sql = "SELECT DISTINCT valuteID, name  FROM " .$this->tableName. " ORDER BY valuteID";

        //подготовка запроса
        $pdo = $this->conn->prepare($sql);
        //выполняем запрос
        $pdo->execute();

        return $pdo;



    }

}

// http://127.0.0.1:8081/bank-rest-api/api/rate/getData.php?valuteID=R01335&dateStart=2020-04-10&dateEnd=2020-04-17

?>


