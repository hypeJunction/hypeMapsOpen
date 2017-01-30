<?php

use hypeJunction\MapsOpen\Geocoder;

$count = Geocoder::getEntitiesWithoutGeocodes(['count' => true]);

// Register upgrade scripts
$path = 'admin/upgrades/maps/geocode';
$upgrade = new ElggUpgrade();
$upgrade = $upgrade->getUpgradeFromPath($path);
if ($count) {
	if (!$upgrade instanceof ElggUpgrade) {
		$upgrade = new ElggUpgrade();
	}
	$upgrade->setPath($path);
	$upgrade->title = elgg_echo('admin:upgrades:maps:geocode');
	$upgrade->description = elgg_echo('admin:upgrades:maps:geocode:description');
	$upgrade->is_completed = false;
	$upgrade->save();
}