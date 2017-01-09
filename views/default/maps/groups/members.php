<?php

$group = elgg_get_page_owner_entity();

if (!elgg_group_gatekeeper(false)) {
	return;
}

if ($group->member_map_enable == 'no') {
	return;
}

$all_link = elgg_view('output/url', array(
	'href' => "groups/members/$group->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

$content = elgg_view('page/components/map', [
	'src' => elgg_http_add_url_query_elements('maps/members', [
		'group_guid' => $group->guid,
		'view' => 'json',
	]),
	'show_search' => false,
	'zoom' => 3,
	'layer_options' => [
		'minZoom' => 3,
	],
]);

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('groups:members'),
	'content' => $content,
	'all_link' => $all_link,
));
