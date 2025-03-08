<?php
// Конфигурация базы данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Стандартное имя пользователя в MAMP
define('DB_PASS', 'root'); // Стандартный пароль в MAMP
define('DB_NAME', 'todo_app');

// Конфигурация приложения
define('SITE_URL', 'http://localhost:8888/site/');
define('APP_ROOT', dirname(dirname(__FILE__)));

// Режим отладки
define('DEBUG', true); 