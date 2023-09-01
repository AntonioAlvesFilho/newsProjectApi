<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\EmailAlreadyInUseException;

class UserService {

	public function update(User $user, array $input) {
		
		if (User::where('email', $input['email'])->exists() && !empty($input['email'])) {
			throw new EmailAlreadyInUseException();
		}
		
		if (!empty($input['password'])) {
			$input['password'] = bcrypt($input['password']);

			$user->fill($input);
			$user->save();

			return $user->fresh();
		}
	}

}