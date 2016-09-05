<?php

include('classFile.php');
include('classDB.php');

class Sync {
    
    public function __construct($host, $user, $pass, $db, $table) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->table = $table;
    }
    
    public function start_sync() {
        $file = new AddFile($this->table);
        $file->add_array();
        
        $db = new Connect($this->host, $this->user, $this->pass, $this->db);
        $db->connect();
        $db->add_array(['table_full']);
        
        $diff_colum = array_diff($file->colum, $db->colum);
        $diff_key = array_diff($file->key, $db->key);
        $diff_end = array_diff($file->end, $db->end);
        
        $lib_type = ["int", "tinyint", "smallint", "mediumint", "bigint", "decimal",
                     "float", "year", "char", "varchar", "binary", "varbinary"];
        
        if ($diff_colum) {
            foreach ($diff_colum as $key => $value) {
                if (array_key_exists($key, $db->colum)) {
                    $str_file = explode(" ", $file->colum[$key]);
                    $str_db = explode(" ", $db->colum[$key]);
                    $string_diff = array_diff($str_file, $str_db);
                    if (in_array(str_replace(" ", "", $string_diff[1]), $lib_type) && 
                        $string_diff[1] == explode("(", $str_db[1])[0]) {
                        echo "Игнор, не указано значение в типе данных";
                    } else {
                        echo "Отправляем запрос в БД на изминения";
                    }
                } else {
                    echo "Колонка не найдена, Отправляем запрос на добавлнение в БД\n";
                    // $val = str_replace([','], "", $value);
                    // $query_db = "ALTER TABLE " . $db->table[0] ." ADD COLUMN $key $val ;";
                    // $db->mysqli->query($query_db);
                }
                echo $key . " " . $value . "\n";
            }
        }
        if ($diff_key) {
           foreach  ($diff_key as $key => $value) {
               echo "Отправляем запрос в БД " . $value;
           }
        }
        if ($diff_end) {
            foreach ($diff_end as $key => $value) {
                $string = str_replace(') ', "", $value);
                echo "Отправляем запрос в БД " . $string;
            }
        }
    }
}

?>