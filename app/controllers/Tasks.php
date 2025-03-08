<?php
class Tasks extends Controller {
    private $taskModel;
    
    public function __construct() {
        // Проверка авторизации
        if(!isset($_SESSION['user_id'])) {
            $this->redirect('users/login');
        }
        
        $this->taskModel = $this->model('Task');
    }
    
    // Метод для отображения списка задач
    public function index() {
        // Получаем фильтры из GET запроса
        $filters = [
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'priority' => isset($_GET['priority']) ? $_GET['priority'] : '',
            'search' => isset($_GET['search']) ? $_GET['search'] : ''
        ];
        
        // Получаем задачи пользователя с учетом фильтров
        $tasks = $this->taskModel->getTasks($_SESSION['user_id'], $filters);
        
        $data = [
            'tasks' => $tasks,
            'filters' => $filters
        ];
        
        $this->view('tasks/index', $data);
    }
    
    // Метод для отображения формы добавления задачи
    public function add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Обработка формы
            
            // Санитизация POST данных
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Данные для валидации
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'status' => trim($_POST['status']),
                'priority' => trim($_POST['priority']),
                'due_date' => trim($_POST['due_date']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'description_err' => ''
            ];
            
            // Валидация заголовка
            if(empty($data['title'])) {
                $data['title_err'] = 'Пожалуйста, введите заголовок задачи';
            }
            
            // Проверка наличия ошибок
            if(empty($data['title_err']) && empty($data['description_err'])) {
                // Добавление задачи
                if($this->taskModel->addTask($data)) {
                    // Устанавливаем флеш-сообщение
                    $_SESSION['flash_message'] = 'Задача успешно добавлена';
                    // Перенаправляем на список задач
                    $this->redirect('tasks');
                } else {
                    die('Что-то пошло не так');
                }
            } else {
                // Загружаем представление с ошибками
                $this->view('tasks/add', $data);
            }
        } else {
            // Инициализация данных
            $data = [
                'title' => '',
                'description' => '',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => '',
                'title_err' => '',
                'description_err' => ''
            ];
            
            // Загружаем представление
            $this->view('tasks/add', $data);
        }
    }
    
    // Метод для отображения формы редактирования задачи
    public function edit($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Обработка формы
            
            // Санитизация POST данных
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Данные для валидации
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'status' => trim($_POST['status']),
                'priority' => trim($_POST['priority']),
                'due_date' => trim($_POST['due_date']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'description_err' => ''
            ];
            
            // Валидация заголовка
            if(empty($data['title'])) {
                $data['title_err'] = 'Пожалуйста, введите заголовок задачи';
            }
            
            // Проверка наличия ошибок
            if(empty($data['title_err']) && empty($data['description_err'])) {
                // Обновление задачи
                if($this->taskModel->updateTask($data)) {
                    // Устанавливаем флеш-сообщение
                    $_SESSION['flash_message'] = 'Задача успешно обновлена';
                    // Перенаправляем на список задач
                    $this->redirect('tasks');
                } else {
                    die('Что-то пошло не так');
                }
            } else {
                // Загружаем представление с ошибками
                $this->view('tasks/edit', $data);
            }
        } else {
            // Получаем задачу по ID
            $task = $this->taskModel->getTaskById($id);
            
            // Проверяем, принадлежит ли задача текущему пользователю
            if($task->user_id != $_SESSION['user_id']) {
                $this->redirect('tasks');
            }
            
            // Инициализация данных
            $data = [
                'id' => $id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'priority' => $task->priority,
                'due_date' => $task->due_date,
                'title_err' => '',
                'description_err' => ''
            ];
            
            // Загружаем представление
            $this->view('tasks/edit', $data);
        }
    }
    
    // Метод для отображения детальной информации о задаче
    public function show($id) {
        // Получаем задачу по ID
        $task = $this->taskModel->getTaskById($id);
        
        // Проверяем, принадлежит ли задача текущему пользователю
        if($task->user_id != $_SESSION['user_id']) {
            $this->redirect('tasks');
        }
        
        $data = [
            'task' => $task
        ];
        
        $this->view('tasks/show', $data);
    }
    
    // Метод для удаления задачи
    public function delete($id) {
        // Получаем задачу по ID
        $task = $this->taskModel->getTaskById($id);
        
        // Проверяем, принадлежит ли задача текущему пользователю
        if($task->user_id != $_SESSION['user_id']) {
            $this->redirect('tasks');
        }
        
        if($this->taskModel->deleteTask($id, $_SESSION['user_id'])) {
            // Устанавливаем флеш-сообщение
            $_SESSION['flash_message'] = 'Задача успешно удалена';
        } else {
            $_SESSION['flash_message'] = 'Не удалось удалить задачу';
        }
        
        $this->redirect('tasks');
    }
    
    // AJAX методы
    
    // Метод для обновления статуса задачи через AJAX
    public function updateStatus() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Получаем данные из POST запроса
            $data = $this->getPostData();
            
            if(isset($data->id) && isset($data->status)) {
                if($this->taskModel->updateStatus($data->id, $_SESSION['user_id'], $data->status)) {
                    $this->jsonResponse(['success' => true]);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Не удалось обновить статус задачи']);
                }
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Неверные данные']);
            }
        } else {
            $this->redirect('tasks');
        }
    }
    
    // Метод для получения отфильтрованных задач через AJAX
    public function filter() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Получаем данные из POST запроса
            $data = $this->getPostData();
            
            $filters = [
                'status' => isset($data->status) ? $data->status : '',
                'priority' => isset($data->priority) ? $data->priority : '',
                'search' => isset($data->search) ? $data->search : ''
            ];
            
            // Получаем задачи пользователя с учетом фильтров
            $tasks = $this->taskModel->getTasks($_SESSION['user_id'], $filters);
            
            $this->jsonResponse(['success' => true, 'tasks' => $tasks]);
        } else {
            $this->redirect('tasks');
        }
    }
} 