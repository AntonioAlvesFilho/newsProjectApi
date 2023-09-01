<?php

namespace App\Exceptions;

use Exception;

class ForgotPasswordRequestExistsException extends Exception
{
	public function render() {
		return response()->json([
			'error' => class_basename($this),
			'message' => 'Reset Password Request already send, check your email',
		], 401);
	}
}
