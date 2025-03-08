<?php
/**
 * Класс приложения
 * Создает URL и загружает основной контроллер
 * URL FORMAT - /controller/method/params
 */
class App {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];
    
    public function __construct() {
        // Получаем URL
        $url = $this->getUrl();
        
        // Проверяем существование контроллера
        if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // Если существует, устанавливаем как текущий контроллер
            $this->currentController = ucwords($url[0]);
            // Удаляем из массива
            unset($url[0]);
        }
        
        // Подключаем контроллер
        require_once '../app/controllers/' . $this->currentController . '.php';
        
        // Создаем экземпляр контроллера
        $this->currentController = new $this->currentController;
        
        // Проверяем второй параметр URL
        if(isset($url[1])) {
            // Проверяем существование метода в контроллере
            if(method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Удаляем из массива
                unset($url[1]);
            }
        }
        
        // Получаем параметры
        $this->params = $url ? array_values($url) : [];
        
        // Вызываем метод контроллера с параметрами
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
    
    // Получение URL
    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        
        return [];
    }
} 