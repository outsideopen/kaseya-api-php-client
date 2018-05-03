<?php

/**
 * Lists all the agents that need a restart
 */

require_once __DIR__ . '/vendor/autoload.php';

$client   = new \Kaseya\Client('kaseya.example.com', 'agent', 'password');
$asset    = new \Kaseya\Service\Asset($client);
$machines = [];

$agents = $asset->agents->all();
$count  = $agents->TotalRecords;
// account for having a lot of agents
for ($i = 0; $i < $count;) {
	foreach ($agents as $agent) {
		$patch = $asset->patches->status($agent->AgentId);
		if ($patch->Reset == 2) {
			$key              = explode('.', $agent->AgentName);
			$key              = array_reverse($key);
			$machine          = array_pop($key);
			$key              = join('.', $key);
			$machines[$key][] = $machine;
		}
		$i++;
	}

	$agents = $asset->agents->all(['$skip' => $i]);
}

ksort($machines);
foreach ($machines as $group => $data) {
	echo "==> {$group}\n";
	sort($data);
	foreach ($data as $datum) {
		echo "    $datum\n";
	}
}
