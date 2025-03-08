<?php
/**
 * Базовый класс контроллера
 * Загружает модели и представления
 */
class Controller {
    // Загрузка модели
    public function model($model) {
        // Подключаем файл модели
        require_once '../app/models/' . $model . '.php';
        
        // Создаем экземпляр модели
        return new $model();
    }
    
    // Загрузка представления
    public function view($view, $data = []) {
        // Проверяем наличие файла представления
        if(file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // Представление не найдено
            die('Представление не найдено');
        }
    }
    
    // Проверка авторизации пользователя
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Редирект
    public function redirect($url) {
        header('location: ' . SITE_URL . $url);
    }
    
    // Получение данных из POST запроса
    public function getPostData() {
        return json_decode(file_get_contents("php://input"));
    }
    
    // Ответ в формате JSON
    public function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 