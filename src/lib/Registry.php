<?php
namespace vedebel\ydapi\lib;

final class Registry
{

private static $instance = NULL;
private $vars = array();

private function __construct() {}
private function __clone() {}
private function __wakeup() {}

public static function getInstance()
    {
        if(is_null(self::$instance))
            self::$instance = new self;

        return self::$instance;
    }

public function __set($name, $val)
	{
		$this->vars[$name] = $val;
	}

public function __get($name)
	{
		if (!isset($this->vars[$name]))
            return NULL;

		return $this->vars[$name];
	}

public function __unset($name)
	{
		unset($this->vars[$name]);
	}

}

?>