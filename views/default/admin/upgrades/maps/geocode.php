<?php

$count = hypeJunction\MapsOpen\Geocoder::getEntitiesWithoutGeocodes(['count' => true]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:upgrades:scraper:move_to_db:description')
]);

echo elgg_view('admin/upgrades/view', [
	'count' => $count,
	'action' => 'action/upgrade/maps/geocode',
]);
