var sourceLoad = sourceLoad || {};

(function ($, $document, sourceLoad) {
	"use strict";

	var loaded = [],
		promise = false,
		deferred = $.Deferred();


	sourceLoad.load = function (srcs) {
		srcs = $.isArray(srcs)
			? srcs
			: srcs.split(/\s+/);

		if (!promise) {
			promise = deferred.promise();
		}

		$.each(srcs, function (index, src) {
			promise = promise.then(function () {
				return src.indexOf('.css') >= 0 ? loadCSS(src) : loadScript(src);
			});
		});

		deferred.resolve();

		return promise;
	};


	var loadScript = function (src) {
		if (loaded[src])
			return loaded[src].promise();

		var deferred = $.Deferred();
		var script = $document.createElement('script');

		script.src = src;

		script.onload = function (events) {
			deferred.resolve(events);
		};
		script.onerror = function (events) {
			deferred.reject(events);
		};

		$document.body.appendChild(script);

		loaded[src] = deferred;

		return deferred.promise();
	};


	var loadCSS = function (href) {
		if (loaded[href])
			return loaded[href].promise();

		var deferred = $.Deferred();
		var style = $document.createElement('link');

		style.rel = 'stylesheet';
		style.type = 'text/css';
		style.href = href;

		style.onload = function (events) {
			deferred.resolve(events);
		};

		style.onerror = function (events) {
			deferred.reject(events);
		};

		$document.head.appendChild(style);

		loaded[href] = deferred;

		return deferred.promise();
	}

})(jQuery, document, sourceLoad);