<?php

namespace Rapidweb;

class User implements \ArrayAccess {
    private $attributes;

    public $name;

    /// @todo make private after moving password check into this class
    public $password;
    public $crypt;

    public static function fromArray($name, $array) {
        $password = $array['password'];
        $crypt = $array['crypt'];
        unset($array['password']);
        unset($array['crypt']);
        return new static($name, $password, $crypt, $array);
    }

    private function __construct($name, $password, $crypt, $array) {
        $this->name = $name;
        $this->password = $password;
        $this->crypt = $crypt;
        $this->attributes = $array;
    }

    public function offsetGet($field) {
        return $this->attributes[$field];
    }

    public function offsetSet($field, $value) {
        return $this->attributes[$field];
    }

    public function offsetExists($field) {
        return isset($this->attributes[$field]);
    }

    public function offsetUnset($field) {
        unset($this->attributes[$field]);
    }

}
