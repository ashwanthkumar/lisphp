<?php

spl_autoload_register(function ($class) {
    include  $class . '.php';
});

interface LisPHPBinding {
	public function doEval($environment = array(), $values);
}

class KNOWN_BINDINGS {
	static public function of($operator) {
		$implementations = array(
			"+" => "LAddition",
			"-" => "LSubtraction",
			"*" => "LMultiplication",
			"/" => "LDivision",
			"print" => "LPrint",
			"list" => "LList"
		);

		if(array_key_exists($operator, $implementations)) {
			return $implementations[$operator];
		} else {
			throw new Exception("$operator not implemented", 1);
		}
	}
}


function is_boolean_value($value) {
	$value = strtolower($value);
	return $value === "true" || $value === "false";
}

function is_numeric_value($value) {
	$match = preg_match('/[0-9]+/', $value);
	return $match === 1;
}

function is_literal($value) {
	$match = preg_match('/\'(.*)\'/', $value);
	$match2 = preg_match('/\"(.*)\"/', $value);
	return $match === 1 || $match2 === 1;
}
