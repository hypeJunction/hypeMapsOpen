<?php

if (!elgg_get_plugin_setting('enable_group_member_map', 'hypeMapsOpen')) {
	forward('', '404');
}

$group_guid = get_input('group_guid');
$group = get_entity($group_guid);

if (!$group instanceof ElggGroup) {
	forward('', '404');
}

elgg_set_page_owner_guid($group->guid);

elgg_group_gatekeeper(true);

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

$dbprefix = elgg_get_config('dbprefix');
$options = [
	'type' => 'user',
	'limit' => 0,
	'wheres' => [
		"
			EXISTS (SELECT 1 FROM {$dbprefix}entity_relationships
					WHERE guid_one = e.guid
					AND relationship = 'member'
					AND guid_two = $group_guid)
		"
	],
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
