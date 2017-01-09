<?php

if (!elgg_get_plugin_setting('enable_group_map', 'hypeMapsOpen')) {
	forward('', '404');
}

$title = elgg_echo('maps:open:groups');
$content = elgg_view('maps/users');

$filter = '';
$sidebar = '';
if (elgg_in_context('members')) {
	$tabs = elgg_trigger_plugin_hook('members:config', 'tabs', null, []);

	foreach ($tabs as $type => $values) {
		$tabs[$type]['selected'] = ('map' == $type);
	}
	$filter = elgg_view('navigation/tabs', [
		'tabs' => $tabs,
	]);
	$sidebar = elgg_view('members/sidebar');
}

$layout = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => $filter,
	'sidebar' => $sidebar,
		]);

echo elgg_view_page($title, $layout);
