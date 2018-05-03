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

namespace Kaseya\Service\Asset;

use Kaseya\Collection;

class Agents
	extends Collection
{
	protected $collection_key = 'Result';
	protected $ResultType = Agent::class;
	protected $ResultDataType = 'array';
	public $kind;
	protected $Result;

	/**
	 * @param Agent
	 */
	public function setItems($items)
	{
		$this->{$collection_key} = $items;
	}

	/**
	 * @return Agent
	 */
	public function getItems()
	{
		return $this->{$collection_key};
	}

	public function setKind($kind)
	{
		$this->kind = $kind;
	}

	public function getKind()
	{
		return $this->kind;
	}
}