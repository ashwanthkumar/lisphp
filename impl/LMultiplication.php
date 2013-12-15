<?php

class LMultiplication implements LisPHPBinding {
	public function doEval($env = array(), $values) {
		return array_reduce($values, function($soFar, $value) {
			if($soFar == 0) $soFar = 1;
			$soFar *= $value;
			return $soFar;			
		});
	}
}