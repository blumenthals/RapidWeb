<?php

class Stack {
	private $items = array();
	private $size = 0;

	function push($item) {
		$this->items[$this->size] = $item;
		$this->size++;
		return true;
	}

	function pop() {
		if ($this->size == 0) {
			return false; // stack is empty
		}
		$this->size--;
		return $this->items[$this->size];
	}

	function cnt() {
		return $this->size;
	}

	function top() {
		if($this->size)
			return $this->items[$this->size - 1];
		else
			return '';
	}
}
// end class definition
