<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('maps:open:setting:site_location'),
	'#help' => elgg_echo('maps:open:setting:site_location:help'),
	'name' => 'params[site_location]',
	'value' => $entity->site_location,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('maps:open:setting:enable_user_map'),
	'name' => 'params[enable_user_map]',
	'value' => 1,
	'checked' => (bool) $entity->enable_user_map,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('maps:open:setting:enable_group_map'),
	'name' => 'params[enable_group_map]',
	'value' => 1,
	'checked' => (bool) $entity->enable_group_map,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('maps:open:setting:enable_group_member_map'),
	'name' => 'params[enable_group_member_map]',
	'value' => 1,
	'checked' => (bool) $entity->enable_group_member_map,
]);