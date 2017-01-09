hypeMapsOpen for Elgg
=====================
![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

API and UI for maps built with open technology

## Screenshots

![User Map](https://s30.postimg.org/3p3duaa6p/open_maps.png "User Map")

## Features

* Geocoding and reverse geocoding via Nominatim
* Maps built with Leaflet.js
* Default map tiles provided by Open Stree Maps

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
