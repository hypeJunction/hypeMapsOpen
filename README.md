hypeMapsOpen for Elgg
=====================
![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

API and UI for maps built with open technology

## Screenshots

![User Map](https://raw.github.com/hypeJunction/hypeMapsOpen/master/screenshots/open-maps.png "User Map")

## Acknowledgements

 * Plugin has been partially sponsored by [Social Business World] (https://socialbusinessworld.org "Social Business World")

## Features

* Geocoding and reverse geocoding via Nominatim
* Maps built with Leaflet.js
* Default map tiles provided by Open Street Maps (customizagle in views)
* User map
* Groups map
* Group members map

## Usage

### A map of arbitrary locations

```php
echo elgg_view('page/components/map', [
	'markers' => [
		'Berlin, Germany',
		'London, UK',
		'Paris, France',
	]
]);
```

### A map with custom icons

```php

$berlin = hypeJunction\MapsOpen\Marker::fromLocation('Berlin, Germany');
$berlin->icon = 'smile-o';
$berlin->color = 'green';
$berlin->tooltip = '<b>Berlin is a happy place</b>';

$paris = hypeJunction\MapsOpen\Marker::fromLocation('Paris, France');
$paris->icon = 'coffee';
$paris->color = 'black';
$paris->tooltip = '<img src="https://s-media-cache-ak0.pinimg.com/736x/ca/ea/57/caea57268e1dee696f3c20a5a0f895f2.jpg" alt="Paris" />';

echo elgg_view('page/components/map', [
	'markers' => [
		$berlin,
		$paris,
	],
		]);

```

### A map of entities

```php
echo elgg_view('page/components/map', [
	'markers' => elgg_get_entities_from_metadata([
		'types' => 'object',
		'subtypes' => 'place',
		'metadata_name_value_pairs' => [
			'venue_type' => 'cafe',
		],
		'limit' => 0,
	]),
	'center' => hypeJunction\MapsOpen\Marker::fromLocation('London, UK');
		]);
```

### A map with data source and search

```php
echo elgg_view('page/components/map', [
	// Set src to json data source
	// Data set should be an export of Marker instances
	'src' => '/path/to/data/source/json',
	'show_search' => true,
		]);
```

### Change marker icon and color

Use `'marker','<entity_type>'` hook.
Supported colors: 'red', 'darkred', 'orange', 'green', 'darkgreen', 'blue', 'purple', 'darkpuple', 'cadetblue'


```php

elgg_register_plugin_hook_handler('marker', 'object', function($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if ($entity instanceof Event) {
		$return->icon = 'calendar';
		$return->color = 'darkpurple'
	}

	return $return;
})
```

### Change popup content

Add a view for `maps/tooltip/<entity_type>/<entity_subtype>` or `maps/tooltip/<entity_type>/default`;