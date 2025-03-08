<?php
class Pages extends Controller {
    public function __construct() {
        // Конструктор
    }
    
    // Метод для отображения главной страницы
    public function index() {
        // Если пользователь авторизован, перенаправляем на страницу задач
        if($this->isLoggedIn()) {
            $this->redirect('tasks');
        }
        
        $data = [
            'title' => 'Система управления задачами',
            'description' => 'Простая и удобная система для управления вашими задачами'
        ];
        
        $this->view('pages/index', $data);
    }
    
    // Метод для отображения страницы ошибки
    public function error() {
        $data = [
            'title' => 'Ошибка 404',
            'description' => 'Страница не найдена'
        ];
        
        $this->view('pages/error', $data);
    }
} 