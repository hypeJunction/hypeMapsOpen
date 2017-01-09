<?php

if (!elgg_get_plugin_setting('enable_user_map', 'hypeMapsOpen', false)) {
	return;
}

$svc = new \hypeJunction\MapsOpen\MapsService();

$location = get_input('location');
$lat = get_input('lat');
$long = get_input('long');
	
if ($location) {
	if ($lat && $long) {
		$location = new \hypeJunction\MapsOpen\LatLong($lat, $long, $location);
	} else {
		$location = \hypeJunction\MapsOpen\LatLong::fromLocation($location);
	}
} else if ($lat && $long) {
	$location = \hypeJunction\MapsOpen\LatLong::fromLatLong($lat, $long);
} 

if (!$location) {
	$location = $svc->getDefaultMapCenter();
}

$radius = get_input('radius');
if (!$radius) {
	$radius = 1000;
}

$query = get_input('query', '');

$options = [
	'type' => 'user',
	'limit' => 0,
];

elgg_set_viewtype('default');

$markers = $svc->getMarkers($options, $location, $radius, $query);

$response = [];
foreach ($markers as $marker) {
	$response['markers'][] = $marker->toArray();
}

$response['search'] = [
	'query' => $query,
	'radius' => $radius,
	'location' => $location->getLocation(),
	'lat' => $location->getLat(),
	'long' => $location->getLong(),
];

elgg_set_http_header('Content-Type: application/json');
echo json_encode($response);
return;
