<?php

// Конфиг
$cfg = [];


// Название сайта
$cfg['title'] = 'AntiPlagiarism';

// Количество записей на страницу
$cfg['rowsPerPage'] = 5;

// Удалять диломные старше 5 лет. ВКЛЮЧИТЕЛЬНО
$cfg['oldDiplomas'] = 5;

// Рут деректория. НЕ ИЗМЕНЯТЬ
$cfg['rootDir'] = $_SERVER['DOCUMENT_ROOT'];

// Директория загрузки дипломных
$cfg['uploadDir'] = $cfg['rootDir'] . '/uploads/';

// Директория загрузки дипломных
$cfg['dumpsDir'] = $cfg['rootDir'] . '/dumps/';

// Хост базы данных
$cfg['dbhost'] = 'localhost';

// Юзер базы
$cfg['dbuser'] = 'root';

// Пароль для юзера
$cfg['dbpass'] = 'root';

// Название базы
$cfg['dbname'] = 'ap';

// Префикс таблиц. НЕ ИЗМЕНЯТЬ
$cfg['dbprefix'] = 'ap_';
