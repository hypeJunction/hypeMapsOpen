<?php

namespace hypeJunction\MapsOpen;

class LatLong {

	protected $lat;
	protected $long;
	protected $location;

	/**
	 * Constructor
	 * 
	 * @param float  $lat      Latitude
	 * @param float  $long     Longitude
	 * @param string $location Location name/address
	 */
	public function __construct($lat, $long, $location = '') {
		$this->lat = $lat;
		$this->long = $long;
		$this->location = $location;
	}

	/**
	 * Construct from location address/name
	 * Get coordinates through geocodong
	 * 
	 * @param string $location Location
	 * @return static
	 */
	public static function fromLocation($location = '') {
		$svc = new MapsService();
		$coords = $svc->geocode($location);
		return new static($coords['lat'], $coords['long'], $location);
	}

	/**
	 * Construct from lat/long and lookup the location name
	 *
	 * @param float $lat  Latitude
	 * @param float $long Longitude
	 * @param int   $zoom Zoom/precision level
	 * @return static
	 */
	public static function fromLatLong($lat, $long, $zoom = 12) {
		$svc = new MapsService();
		$location = $svc->reverse($lat, $long, $zoom);
		return new static($lat, $long, $location);
	}

	/**
	 * Returns latitude
	 * @return float
	 */
	public function getLat() {
		return (float) $this->lat;
	}

	/**
	 * Returns longitude
	 * @return float
	 */
	public function getLong() {
		return (float) $this->long;
	}

	/**
	 * Returns location address
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Export lat, long and location to an array
	 * @return array
	 */
	public function toArray() {
		return get_object_vars($this);
	}
}
