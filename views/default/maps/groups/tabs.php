<?php

if (!elgg_get_plugin_setting('enable_group_map', 'hypeMapsOpen')) {
	return;
}

elgg_register_menu_item('filter', [
	'name' => 'group:map',
	'text' => elgg_echo('maps:open:groups:tab'),
	'href' => 'groups/all?filter=map',
	'priority' => 220,
	'selected' => elgg_extract('selected', $vars) == 'map',
]);
