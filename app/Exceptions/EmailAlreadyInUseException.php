<?php

namespace App\Exceptions;

use Exception;

class EmailAlreadyInUseException extends Exception
{
	public function render() {
		return response()->json([
			'error' => class_basename($this),
			'message' => 'Email already in use',
		], 400);
	}	
}