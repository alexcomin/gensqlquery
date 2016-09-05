<?php

class Connect {
    
    public $mysqli;
    public $array_db;
    public $colum;
    public $key;
    public $end;
    public $table;
    
    public function __construct($host, $user, $password, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }
    
    public function connect() {
        $this->mysqli = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        
        if ($this->mysqli->connect_errno) {
            printf("Не удалось подключиться: %s\n", $this->mysqli->connect_error);
            exit();
        }
    }
    
    public function add_array($table) {
        $this->table = $table;
        $query = "SHOW CREATE TABLE $table[0]";
        $result = $this->mysqli->query($query)->fetch_row();
        $res_table = $result[1];
        $this->array_db = explode("\n", $res_table);
        $this->array_db = array_map('trim', $this->array_db);
        $this->colum = [];
        $this->key = [];
        $this->end = [];
        foreach ($this->array_db as $key => $value) {
            if (preg_match("/^`/", $value)) {
                 $string = explode(" ", $value);
                 $str = str_replace($string[0], "", $value);
                 $this->colum[$string[0]] = $str;
            } elseif (preg_match("/^CREATE/", $value)) {
                continue;
            } elseif (preg_match("/^KEY|^PRIMARY KEY|^UNIQUE KEY/", $value)) { 
                array_push($this->key, $value);
            } elseif (preg_match("/^\)/", $value)) {
                array_push($this->end, $value);
            }
        }
    }
}

?>