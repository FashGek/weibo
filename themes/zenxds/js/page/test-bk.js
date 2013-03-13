define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
		_ = require('underscore'),
        Config = window.Config;

    // escape/has/unset/clear/destory/toJSON
    var Group = Backbone.Model.extend({
            url: '/save/',
            // 
            initialize: function(attributes, options) {
                // change/destory/
                // this.on('error', function(model, error) {});
            },
            defaults: {
                name: '未分组'
            },
            validate: function(attributes) {
                if (attributes.name == '') {
                    return '分组名不能为空';
                }
            }
        }, {
            ClassPro: 'asd'
        }),

        // this.length
        // 
        GroupCollection = Backbone.Collection.extend({
            model: Group,

            // Collection([models], [options]) 
            initialize: function() {
                this.on('add', function(model) {

                });
            },
            comparator: function(item) {   // sort
                return item.get('id');
            }
        }),

        // view.listenTo
        // new xxView({model:})
        GroupView = Backbone.View.extend({
            el: $('body'),
            
            // li.group-item
            // tagName: 'li',
            // className: 'item',

            initialize: function(Collection, options) {
                // console.log(this);
                this.items = new Collection(Config.groups)
            },

            // render: function() {
            //     var template = _.template($("#test-tpl").html(),{
            //         name: '123'
            //     });

            //     this.$el.html(template);
            // },

            events: {
                "mouseover .item": "toggleActive",   //事件绑定，绑定Dom中id为check的元素
                "mouseout .item": "toggleActive"
            },

            toggleActive: function(e) {
                // console.log(this.model.get('name'));
                $(e.target).toggleClass('current');
            }
        });
    var groupViewInstance = new GroupView(GroupCollection);
    var g = new Group();
    console.log(g);
    console.log(Group.ClassPro);
});