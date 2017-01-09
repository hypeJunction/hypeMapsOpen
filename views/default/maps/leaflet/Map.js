define(function (require) {

	var $ = require('jquery');
	var L = require('leaflet');
	require('leaflet-markers');
	require('leaflet-clusters');

	L.AwesomeMarkers.Icon.prototype.options.prefix = 'fa';

	var Ajax = require('elgg/Ajax');

	var Map = function (id, data) {
		this.id = id;
		this.data = {
			center: {
				lat: 0,
				long: 0,
			},
			zoom: 13,
			layer: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			layerOpts: {
				attribution: 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
			},
			markers: []
		};
	};

	var xhr;

	Map.prototype = {
		constructor: Map,
		markers: [],
		init: function () {

			var self = this;

			self.$map = $('#' + self.id);
			if (!self.$map.length) {
				return;
			}

			self.$component = self.$map.closest('.maps-component');
			self.$form = self.$component.find('.elgg-form');

			$.extend(self.data, self.$map.data());

			self.map = L.map(self.id).setView([self.data.center.lat, self.data.center.long], self.data.zoom);

			self.tiles = new L.TileLayer(self.data.layer, self.data.layerOpts);
			self.map.addLayer(self.tiles);

			self.clusters = L.markerClusterGroup({
				showCoverageOnHover: false
			});

			self.map.addLayer(self.clusters);

			self.setMarkers(self.data);
			self.loadMarkers();

			self.map.on('dragend', self.loadMarkers.bind(self));

			self.$map.parent().removeClass('elgg-ajax-loader');

			self.$form.on('submit', self.search.bind(self));

		},
		setMarkers: function (data) {

			if (!data.markers) {
				return;
			}

			var self = this;

			var Lmarkers = [];

			self.clusters.clearLayers();

			$.each(data.markers, function (index, marker) {
				if (!marker.lat || !marker.long) {
					return;
				}
				var opts = {};
				if (marker.icon) {
					opts.icon = L.AwesomeMarkers.icon({
						icon: marker.icon,
						markerColor: marker.color || 'blue'
					});
				}

				var Lmarker = L.marker([marker.lat, marker.long], opts);

				if (marker.tooltip) {
					Lmarker.bindPopup(marker.tooltip);
				}

				Lmarkers.push(Lmarker);
			});

			self.clusters.addLayers(Lmarkers);
		},
		loadMarkers: function () {
			var self = this;
			if (!self.data.src) {
				return;
			}

			if (xhr) {
				xhr.abort();
			}

			var data = {};

			var ajax = new Ajax();
			var data = ajax.objectify(self.$form);

			var center = self.map.getCenter();
			data.lat = center.lat;
			data.long = center.lng;

			// Get distance in meters from NW to center
			data.radius = Math.round(self.map.distance(self.map.getBounds().getNorthWest(), center) / 1000);
			if (self.$form) {
				self.$form.find('[name="radius"]').val(data.radius);
			}

			$.ajax({
				crossDomain: true,
				dataType: "json",
				url: '//nominatim.openstreetmap.org/reverse',
				data: {
					format: 'json',
					lat: data.lat,
					lon: data.long,
					zoom: 12,
				},
				success: function (response) {
					if (response.address) {
						var address = [response.address.city, response.address.state, response.address.country];
						address = address.filter(Boolean);
						data.location = address.join(', ');
						if (self.$form) {
							self.$form.find('[name="location"]').val(data.location);
						}
					}
					xhr = ajax.path(self.data.src, {
						data: data
					}).done(self.setMarkers.bind(self));
				}
			});
		},
		search: function (e) {

			e.preventDefault();

			var self = this;

			var ajax = new Ajax();
			var data = ajax.objectify(self.$form);

			if (data.location) {
				$.ajax({
					crossDomain: true,
					dataType: "json",
					url: '//nominatim.openstreetmap.org/search',
					data: {
						format: 'json',
						q: data.location
					},
					success: function (response) {
						var location = response.shift();

						if (location) {
							var center = new L.LatLng(location.lat, location.lon);
							self.map.panTo(center);

							data.lat = center.lat;
							data.long = center.lng;
						}

						xhr = ajax.path(self.data.src, {
							data: data
						}).done(self.setMarkers.bind(self));
					}
				});
			}
		}
	};

	return Map;
});