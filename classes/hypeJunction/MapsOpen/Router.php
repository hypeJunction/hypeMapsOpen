<?php

namespace hypeJunction\MapsOpen;

class Router {

	/**
	 * Route /maps
	 * 
	 * @param string $hook   "route"
	 * @param string $type   "maps"
	 * @param array  $return Route
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function routeMaps($hook, $type, $return, $params) {

		if (!is_array($return)) {
			return;
		}

		$segments = elgg_extract('segments', $return);
		$page = array_shift($segments);

		switch ($page) {
			case 'users' :
				if (elgg_get_plugin_setting('enable_user_map', 'hypeMapsOpen')) {
					echo elgg_view_resource('maps/users');
					return false;
				}
				break;

			case 'groups' :
				if (elgg_get_plugin_setting('enable_group_map', 'hypeMapsOpen')) {
					echo elgg_view_resource('maps/groups');
					return false;
				}
				break;

			case 'members' :
				if (elgg_get_plugin_setting('enable_group_member_map', 'hypeMapsOpen')) {
					echo elgg_view_resource('maps/members');
					return false;
				}
				break;
		}
	}

	/**
	 * Re-route /members/maps to /maps/users
	 *
	 * @param string $hook   "route"
	 * @param string $type   "members"
	 * @param array  $return Route
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function routeMembers($hook, $type, $return, $params) {

		$segments = elgg_extract('segments', $return);
		$page = array_shift($segments);

		switch ($page) {
			case 'map' :
				if (elgg_get_plugin_setting('enable_user_map', 'hypeMapsOpen')) {
					echo elgg_view_resource('maps/users');
					return false;
				}
				break;
		}
	}

}
