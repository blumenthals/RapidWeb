<?php

namespace Rapidweb;

class FileUpload {
    public $name;
    public $type;
    public $size;
    public $tmp_name;
    public $error;
    public function __construct($phpfile) {
        $this->name = $phpfile['name'];
        $this->type = $phpfile['type'];
        $this->size = $phpfile['size'];
        $this->tmp_name = $phpfile['tmp_name'];
        $this->error = $phpfile['error'];
    }

    public function moveTo($dir) {
        if(!move_uploaded_file($this->tmp_name, $dir."/".basename($this->name))) throw new \Exception("Can't move file to $dir");
    }
}
