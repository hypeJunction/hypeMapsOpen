<?php

$defaults =[
	'site_location' => 'Greenwich, London, UK',
];

foreach ($defauls as $name => $value) {
	if (is_null(elgg_get_plugin_setting($name, 'hypeMapsOpen'))) {
		elgg_set_plugin_setting($name, $value, 'hypeMapsOpen');
	}
}