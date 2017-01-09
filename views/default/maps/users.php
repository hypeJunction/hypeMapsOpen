<?php

echo elgg_view('page/components/map', [
	'src' => elgg_http_add_url_query_elements('maps/users', [
		'view' => 'json',
	]),
	'show_search' => true,
	'zoom' => 5,
	'layer_options' => [
		'minZoom' => 5,
	],
]);
