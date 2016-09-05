<?php

class AddFile {

    public $array_file;
    public $colum;
    public $key;
    public $end;
    public function __construct($file) {
        $this->file = $file;
    }
    
    public function add_array() {
        $array_file = file($this->file);
        $this->array_file = array_map('trim', $array_file);
        $new_value = str_replace(";", "", array_pop($this->array_file));
        array_push($this->array_file, $new_value);
        $this->colum = [];
        $this->key = [];
        $this->end = [];
        foreach ($this->array_file as $key => $value) {
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