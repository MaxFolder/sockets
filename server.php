<?php
header('Content-Type: text/plain;'); //Мы будем выводить простой текст
header('charset=utf-8'); //Мы будем выводить простой текст
set_time_limit(0); //Скрипт должен работать постоянно
ob_implicit_flush(); //Все echo должны сразу же отправляться клиенту
$address = 'localhost'; //Адрес работы сервера
$port = 8082; //Порт работы сервера (лучше какой-нибудь редкоиспользуемый)
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    //AF_INET - семейство протоколов
    //SOCK_STREAM - тип сокета
    //SOL_TCP - протокол
    echo "Ошибка создания сокета";
} else {
    echo "Сокет создан\n";
}
//Связываем дескриптор сокета с указанным адресом и портом
if (($ret = socket_bind($sock, $address, $port)) < 0) {
    echo "Ошибка связи сокета с адресом и портом";
} else {
    echo "Сокет успешно связан с адресом и портом\n";
}
//Начинаем прослушивание сокета (максимум 5 одновременных соединений)
if (($ret = socket_listen($sock, 5)) < 0) {
    echo "Ошибка при попытке прослушивания сокета";
} else {
    echo "Ждём подключение клиента\n";
}
do {
    //Принимаем соединение с сокетом
    if (($msgsock = socket_accept($sock)) < 0) {
        echo "Ошибка при старте соединений с сокетом";
    } else {
        echo "Сокет готов к приёму сообщений\n";
    }
    sleep(2);
    $msg = 'Привет'; //Сообщение клиенту
    socket_write($msgsock, $msg,  mb_strlen($msg,'cp1251')); //Запись в сокет
    //Бесконечный цикл ожидания клиентов
    do {
        if (false === ($buf = socket_read($msgsock, 1024))) {
            echo "Ошибка при чтении сообщения от клиента";
        }

        //Если клиент передал exit, то отключаем соединение
        if ($buf === 'exit') {
            socket_close($msgsock);
            break 2;
        }
        if (!is_string($buf)) {
            echo "Сообщение от сервера: передана не строка\n";
        }

        if ($buf === 'Принято') {
            echo sprintf("Сообщение %s принято клиентом\n", $msg);
        }

        $msg = mt_rand(1, 10000);
        socket_write($msgsock, $msg, mb_strlen($msg,'cp1251'));

    } while (true);
} while (true);
//Останавливаем работу с сокетом
if (isset($sock)) {
    socket_close($sock);
    echo "Сокет успешно закрыт";
}
