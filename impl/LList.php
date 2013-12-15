<?php

class LList implements LisPHPBinding {
	public function doEval($env = array(), $values) {
		return ConsCell::of($values);
	}
}