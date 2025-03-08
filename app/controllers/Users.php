<?php
class Users extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    // Метод для отображения формы регистрации
    public function register() {
        // Если пользователь уже авторизован, перенаправляем на страницу задач
        if($this->isLoggedIn()) {
            $this->redirect('tasks');
        }
        
        // Проверяем, был ли отправлен POST запрос
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Обработка формы
            
            // Санитизация POST данных
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Данные для валидации
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Валидация имени
            if(empty($data['name'])) {
                $data['name_err'] = 'Пожалуйста, введите имя';
            }
            
            // Валидация email
            if(empty($data['email'])) {
                $data['email_err'] = 'Пожалуйста, введите email';
            } else {
                // Проверка существования email
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email уже зарегистрирован';
                }
            }
            
            // Валидация пароля
            if(empty($data['password'])) {
                $data['password_err'] = 'Пожалуйста, введите пароль';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Пароль должен содержать не менее 6 символов';
            }
            
            // Валидация подтверждения пароля
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Пожалуйста, подтвердите пароль';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Пароли не совпадают';
                }
            }
            
            // Проверка наличия ошибок
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Валидация пройдена
                
                // Регистрация пользователя
                if($this->userModel->register($data)) {
                    // Устанавливаем флеш-сообщение
                    $_SESSION['flash_message'] = 'Вы успешно зарегистрировались и можете войти в систему';
                    // Перенаправляем на страницу входа
                    $this->redirect('users/login');
                } else {
                    die('Что-то пошло не так');
                }
            } else {
                // Загружаем представление с ошибками
                $this->view('users/register', $data);
            }
        } else {
            // Инициализация данных
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Загружаем представление
            $this->view('users/register', $data);
        }
    }
    
    // Метод для отображения формы входа
    public function login() {
        // Если пользователь уже авторизован, перенаправляем на страницу задач
        if($this->isLoggedIn()) {
            $this->redirect('tasks');
        }
        
        // Проверяем, был ли отправлен POST запрос
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Обработка формы
            
            // Санитизация POST данных
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Данные для валидации
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Валидация email
            if(empty($data['email'])) {
                $data['email_err'] = 'Пожалуйста, введите email';
            }
            
            // Валидация пароля
            if(empty($data['password'])) {
                $data['password_err'] = 'Пожалуйста, введите пароль';
            }
            
            // Проверка существования пользователя
            if(!$this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Пользователь не найден';
            }
            
            // Проверка наличия ошибок
            if(empty($data['email_err']) && empty($data['password_err'])) {
                // Валидация пройдена
                
                // Авторизация пользователя
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if($loggedInUser) {
                    // Создаем сессию
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Неверный пароль';
                    $this->view('users/login', $data);
                }
            } else {
                // Загружаем представление с ошибками
                $this->view('users/login', $data);
            }
        } else {
            // Инициализация данных
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Загружаем представление
            $this->view('users/login', $data);
        }
    }
    
    // Метод для создания сессии пользователя
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $this->redirect('tasks');
    }
    
    // Метод для выхода из системы
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        $this->redirect('users/login');
    }
} 