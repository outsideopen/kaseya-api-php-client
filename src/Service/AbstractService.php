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

namespace Kaseya\Service;

use Kaseya\Client;

/**
 * Abstract service class for Services to extend
 *
 * @package Kaseya\Service
 */
abstract class AbstractService
{
	public $servicePath;
	private $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function client()
	{
		return $this->client;
	}
}