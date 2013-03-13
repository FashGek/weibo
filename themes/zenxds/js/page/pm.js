define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
		_ = require('underscore'),
        mustache = require('mustache'),
        Config = window.Config,
        util = require('util');

    var t;  // temp

    t = require('PmModel');
    var PmModel = t.model,
        PmCollection = t.collection;
$(function() {
    
    var pms = new PmCollection,
        
        PmView = Backbone.View.extend({
            className: 'pm-item fn-clear',
            template: mustache.compile($('#pm-tpl').html()),
            // 触发时this指向当前view，不需要bind
            events:{
                // 'click .delete': 'clear',
                'click .reply': 'reply',
                'click .delete': 'clear'

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
                var $modal = $('#pm-modal'),
                    $detail = $('#pm-detail'),
                    $title = $('#pm-title');
                
                $modal.data('id', this.model.get('user_id'));
                $modal.modal();  
                $title.val(this.model.get('nikename'));
            }   
        }),
        PmsView = Backbone.View.extend({

            events: {
                "click #save-pm2": 'addItem',
            },

            initialize: function() {
                _.bindAll(this, 'renderItem', 'renderItems'); 
            
                // this.listenTo(this.collection, 'add', this.renderItem);
                this.listenTo(this.collection, 'reset', this.renderItems);
                this.collection.fetch();

                this.$loading = this.$('.loading');
            },

            renderItem: function(item) {
                var view = new PmView({
                    model: item
                });
                this.$(".pms-area").prepend(view.render().el);
            },

            renderItems: function() {
                this.$loading.hide();
                this.collection.each(this.renderItem);
            },

            addItem: function() {
                var $modal = $('#pm-modal'),
                    $detail = $('#pm-detail'),
                    $title = $('#pm-title');
                this.collection.create(this.newWeiboAttributes());
                
                $modal.modal('hide');
                $detail.val('');
            },

            newWeiboAttributes: function() {
                var $modal = $('#pm-modal'),
                    $detail = $('#pm-detail'),
                    $title = $('#pm-title');

                return {
                    content: $detail.val(),
                    user_id: Config.requestor.id,
                    to_id: $modal.data('id'),
                };
            }
        }),
        pmsView = new PmsView({
            collection: pms,
            el: $('body')
        });

});	// end $(f)

});