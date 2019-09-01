<?php
header('Content-Type: text/plain;'); //Мы будем выводить простой текст
header('charset=utf-8'); //Мы будем выводить простой текст
set_time_limit(0); //Скрипт должен работать постоянно
ob_implicit_flush(); //Все echo должны сразу же выводиться
$address = 'localhost'; //Адрес работы сервера
$port = 8082; //Порт работы сервера (лучше какой-нибудь редкоиспользуемый)
if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    //AF_INET - семейство протоколов
    //SOCK_STREAM - тип сокета
    //SOL_TCP - протокол
    echo "Ошибка создания сокета";
} else {
    echo "Сокет создан\n";
}
$result = socket_connect($socket, $address, $port);
if ($result === false) {
    echo "Ошибка при подключении к сокету";
} else {
    echo "Подключение к сокету прошло успешно\n";
}

do {
    sleep(1);
    $out = socket_read($socket, 1024); //Читаем сообщение от сервера
    echo "Сообщение от сервера: $out.\n";
    $msg = 'Принято';
    echo (mb_strlen($msg,'cp1251'));
    socket_write($socket, $msg,  mb_strlen($msg,'cp1251')); //Отправляем серверу сообщение
} while(true);


if (isset($socket)) {
    socket_close($socket);
    echo "Сокет успешно закрыт";
}

