<?php

use hypeJunction\MapsOpen\Geocoder;

// Register upgrade scripts
$path = 'admin/upgrades/maps/geocode';
$upgrade = new ElggUpgrade();
$upgrade = $upgrade->getUpgradeFromPath($path);

if ($upgrade instanceof ElggUpgrade) {
	// Upgrade already exists
	return;
}

$count = Geocoder::getEntitiesWithoutGeocodes(['count' => true]);

if ($count) {
	$upgrade = new ElggUpgrade();
	$upgrade->setPath($path);
	$upgrade->title = elgg_echo('admin:upgrades:maps:geocode');
	$upgrade->description = elgg_echo('admin:upgrades:maps:geocode:description');
	$upgrade->is_completed = false;
	$upgrade->save();
}
