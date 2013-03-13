define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
        util = require('util'),
        Config = window.Config;

    var CommentModel = Backbone.Model.extend({
            initialize: function() {
                this.setHumanDateline();
    
                if (this.get('user_id') == Config.requestor.id) {
                    this.set('isMe', true);
                }

                // 将原始的@和话题处理为带url的
                this.set('processContent', this.getProcessContent());
                this.on('change:content', function() {
                    this.set('processContent', this.getProcessContent());
                });
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
                };

                var content =  processAt(this.get('content'));
                return content;
            },

            setHumanDateline: function() {
                this.set('humanDateline', util.transDate(this.get('dateline')));
            },

            validate: function(attrs) {

            }
        }),
        CommentCollection = Backbone.Collection.extend({
            url: Config.request.comments,
            model: CommentModel,
            initialize: function() {

            }
        });

	return {
		model: CommentModel,
		collection: CommentCollection
	}
});