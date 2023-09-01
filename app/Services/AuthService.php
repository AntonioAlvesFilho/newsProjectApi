<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Events\ForgotPassword;
use App\Events\ResetedPassword;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\EmailAlreadyInUseException;
use App\Exceptions\VerifyEmailTokenInvalidException;
use App\Exceptions\ResetPasswordTokenInvalidException;
use App\Exceptions\ForgotPasswordRequestExistsException;
use App\Exceptions\ForgotPasswordEmailNotExistsException;

class AuthService
{
	public function login(string $email, string $password) {

		$login = [
			'email' => $email,
			'password' => $password
		];

		if (!$token = auth()->attempt($login)) {
			throw new LoginInvalidException();
		}
		
		return [
			'access_token' => $token,
			'token_type' => 'Bearer',
		];
	}

	public function register(string $name, string $email, string $data_nascimento, string $genero, string $password) {
		

		$user = User::where('email', $email)->exists();
		if (!empty($user)) {
			throw new EmailAlreadyInUseException();
		}
		
		$userPassword = bcrypt($password ?? Str::random(10));

	
		$user = User::Create([
			'name' => $name,
			'email' => $email,
			'data_nascimento' => $data_nascimento,
			'genero' => $genero,
			'password' => $userPassword,
			'confirmation_token' => Str::random(30)
		]);

		event(new UserRegistered($user));

		return $user;
	}

	public function VerifyEmail(String $token) {
		$user = User::where('confirmation_token', $token)->first();
		if (empty($user)) {
			throw new VerifyEmailTokenInvalidException();
		}
		
		$user->confirmation_token = 'already_verified';
		$user->email_verified_at = now();
		$user->save();

		return $user;


	}

	public function forgotPassword(string $email) {

		$user = User::where('email', $email)->firstOrFail();
		$token = Str::random(60);
		
		$checkIfExistsBeforeCreate = PasswordReset::where('email', $email)->exists();
		
		if ($checkIfExistsBeforeCreate) {
			throw new ForgotPasswordRequestExistsException();
		}

		if (!$user) {
			throw new ForgotPasswordEmailNotExistsException();
		}
		
		PasswordReset::create([
			'email' => $user->email,
			'token' => $token,
		]);

		

		event(new ForgotPassword($user, $token));
		return response('success', 202);
	}

	public function resetPassword(string $email, string $password, string $token) {

			$passReset = PasswordReset::where('email', $email)->where('token', $token)->firstOrFail();
			if (empty($passReset)) {
				throw new ResetPasswordTokenInvalidException();

			}
			$user = User::where('email', $passReset->email)->firstOrFail();
			$user->password =  bcrypt($password);
			$user->save();

			PasswordReset::where('email', $passReset->email)->delete();

			event(new ResetedPassword($user, $token));

			return response('success', 202);
	}

}