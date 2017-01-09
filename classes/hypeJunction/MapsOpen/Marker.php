<?php

namespace hypeJunction\MapsOpen;

/**
 * @property string $title   Title
 * @property int    $guid    GUID
 * @property string $icon    FontAwesome icon name
 * @property string $color   Icon color
 * @property string $tooltip HTML content of the tooltip
 */
class Marker extends LatLong {
	
	/**
	 * @var string
	 */
	var $title = '';

	/**
	 * @var int
	 */
	var $guid = 0;

	/**
	 * @var string
	 */
	var $icon = '';

	/**
	 * @var string
	 */
	var $color = 'blue';

	/**
	 * @var string
	 */
	var $tooltip = '';
}
