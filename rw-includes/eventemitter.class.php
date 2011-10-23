<?php 

class EventEmitter {
    private $callbacks = array();

    public function on($event, $callback) {
        if(!isset($this->callbacks[$event])) $this->callbacks[$event] = array();
        $this->callbacks[$event][] = $callback;
    }

    public function trigger($event) {
        $args = func_get_args();
        array_shift($args);
        if(!isset($this->callbacks[$event])) return;
        foreach($this->callbacks[$event] as $callback) {
            call_user_func_array($callback, $args);
        }
    }
}

