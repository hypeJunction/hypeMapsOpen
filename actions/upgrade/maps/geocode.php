<?php


if (get_input('upgrade_completed')) {
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/maps/geocode');
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}
	return true;
}

$original_time = microtime(true);
$time_limit = 4;

$success_count = 0;
$error_count = 0;

$response = [];

while (microtime(true) - $original_time < $time_limit) {
	$success_count += hypeJunction\MapsOpen\Geocoder::setBatchLatLong();
}

if (elgg_is_xhr()) {
	$response['numSuccess'] = $success_count;
	$response['numErrors'] = $error_count;
	echo json_encode($response);
}
