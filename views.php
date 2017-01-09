<?php

$plugin_root = __DIR__;
$root = dirname(dirname($plugin_root));
$alt_root = dirname(dirname(dirname($root)));

if (file_exists("$plugin_root/vendor/autoload.php")) {
	$path = $plugin_root;
} else if (file_exists("$root/vendor/autoload.php")) {
	$path = $root;
} else {
	$path = $alt_root;
}

return [
	'default' => [
		'/' => [
			$path . '/vendor/bower-asset/leaflet/dist/',
			$path . '/vendor/bower-asset/leaflet.awesome-markers/dist/',
			$path . '/vendor/bower-asset/leaflet.markercluster/dist/',
		],
	],
];
