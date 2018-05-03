<?php

/**
 * Copyright 2018 Outside Open, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Kaseya\Auth;

/**
 * Kaseya Auth Credentials class for the REST API
 *
 * This creates the hash in the manner documented by Kaseya
 *
 * @package Kaseya\Auth
 */
class Credentials
{
	private $hash;
	public function __construct($user, $password)
	{
		$rand2 = rand();
		$pass2 = hash("sha256", $password . $user);
		$pass1 = hash("sha1", $password . $user);
		$auth  = join(
			',', [
			'user=' . $user,
			'pass2=' . hash("sha256", $pass2 . $rand2),
			'pass1=' . hash("sha256", $pass1 . $rand2),
			'rpass2=' . hash("sha256", $password),
			'rpass1=' . hash("sha1", $password),
			'rand2=' . $rand2,
		]);

		$this->hash = base64_encode($auth);
	}

	public function hash()
	{
		return $this->hash;
	}
}