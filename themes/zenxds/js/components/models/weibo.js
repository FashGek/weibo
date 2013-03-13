define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
        util = require('util'),
        Config = window.Config;

    var WeiboModel = Backbone.Model.extend({
            initialize: function() {
                this.setHumanDateline();

                this.set('isCollect', Boolean(this.get('isCollect')));
                if (this.get('author') == Config.requestor.id) {
                    this.set('isMe', true);
                }

                // 将原始的@和话题处理为带url的
                this.set('processContent', this.getProcessContent());
                this.on('change:content', function() {
                    this.set('processContent', this.getProcessContent());
                });
            },

            defaults: {
                isMe: false,
                forwardCount: 0,
                commentCount: 0
            },
            validate: function(attrs) {},

            setHumanDateline: function() {
                if (this.has('dateline')) {
                    this.set('humanDateline', util.transDate(this.get('dateline')));
                }
                if (this.has('root')) {
                    this.set('rootHumanDateline', util.transDate(this.get('root')['dateline']));
                }
            },

            //  处理 @ # 等转换为a
            getProcessContent: function() {
                var processAt = function(content) {
                        var pattern = /@([0-9a-zA-Z一-龥_-]+)?/g,
                            template = '<a class="title" href="{{url}}" title="{{name}}">@{{name}}</a>';
                        return content.replace(pattern, function(val) {
                            val = val.substring(1);
                            return util.substitute(template, {
                                name: val,
                                url: Config.request.baseUrl + '/n/' + val
                            });
                        });
                    },
                    processTopic = function(content) {
                        var pattern = /#([0-9a-zA-Z一-龥_-]+)?#/g,
                            template = '<a class="title" href="{{url}}" title="{{name}}">#{{name}}#</a>';
                            

                        return content.replace(pattern, function(val) {
                            var array = val.split('');
                            array.pop();
                            array.shift();
                            val = array.join('');
                            return util.substitute(template, {
                                name: val,
                                url: Config.request.baseUrl + '/t/' + val
                            });
                        });
                    };

                var content =  processAt(this.get('content'));
                content = processTopic(content);
                return content;
            },

            toggleCollect: function() {
                 if (this.get('isCollect')) {
                    this.set('isCollect', false);
                } else {
                    this.set('isCollect', true);
                }
                this.save();
            }
        }),

    	WeiboCollection = Backbone.Collection.extend({
            url: Config.request.weibo,
            model: WeiboModel,
            comparator: function(item) {
                return item.get('dateline');
            }
        });

	return {
		model: WeiboModel,
		collection: WeiboCollection
	}
});