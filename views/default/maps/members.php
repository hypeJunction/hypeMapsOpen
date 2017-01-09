<?php

$group = elgg_extract('group', $vars);
if (!$group instanceof ElggGroup || !elgg_group_gatekeeper(false, $group->guid)) {
	return;
}

echo elgg_view('page/components/map', [
	'src' => elgg_http_add_url_query_elements('maps/users', [
		'group_guid' => $group->guid,
		'view' => 'json',
	]),
	'show_search' => true,
	'zoom' => 5,
	'layer_options' => [
		'minZoom' => 5,
	],
]);
