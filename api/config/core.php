<?php
// показывать сообщения об ошибках
ini_set('display_errors', 1);
error_reporting(E_ALL);

// URL домашней страницы
$home_url="http://127.0.0.1:8081/apiFB/";

// страница указана в параметре URL, страница по умолчанию одна
$page = isset($_GET['page']) ? $_GET['page'] : 1;
?>