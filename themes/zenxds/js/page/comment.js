define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
		_ = require('underscore'),
        mustache = require('mustache'),
        Config = window.Config,
        util = require('util');

    var t;  // temp

    t = require('CommentModel');
    var CommentModel = t.model,
        CommentCollection = t.collection;
$(function() {
    
    var comments = new CommentCollection,
        
        CommentView = Backbone.View.extend({
            className: 'comment-detail-item fn-clear',
            template: mustache.compile($('#comment-detail-tpl').html()),
            // 触发时this指向当前view，不需要bind
            events:{
                'click .delete': 'clear',
                'click .reply': 'reply'
            },
            
            initialize:function(){
                _.bindAll(this, 'render', 'remove');

                // 触发时this指向model，需要bind才指向当前view
                this.model.on('change', this.render);
                this.model.on('destroy', this.remove);
            },
            
            render: function(){
                this.$el.html(this.template(this.model.toJSON()));
                return this;
            },

            clear: function() {
                this.model.destroy();
            },

            reply: function() { 
                var $input = this.$el.parent().parent().find('.comment-detail'),
                    nikename = this.model.get('nikename'),
                    newValue = "回复@" + nikename + ":";
                
                $input.data('id', this.model.get('id'));

                $input.focus();
                $input.val('').val(newValue);
                    
            },
        }),
        ComentsView = Backbone.View.extend({
            inputTemplate: mustache.compile($('#comment-detail-input-tpl').html()),

            events: {
                "click .submit": 'addComment',
            },

            initialize: function() {
                _.bindAll(this, 'renderItem', 'renderItems'); 
            
                this.listenTo(this.collection, 'add', this.renderItem);
                this.listenTo(this.collection, 'reset', this.renderItems);
                this.collection.fetch();

                this.$loading = this.$('.loading');
            },

            renderItem: function(item) {
                var view = new CommentView({
                    model: item
                });
                this.$(".comments-area").prepend(view.render().el);
            },

            renderItems: function() {
                this.$loading.hide();
                this.$el.prepend(this.inputTemplate({}));
                this.collection.each(this.renderItem);
            },

            addComment: function() {
                var $input = this.$el.find('.comment-detail');
                if (util.imperfectTextCheck($input)) {
                    this.collection.create(this.newWeiboAttributes());
                
                    $input.val('');
                }
            },

            newWeiboAttributes: function() {
                var $input = this.$el.find('.comment-detail'),
                    content = $input.val(),
                    checkboxes = this.$el.find(':checkbox'),
                    id = $input.data('id'),
                    root,
                    rootJSON;

                if (id) {
                    root = comments.get(id);
                    rootJSON = root.toJSON();
                }

                return {
                    content: $input.val(),
                    dateline: util.getTime(),
                    user_id: Config.requestor.id,
                    weibo_id: this.$el.data('weiboId'),
                    avatarUrl: Config.requestor.avatarUrl,
                    nikename: Config.requestor.nikename,
                    isMe: true,
                    root_id: id,
                    root: rootJSON,
                    isForward: checkboxes.eq(0).is(":checked")
                }   
            }
        }),
        commentsView = new ComentsView({
            collection: comments,
            el: $('#comments-area-wrapper')
        });

});	// end $(f)

});