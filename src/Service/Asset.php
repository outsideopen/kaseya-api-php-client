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
use Kaseya\Service\Asset\Resource;

class Asset
	extends AbstractService
{
	/**
	 * @var Resource\Agents
	 */
	public $agents;

	/**
	 * @var Resource\Patches
	 */
	public $patches;

	/**
	 * @var string
	 */
	protected $serviceName = 'asset';

	public function __construct(Client $client)
	{
		$this->servicePath = 'assetmgmt';
		parent::__construct($client);

		$this->agents = new Resource\Agents(
			$this,
			$this->serviceName,
			'agents',
			[
				'all' => [
					'path'       => 'agents',
					'httpMethod' => 'GET',
				],
				'get' => [
					'path'       => 'agents/{agentId}',
					'httpMethod' => 'GET',
					'parameters' => [
						'agentId' => [
							'location' => 'path',
							'type'     => 'guid',
							'required' => true
						]
					]
				]
			]
		);

		$this->patches = new Resource\Patches(
			$this,
			$this->serviceName,
			'patches',
			[
				'status' => [
					'path'       => 'patch/{agentId}/status',
					'httpMethod' => 'GET',
					'parameters' => [
						'agentId' => [
							'location' => 'path',
							'type'     => 'guid',
							'required' => true
						],
					]
				]
			]
		);
	}
}