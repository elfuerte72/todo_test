<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // Настройка DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        // Создание экземпляра PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Ошибка подключения: ' . $this->error;
        }
    }

    // Подготовка запроса
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Привязка значений
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Выполнение подготовленного запроса
    public function execute() {
        return $this->stmt->execute();
    }

    // Получение результатов в виде массива объектов
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Получение одной записи
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Получение количества затронутых строк
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Получение последнего вставленного ID
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
} 