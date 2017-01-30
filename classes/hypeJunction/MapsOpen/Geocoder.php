<?php

namespace hypeJunction\MapsOpen;

use ElggEntity;
use ElggFile;

class Geocoder {

	/**
	 * Geocode location via Nominatim
	 *
	 * @param string $hook   "geocode"
	 * @param string $type   "location"
	 * @param mixed  $return Lat/long
	 * @param array  $params Hook params
	 * @return mixed
	 */
	public static function geocode($hook, $type, $return, $params) {

		if (!empty($return)) {
			// location has been geocoded elsewhere
			return;
		}

		$location = elgg_extract('location', $params);

		// Try geocache
		$site = elgg_get_site_entity();
		$location_hash = md5($location);

		$file = new ElggFile();
		$file->owner_guid = $site->guid;
		$file->setFilename("nominatim/$location_hash.json");

		if ($file->exists()) {
			$file->open('read');
			$json = $file->grabFile();
			$file->close();
		} else {

			$endpoint = elgg_http_add_url_query_elements('http://nominatim.openstreetmap.org/search', [
				'q' => $location,
				'format' => 'json',
				'email' => elgg_get_site_entity()->email,
				'limit' => 1,
				'namedetails' => false,
			]);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $endpoint);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$json = curl_exec($ch);
			curl_close($ch);

			$file->open('write');
			$file->write($json);
			$file->close();
		}

		if (!$json) {
			return;
		}

		$data = json_decode($json, true);

		if (empty($data)) {
			return;
		}

		$item = array_shift($data);
		return array(
			'lat' => $item['lat'],
			'long' => $item['lon'],
		);
	}

	/**
	 * Geocode location via Nominatim
	 *
	 * @param string $hook   "geocode"
	 * @param string $type   "location"
	 * @param string $return Location
	 * @param array  $params Hook params
	 * @return mixed
	 */
	public static function reverse($hook, $type, $return, $params) {

		if (!empty($return)) {
			return;
		}

		$lat = elgg_extract('lat', $params);
		$long = elgg_extract('long', $params);
		$zoom = elgg_extract('zoom', $params, 12);

		// Try geocache
		$site = elgg_get_site_entity();
		$hash = md5("$lat:$long:$zoom");

		$file = new ElggFile();
		$file->owner_guid = $site->guid;
		$file->setFilename("nominatim/$hash.json");

		if ($file->exists()) {
			$file->open('read');
			$json = $file->grabFile();
			$file->close();
		} else {

			$endpoint = elgg_http_add_url_query_elements('http://nominatim.openstreetmap.org/reverse', [
				'lat' => $lat,
				'lon' => $long,
				'format' => 'json',
				'email' => elgg_get_site_entity()->email,
			]);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $endpoint);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$json = curl_exec($ch);
			curl_close($ch);

			$file->open('write');
			$file->write($json);
			$file->close();
		}

		if (!$json) {
			return;
		}

		$data = json_decode($json, true);

		if (empty($data)) {
			return;
		}

		return elgg_extract('display_name', $data);
	}

	/**
	 * Check if entity location has changed and geocode if so
	 *
	 * @param string     $event  "create"|"update"
	 * @param string     $type   "object"|"user"|"group"
	 * @param ElggEntity $entity Entity
	 */
	public static function setEntityLatLong($event, $type, $entity) {

		if ($entity->geocoded_location == $entity->location) {
			return;
		}

		if (is_array($entity->location) || !$entity->location) {
			return;
		}

		// Clear previous values
		unset($entity->{"geo:lat"});
		unset($entity->{"geo:long"});

		$entity->geocoded_location = $entity->location;

		$coordinates = (new MapsService())->geocode($entity->location);
		$lat = elgg_extract('lat', $coordinates) ?: '';
		$long = elgg_extract('long', $coordinates) ?: '';

		$entity->setLatLong($lat, $long);
	}

	/**
	 * Update entity geocoordinates
	 * @return int
	 */
	public static function setBatchLatLong() {

		set_time_limit(0);

		$entities = self::getEntitiesWithoutGeocodes([
			'batch' => true,
		]);

		$entities->setIncrementOffset(false);

		$i = 0;
		foreach ($entities as $e) {
			// trigger update
			$e->save();
			$lat = $e->getLatitude();
			$long = $e->getLongitude();
			if ($lat && $long) {
				elgg_log("New coordinates for {$e->getDisplayName()} ({$e->type}:{$e->getSubtype()} $e->guid) [$lat, $long]");
			}
			$i++;
		}

		return $i;
	}

	/**
	 * Get entities that are missing geographic coordinates
	 * 
	 * @param array $options ege* options
	 * @return ElggEntity[]|false
	 */
	public static function getEntitiesWithoutGeocodes(array $options = []) {

		$exclude = array(
			'messages',
			'plugin',
			'widget',
			'site_notification',
			'admin_notice',
		);

		foreach ($exclude as $k => $e) {
			$exclude[$k] = get_subtype_id('object', $e);
		}

		$exclude_ids = implode(',', array_filter($exclude));

		$location_md = elgg_get_metastring_id('location');
		$lat_md = elgg_get_metastring_id('geo:lat');
		$long_md = elgg_get_metastring_id('geo:long');

		$exclude = array(
			'messages',
			'plugin',
			'widget',
			'site_notification',
			'admin_notice',
		);

		foreach ($exclude as $k => $e) {
			$exclude[$k] = get_subtype_id('object', $e);
		}
		
		$exclude_ids = implode(',', array_filter($exclude));

		$location_md = elgg_get_metastring_id('location');
		$lat_md = elgg_get_metastring_id('geo:lat');
		$long_md = elgg_get_metastring_id('geo:long');

		$dbprefix = elgg_get_config('dbprefix');

		$options['wheres'][] = ($exclude_ids) ? "e.subtype NOT IN ($exclude_ids)" : null;
		$options['wheres'][] = "EXISTS (SELECT 1 FROM {$dbprefix}metadata WHERE entity_guid = e.guid AND name_id = $location_md)";
		$options['wheres'][] = "(NOT EXISTS (SELECT 1 FROM {$dbprefix}metadata WHERE entity_guid = e.guid AND name_id = $lat_md)
				OR NOT EXISTS (SELECT 1 FROM {$dbprefix}metadata WHERE entity_guid = e.guid AND name_id = $long_md))";
				
		$entities = elgg_get_entities($options);

		return $entities;
	}

}
