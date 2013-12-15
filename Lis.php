<?php

require_once("impl/LispImpl.php");

class ConsCell {
	protected $car;
	protected $cdr; // can be a conscell by itself
	
	public function __construct($car, $cdr) {
		$this->car = $car;
		$this->cdr = $cdr;
	}

	public function asTuple() {
		if(is_null($this->cdr)) {
			return "(" . $this->car . ",Nil)";
		} else {
			return "(" . $this->car . "," . $this->cdr->asTuple() . ")";
		}
	}

	public static function of($values) {
		if(is_null($values)) throw new Exception("Invalid Input for creating ConsCell", 1);
		
		if(is_array($values) && count($values) > 1) {
			$car = array_shift($values);
			return new ConsCell($car, ConsCell::of($values));
		}
		
		// we know the values contains only 1
		return new ConsCell($values[0], null);
	}
}

class Environment extends ConsCell {
	public function find($name) {
		if(array_key_exists($name, $this->car)) {
			return $this->car[$name];
		} else {
			return $this->cdr->find($name);
		}
	}

	public function add($name, $value) {
		$this->car[$name] = $value;
		return $this;
	}
}

function notEmptyStr($str) {
	return !(($str == "" ) ? true : false);
}

function parseProgram($program) {
	$program = str_replace(")", " ) ", $program);
	$program = str_replace("(", " ( ", $program);
	$tree = array_filter(str_getcsv($program, " "), "notEmptyStr");

	$stack = array();
	$current = array();
	foreach ($tree as $token) {
		switch($token) {
			case "(":
				array_push($stack, $current);
				$current = array();
				break;
			case ")":
				$tmp = array_pop($stack);
				array_push($tmp, $current);
				$current = $tmp;
				break;
			default:
				array_push($current, $token);
				break;
		}
	}

	return $current;
}

class Expression {
	static public function create($tree, $env) {
		$operator = $tree[0];
		$values = array();
		for ($i=1; $i < count($tree); $i++) {
			$atom = $tree[$i];
			if(is_array($atom)) {
				$values[] = Expression::create($atom, $env);
			} else if(is_numeric_value($atom) || is_boolean_value($atom) || is_literal($atom)) {
				$values[] = $atom;
			} else {
				echo "Getting string from env - " . $tree[$i] . "\n";
				$values[] = $env[$atom];
			}
		}

		$operatorClass = KNOWN_BINDINGS::of($operator);
		$eval = new $operatorClass;
		return $eval->doEval(&$env, $values);
	}
}

function eval_lisp($parseTree) {

	foreach ($parseTree as $expression) {
		Expression::create($expression, array());
	}
}



if($argc < 2) {
	die("Usage: php Lis.PHP <file_name_eval>\n");
}
$lispFileToEval = $argv[1];
$parseTree = parseProgram(file_get_contents($lispFileToEval));
eval_lisp($parseTree);
