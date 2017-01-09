<?php

$fields = elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('maps:open:search:location'),
	'name' => 'location',
	'value' => elgg_extract('location', $vars),
]);

$fields .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('maps:open:search:radius'),
	'name' => 'radius',
	'value' => elgg_extract('radius', $vars),
]);
$fields .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('maps:open:search:query'),
	'name' => 'query',
	'value' => elgg_extract('query', $vars),
]);

$fields .= elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('search'),
]);

?>
<div class="maps-search-fields">
	<?= $fields ?>
</div>
