<?php

class LDivision implements LisPHPBinding {
	public function doEval($env = array(), $values) {
		return array_reduce($values, function($soFar = null, $value) {
			if(is_null($soFar)) return $value;
			return $soFar / $value;
		});
	}
}