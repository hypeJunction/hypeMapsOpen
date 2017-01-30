<?php

/**
 * hypeMapsOpen
 *
 * Maps built with open tech
 * 
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2017, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

use hypeJunction\MapsOpen\Geocoder;
use hypeJunction\MapsOpen\Groups;
use hypeJunction\MapsOpen\Router;
use hypeJunction\MapsOpen\Users;

elgg_register_event_handler('init', 'system', function() {

	// Implement geocoding via Nominatim
	elgg_register_plugin_hook_handler('geocode', 'location', [Geocoder::class, 'geocode']);
	elgg_register_plugin_hook_handler('geocode', 'latlong', [Geocoder::class, 'reverse']);

	// Geocode entity location whenever it's created or updated
	foreach (array('user', 'object', 'group', 'site') as $type) {
		elgg_register_event_handler('create', $type, [Geocoder::class, 'setEntityLatLong']);
		elgg_register_event_handler('update', $type, [Geocoder::class, 'setEntityLatLong']);
	}

	elgg_register_plugin_hook_handler('route', 'maps', [Router::class, 'routeMaps']);

	// Groups
	add_group_tool_option('member_map', elgg_echo('groups:tools:member_map'));
	elgg_register_plugin_hook_handler('tool_options', 'group', [Groups::class, 'filterToolOptions']);
	elgg_register_plugin_hook_handler('profile:fields', 'group', [Groups::class, 'addLocationField']);
	elgg_extend_view('groups/group_sort_menu', 'maps/groups/tabs', 100);
	elgg_extend_view('groups/tool_latest', 'maps/groups/members');

	// Users
	elgg_register_plugin_hook_handler('members:config', 'tabs', [Users::class, 'addMapTab']);
	elgg_register_plugin_hook_handler('profile:fields', 'profile', [Users::class, 'addLocationField']);
	elgg_register_plugin_hook_handler('route', 'members', [Router::class, 'routeMembers']);

	// CSS
	elgg_extend_view('elgg.css', 'leaflet.css');
	elgg_extend_view('elgg.css', 'leaflet.awesome-markers.css');
	elgg_extend_view('elgg.css', 'MarkerCluster.Default.css');
	elgg_extend_view('elgg.css', 'MarkerCluster.css');

	elgg_extend_view('elgg.css', 'page/components/map.css');

	elgg_define_js('leaflet-markers', [
		'src' => elgg_get_simplecache_url('leaflet.awesome-markers.min.js'),
		'deps' => ['leaflet'],
	]);

	elgg_define_js('leaflet-clusters', [
		'src' => elgg_get_simplecache_url('leaflet.markercluster.js'),
		'deps' => ['leaflet'],
	]);

	elgg_register_action('upgrade/maps/geocode', __DIR__ . '/actions/upgrade/maps/geocode.php', 'admin');
});

elgg_register_event_handler('upgrade', 'system', function() {
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	require __DIR__ . '/lib/upgrades.php';
});