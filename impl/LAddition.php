<?php

class LAddition implements LisPHPBinding {
	public function doEval($env = array(), $values) {
		return array_reduce($values, function($soFar, $value) {
			$soFar += $value;
			return $soFar;			
		});
	}
}