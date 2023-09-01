<?php

namespace App\Exceptions;

use Exception;

class ForgotPasswordEmailNotExistsException extends Exception
{
	public function render() {
		return response()->json([
			'error' => class_basename($this),
			'message' => 'Email not found',
		], 404);
	}
}
