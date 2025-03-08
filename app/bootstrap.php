<?php
// Загрузка конфигурации
require_once 'config/config.php';

// Автозагрузка основных классов
spl_autoload_register(function($className) {
    require_once 'core/' . $className . '.php';
});

// Запуск сессии
session_start(); 