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

$title = elgg_echo('maps:open:members', [$group->getDisplayName()]);
$content = elgg_view('maps/members', [
	'group' => $group,
]);

$filter = '';
$sidebar = '';

$layout = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => $filter,
	'sidebar' => $sidebar,
		]);

echo elgg_view_page($title, $layout);
