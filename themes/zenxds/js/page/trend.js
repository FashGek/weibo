define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
		_ = require('underscore'),
        mustache = require('mustache'),
        util = require('util'),
        Config = {
            request: {
                trend: "/weibo/user/trend",
                atUrl: "/weibo/user/at",
                commentUrl: "/weibo/user/comment",
                pmUrl: "/weibo/user/pm"
            }
        };

$(function() {
    var TrendModel = Backbone.Model.extend({
    		url: Config.request.trend,

    		initialize: function() {
    			this.set('atUrl', Config.request.atUrl);
    			this.set('commentUrl', Config.request.commentUrl);
                this.set('pmUrl', Config.request.pmUrl);
    		},

	    	defaults: {

	    	},

	    	// 是否hide用户动态
	    	isHide: function() {
	    		var attrs = this.attributes;
	    		if (attrs.atCount || attrs.commentCount || attrs.pmCount) {
	    			return false;
	    		}
	    		return true;
	    	}
    	}),
    	trendModel = new TrendModel(),
    	
    	TrendView = Backbone.View.extend({
    		el: $('#user-trend'),
    		template: mustache.compile($('#trend-tpl').html()),

            events:{

            },
            
            initialize:function(){
                _.bindAll(this, 'render');
                this.model.on('change', this.render);
                // this.model.on('destroy', this.remove);

                var interval = 30000,
                    _this = this;

                // Config.lastUpdateTime = util.getTime();

                _this.fetch();
                setInterval(function() {
                    _this.fetch();      // fix this
                }, interval);
            },
            
            fetch: function() {
                this.model.fetch({
                    data: {
                        time: window.Config.lastUpdateTime || util.formatDate("Y-m-d H:i:s")   // for weiboCount
                    }
                });
                this.render();
            },

            render: function(){
            	if (!this.model.isHide()) {
            		this.$el.html(this.template(this.model.toJSON()));
                	this.$el.fadeIn();
            	}
            	if (this.model.get('weiboCount')) {
                    var countTemplate = mustache.compile($('#weibo-count-tpl').html());
                    console.log('a');
                    $('#new-weibo-count').html(countTemplate({
                        weiboCount: this.model.get('weiboCount')
                    }));
                }
                return this;
            },
    	}),
    	trendView = new TrendView({
    		model: trendModel
    	});

});	// end $(f)

});