<?php

/**
 * Display a map
 *
 * @uses $vars['id'] Optional ID of the map
 * @uses $vars['src'] URL of the JSON data source
 * @uses $vars['markers'] ElggEntity[]|Marker[]|string[]
 * @uses $vars['center'] LatLong. Map center
 * @uses $vars['zoom'] Zoom level
 * @uses $vars['layer'] Layer URL
 * @uses $vars['layer_options'] Additional layer configuration options
 * @uses $vars['show_search'] Display a search form
 * @uses $vars['search_vars'] Search parameters
 */
use hypeJunction\MapsOpen\MapsService;
use hypeJunction\MapsOpen\Marker;
use Treffynnon\Navigator\LatLong;

$svc = new MapsService();

$id = elgg_extract('id', $vars, 'map-' . base_convert(mt_rand(), 10, 36));

$center = elgg_extract('center', $vars);
if (!$center instanceof LatLong) {
	$center = $svc->getDefaultMapCenter();
}

$markers = (array) elgg_extract('markers', $vars);
foreach ($markers as $key => $marker) {
	if (is_string($marker)) {
		$marker = Marker::fromLocation($marker);
	}
	if ($marker instanceof ElggEntity) {
		$marker = $svc->getMarker($marker);
	}
	if ($marker instanceof Marker) {
		$markers[$key] = $marker->toArray();
	} else {
		unset($markers[$key]);
	}
}

$form = '';
$src = elgg_extract('src', $vars);
if ($src && elgg_extract('show_search', $vars, false)) {
	$src = elgg_normalize_url($src);
	$search_vars = [
		'location' => $center->getLocation(),
	];

	$form = elgg_view_form('maps/search', [
		'action' => $src,
		'method' => 'GET',
			], $search_vars);
}

$width = elgg_extract('width', $vars);
$map = elgg_format_element('div', [
	'id' => $id,
	'class' => 'maps-map',
	'data-src' => $src,
	'data-center' => json_encode($center->toArray()),
	'data-layer' => elgg_extract('layer', $vars),
	'data-layer-opts' => json_encode(elgg_extract('layer_options', $vars)),
	'data-markers' => json_encode($markers),
	'data-zoom' => elgg_extract('zoom', $vars),
		]);
?>
<div class="maps-component">
	<?= $form ?>
	<div class="maps-filler elgg-ajax-loader">
		<?= $map ?>
	</div>
</div>
<script>
	require(['maps/leaflet/Map'], function (Map) {
		var map = new Map(<?= json_encode($id) ?>);
		map.init();
	});
</script>