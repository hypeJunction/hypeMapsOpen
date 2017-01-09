<?php

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggEntity) {
	return;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();

$views = [
	"maps/tooltip/$type/$subtype",
	"maps/tooltip/$type/default",
];

foreach ($views as $view) {
	if (elgg_view_exists($view)) {
		echo elgg_view($view, $vars);
		return;
	}
}

$subtitle = $entity->location;

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'subtitle' => $subtitle,
	'icon' => elgg_view_entity_icon($entity, 'small', [
		'use_hover' => false,
	]),
	'tags' => false,
	'content' => elgg_get_excerpt($entity->description),
]);