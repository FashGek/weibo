(function(seajs) { 
	var version = {
			jquery: '1.8.3',
			underscore: '1.4.2',
			marked: '0.2.4',
			bootstrap: '2.1.1',
			backbone: '0.9.9',
			mustache: '0.5.0'
		},
		alias = {
			'valuechange': 'components/valuechange.js',
			'util': 'components/util.js',
			'datepicker': 'components/bootstrap-datepicker.js',
			'scrolltopcontrol': 'components/scrolltopcontrol.js',

			// model
			'WeiboModel': 'components/models/weibo.js',
			'CommentModel': 'components/models/comment.js',
			'PmModel': 'components/models/pm.js'
			// view
			
		},
		modulePattern = 'gallery/{{name}}/{{version}}/{{name}}.js',
		
		substitute = function (str, o, regexp) {
            return str.replace(regexp || /\\?\{\{([^{}]+)\}\}/g, function (match, name) {
                if (match.charAt(0) === '\\') {
                    return match.slice(1);
                }
                return (o[name] === undefined) ? '' : o[name];
            });
        },
        name;
	
	for(name in version) {
		var o = {
			name: name,
			version: version[name]
		};
		alias[name] = substitute(modulePattern, o);
	};
	alias['$'] = alias['jquery'];

	seajs.config({
		debug: true,
		alias: alias,
		preload: ['jquery']
	});
})(seajs);