<?php

namespace App\Exceptions;

use Exception;

class VerifyEmailTokenInvalidException extends Exception
{
	public function render() {
		return response()->json([
			'error' => class_basename($this),
			'message' => 'Confirmation token not found.',
		], 400);
	}
}
