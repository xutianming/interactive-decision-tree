<?php

class Node {
	var $name;
	var $children;
	var $size;

	function getName() {
		return $this->name;
	}

	function getChildren() {
		return $this->children;
	}

	function getSize() {
		return $this->size;
	}

	function setName($name) {
		$this->name = $name;
	}

	function setChildren($children) {
		if(is_array($children))
		{
			$this->children = $children;
			return true;
		}
		return false;
	}

	function setSize($size) {
		$this->size = $size;
	}

	function addChildren($child) {
		if(isset($children) && is_array($children))
		{
			array_push($children,$child);
			return true;
		}
		return false;
	}
}
?>