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

namespace Kaseya\Service\Asset\Resource;

use Kaseya\Service\AbstractResource;
use Kaseya\Service\Asset\Patch;

class Patches
	extends AbstractResource
{
	/**
	 * @param       $agentId
	 * @param array $optParams
	 * @return Patch
	 */
	public function status($agentId, $optParams = array())
	{
		$params = array('agentId' => $agentId);
		$params = array_merge($params, $optParams);
		return $this->call('status', array($params), Patch::class);
	}
}
