<?php

class LPrint implements LisPHPBinding {
	public function doEval($env = array(), $values) {
		$strToPrint = array_reduce($values, function($soFar = "", $value) {
			if(is_a($value, "ConsCell")) {
				return $soFar .= $value->asTuple() . "\n";
			}
			return $soFar .= $value;
		});

		echo trim($strToPrint) . "\n";
	}
}