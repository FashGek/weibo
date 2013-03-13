define(function(require, exports, module) {
	var $ = require('jquery');
	
	require('bootstrap')($);
	
	require.async('scrolltopcontrol');
	require.async('page/trend.js');
});