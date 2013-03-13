define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
		_ = require('underscore'),
        mustache = require('mustache'),
        util = require('util'),
        valuechange = require('valuechange'),
        Config = window.Config;

    var t;  // temp

    t = require('WeiboModel');
    var WeiboModel = t.model,
        WeiboCollection = t.collection;

    t = require('CommentModel');
    var CommentModel = t.model,
        CommentCollection = t.collection;

$(function() {

    var weibos = new WeiboCollection(),

        WeiboView = Backbone.View.extend({
            className: 'weibo-item fn-clear',
            template: mustache.compile($('#weibo-tpl').html()),

            // 触发时this指向当前view，不需要bind
            events:{
                'click .delete' : 'clear',
                'click .forward': 'forward',
                'click .collect': 'toggleCollect',
                'click .comment': 'comment',
                'click .image-area': 'toggleImageSize'
            },
            
            initialize:function(){
                _.bindAll(this, 'render', 'remove');

                // 触发时this指向model，需要bind才指向当前view
                this.model.on('change', this.render);
                this.model.on('destroy', this.remove);

                this.isCommentLoaded = false;
            },
            
            render: function(){
                this.$el.html(this.template(this.model.toJSON()));
                return this;
            },

            forward: function() {
                var $modal = $('#forward-modal');
                $modal.modal();
                $modal.data('forward_id', this.model.get('id'));

                // 转发时的默认内容
                var defaultValue;
                if (this.model.get('root_id')) {    // 当前微博是转发
                    var defaultValue = "//@" + this.model.get('nikename') + ": " + this.model.get('content'); 
                } else { // 当前微博是原创
                    defaultValue = "转发微博";
                }

                // 更新modal和模型
                $modal.find('.input-detail').val(defaultValue).focus();
                forwardInput.set('content', defaultValue);
                
            },

            clear: function() {
                this.model.destroy();
            },

            toggleCollect: function() {
                this.model.toggleCollect();
            },

            comment: function() {
                var $commentArea = this.$el.find(".comment-area");
                $commentArea.toggleClass('fn-hide');

                // comments are loaded
                if (this.isCommentLoaded) {
                    return;
                }
                var weiboId = this.model.get('id'),
                    commentCollction = new CommentCollection(),
                    commentsView = new CommentsView({
                        collection: commentCollction,
                        el: $commentArea
                    });

                commentCollction.fetch({
                    data: {
                        id: weiboId
                    }
                });
                this.isCommentLoaded = true;

                $commentArea.data('weiboId', weiboId);
            },

            toggleImageSize: function() {
                this.$('.image-area').toggleClass('small');
            }
        });


    var WeibosAppView = Backbone.View.extend({
        el: $('body'),
        
        events: {
            'click #J-weiboCount': 'fetchNew'
        },
        
        initialize: function() {
            _.bindAll(this, 'renderWeibo', 'renderWeibos'); 
            
            this.listenTo(this.collection, 'add', this.renderWeibo);
            this.listenTo(this.collection, 'reset', this.renderWeibos);

            var $modal = $('#forward-modal');
            $modal.on('hidden', function() {
                $modal.find('.input-detail').val('');
                forwardInput.set('content', '');
            });

            this.$loading = $('#items-wrapper .loading');
            this.$weiboCount = $('#new-weibo-count');
            this.fetch();
        },

        renderWeibo: function(item) { 
            var view = new WeiboView({
                model: item
            });
            this.$(".items-area").prepend(view.render().el);
        },
        renderWeibos: function() { 
            this.$loading.hide();
            this.$weiboCount.html('');

            weibos.each(this.renderWeibo);
        },

        fetch: function() {
            this.$(".items-area").html('');
            this.$loading.show();
            if (Config.request.weibos) {
                this.collection.fetch({
                    url: Config.request.weibos,
                    data: {
                      id: Config.user.id  
                    }
                });
            } else {
                this.collection.fetch({
                    data: {
                      id: Config.user.id  
                    }
                });
            }

            Config.lastUpdateTime = util.formatDate("Y-m-d H:i:s");
        },
        fetchNew: function() {
            this.fetch();
        }
    });
    
    var weibosAppView = new WeibosAppView({
        collection: weibos
    });



    var FollowModel = Backbone.Model.extend({
            url: Config.request.follow,
            defaults: {
                requestor: Config.requestor.id,
                author: Config.user.id,
                isMe: Boolean(Config.isMe),
                isFollow: Boolean(Config.isFollow)
            },

            initialize: function() {

            },

            toggleFollow: function() {
                if (this.get('isFollow')) {
                    this.set('isFollow', false);
                } else {
                    this.set('isFollow', true);
                }
                this.save({
                    success: function(model, response, options) {
                    
                    }
                });
            }
        }),

        followModel = new FollowModel();

        FollowView = Backbone.View.extend({
            el: $('body'),

            template: mustache.compile($('#follow-tpl').html()),

            events: {
                "click #J-toggle-follow": "toggleFollow"
            },
            
            initialize: function() {
                this.followArea = this.$('.toggle-follow');
                this.render();

                _.bindAll(this, 'render');
                this.model.on('change', this.render);
            },

            render: function() {
                this.followArea.html(this.template(this.model.toJSON()));
                return this;
            },

            toggleFollow: function() {
                this.model.toggleFollow();
            }

        }),

        followView = new FollowView({
            model: followModel
        });


    var WeiboInputModel = Backbone.Model.extend({
            initialize: function() {
                this.on("change:content", function() {
                    var remainLength = Config.maxWeiboLength - this.get('content').length,
                        disabled = (remainLength < 0 || remainLength == Config.maxWeiboLength) ? true: false;
                    
                    this.set({
                        remainLength: remainLength,
                        disabled: disabled
                    });
                });
            },

            defaults: {
                remainLength: Config.maxWeiboLength,
                disabled: true,             //
                content: '',
                cursorPosition: 0
            },
            validate: function(attr) {
                
            }
        }),

        InputView = Backbone.View.extend({

            events: {
                "blur .input-detail": "updateCursorPositon",
                "click #J-submit": "addWeibo",
                "click #save-forward": "addForward"
            },
            
            initialize: function() {
                this.$remain = this.$('.length-remain');
                this.$submit = this.$('.weibo-submit');
                this.$input = this.$('.input-detail');
                this.$modal = $('#forward-modal');

                var model = this.model;
                $.valueChange('.input-detail', function(preVal, nowVal) {
                    model.set('content', $.trim(nowVal));
                }, this.$el);
                
                _.bindAll(this, 'render', 'updateCursorPositon', 'addWeibo');
                this.model.on('change', this.render);
            },

            render: function() {
                this.updateCursorPositon();
                var remainLength = this.model.get('remainLength');
                if (remainLength >= 0) {
                    this.$remain.removeClass('warning');
                    this.$remain.html("还可以输入" + remainLength);
                } else {
                    this.$remain.addClass('warning');
                    remainLength = -remainLength;
                    this.$remain.html("已超出" + remainLength);
                }
                
                if (this.model.get('disabled')) {
                    this.$submit.removeClass('btn-danger');
                    this.$submit.addClass('disabled');
                } else {
                    this.$submit.addClass('btn-danger');
                    this.$submit.removeClass('disabled');
                }
                return this;
            },

            updateCursorPositon: function() {
                this.model.set('cursorPosition', util.getCursortPosition(this.$input.get(0)));
            },

            addWeibo: function(e) {
                var target = e.target;
                if ($(target).hasClass('disabled')) {
                    return false;
                }
                weibos.create(this.newWeiboAttributes());
            },

            addForward: function(e) {
                var target = e.target;
                if ($(target).hasClass('disabled')) {
                    return false;
                }

                var attrs = this.newWeiboAttributes(),
                    forward_id = this.$modal.data('forward_id'),
                    forwardWeibo = weibos.get(forward_id),
                    root_id;
                
                if (forwardWeibo.get('root_id')) {
                    root_id =  forwardWeibo.get('root_id');
                    attrs['forward_id'] = forward_id;
                } else {
                    root_id = forward_id;
                }
                var rootWeibo = weibos.get(root_id);

                $.extend(attrs, {
                    root_id: root_id,
                    root: rootWeibo.toJSON()
                });
                weibos.create(attrs);
                this.$modal.modal('hide');
            },

            newWeiboAttributes: function() {
                var content = this.$input.val(),
                    imageUrl = $('#image-url').val() || '';
                return {
                    content: content,
                    author: Config.requestor.id,
                    avatarUrl: Config.requestor.avatarUrl,
                    userUrl: Config.requestor.url,
                    dateline: util.getTime(),
                    image_url: imageUrl
                };
            }

        });

    if (Config.isInput) {
        var mainInput = new WeiboInputModel(),
            mainInputView = new InputView({
                el: $('#publisher'),
                model: mainInput
            });
    }
       
    var forwardInput = new WeiboInputModel(),
        forwardView = new InputView({
            el: $('#forward-modal'),
            model: forwardInput
        });



    var FaceModel = Backbone.Model.extend({
            initialize: function() {
                var type = 'qq';
                if (this.get('url').indexOf('tusiji') > -1) {
                    type = 'tusiji';
                }
                this.set('type', type);
            }
        });

    var FaceCollection = Backbone.Collection.extend({
            url: Config.request.face,
            model: FaceModel,
            isLoaded: false
        }),
        faces = new FaceCollection();

    var FaceView = Backbone.View.extend({
        className: 'fn-inline face-item',
        template: mustache.compile($('#face-tpl').html()),

        events:{
            'click' : 'transFace'
        },
        
        initialize:function(){
            _.bindAll(this, 'render', 'transFace');
            this.$input = $('.input-detail');
        },
        
        render: function(){
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },

        // 获取光标位置，插入表情代码
        transFace: function() {
            var name = '[' + this.model.get('name') + ']',
                elem = this.$input.get(0),
                value = elem.value,
                cursorPosition = mainInput.get('cursorPosition'),
                array,
                newValue;

            array = value.split('');
            array.splice(cursorPosition, 0, name);
            newValue = array.join('');

            // 设置新值及光标位置
            mainInput.set('content', newValue);
            this.$input.val(newValue);
            util.setCursorPosition(elem, cursorPosition + 4);
            
            funcAppView.hideSubArea();
        }
    });

    var FuncAppView = Backbone.View.extend({
        el: $('body'),
        
        events: {
            "click .icon-face": "showFaces",
            "click .icon-qing": "insertTopic",
            'click .icon-img': "insertImage",
            "click .sub-content-area .close": "hideSubArea",
        },
        
        initialize: function() {
            _.bindAll(this,'addFaces','addFace'); 

            this.$subArea = this.$('.sub-content-area');
            this.qq = this.$('#qq');
            this.tusiji = this.$('#tusiji');

            faces.on('reset', this.addFaces);

            this.imageModal = $('#image-modal');
            this.imageModal.on('hidden', function() {
                if ($('#image-url').val()) {
                    $('.input-detail').val('分享图片');
                    mainInput.set('content', '分享图片');
                }
            });
        },
        showSubArea: function() {
           this.$subArea.addClass('active'); 
        },

        hideSubArea: function() {
            this.$subArea.removeClass('active'); 
            this.$subArea.find('.sub-item').hide();
        },
        showFaces: function(e) {
            if (!faces.isLoaded) {
                faces.fetch();
                faces.isLoaded = true;
            }
            this.showSubArea();
            this.$('#face-detail').show();
        },
        addFace: function(item) {
            var type = item.get('type');
            var view = new FaceView({
                model: item
            });
            this[type].append(view.render().el);
        },
        
        addFaces: function() {
            faces.each(this.addFace);
        },

        insertTopic: function() {
            var name = '#此处插入话题#',
                $input = $('.input-detail'),
                elem = $input.get(0),
                value = elem.value,
                cursorPosition = mainInput.get('cursorPosition'),
                array,
                newValue;

            array = value.split('');
            array.splice(cursorPosition, 0, name);
            newValue = array.join('');

            // 设置新值及光标位置
            mainInput.set('content', newValue);
            $input.val(newValue);
            // console.log(util); 
            util.textSelect(elem, cursorPosition + 1, cursorPosition + name.length -1);
            // util.setCursorPosition(elem, cursorPosition + name.length - 1);
        },

        insertImage: function() {
            this.imageModal.modal();
        }

    });
    
    var funcAppView = new FuncAppView;




    
    var CommentView = Backbone.View.extend({
            className: 'comment-item fn-clear',
            template: mustache.compile($('#comment-tpl').html()),

            events:{
                "click .reply": 'reply',
                "click .deleteWeibo": 'clear'
            },
            
            initialize:function(){
                _.bindAll(this, 'render', 'remove', 'reply');
                this.model.on('change', this.render);
                this.model.on('destroy', this.remove);
            },
            
            render: function(){
                this.$el.html(this.template(this.model.toJSON()));
                return this;
            },

            reply: function() { 
                var $input = this.$el.parent().parent().find('.comment-detail'),
                    nikename = this.model.get('nikename'),
                    newValue = "回复@" + nikename + ":";
                
                $input.focus();
                $input.val('').val(newValue);
                    
            },

            clear: function() {
                this.model.destroy();
            }
        }),
        CommentsView = Backbone.View.extend({

            template: mustache.compile($('#comment-input-tpl').html()),

            events: {
                "click .submit": 'addComment',
            },

            initialize: function() {
                _.bindAll(this, 'renderItem', 'renderItems', 'addComment'); 
            
                this.listenTo(this.collection, 'add', this.renderItem);
                this.listenTo(this.collection, 'reset', this.renderItems);
                
                this.$loading = this.$el.find('.comment-loading');
                
            },

            renderItem: function(item) {
                var view = new CommentView({
                    model: item
                });
                this.$el.find(".comment-list").prepend(view.render().el);
            },

            renderItems: function() {
                this.$loading.show();
                this.$el.prepend(this.template({
                    // avatarUrl: Config.requestor.avatarUrl,
                    // userUrl: Config.requestor.url,
                    // nikename: Config.requestor.nikename
                }));
                this.collection.each(this.renderItem);
                this.$loading.hide();
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
                    checkboxes = this.$el.find(':checkbox');

                return {
                    content: $input.val(),
                    dateline: util.getTime(),
                    user_id: Config.requestor.id,
                    weibo_id: this.$el.data('weiboId'),
                    avatarUrl: Config.requestor.avatarUrl,
                    nikename: Config.requestor.nikename,
                    isMe: true,
                    isRoot: checkboxes.eq(0).is(":checked"),
                    isForward: checkboxes.eq(1).is(":checked")
                }   
            }
        });


    var PmModel = Backbone.Model.extend({
            url: Config.request.pm
        });
        PmView = Backbone.View.extend({
            initialize: function() {

                this.modal = $('#pm-modal');
                this.detail = $('#pm-detail');
            },

            events: {
                'click .send-pm': 'popModal',
                'click #save-pm': 'send'
            },

            popModal: function() {
                this.modal.modal();
            },

            send: function() {
                if (util.imperfectTextCheck(this.detail)) {
                    var attrs = {
                        content: this.detail.val(),
                        user_id: Config.requestor.id,
                        to_id: Config.user.id
                    },
                    pm = new PmModel(attrs);

                    pm.save();
                    this.modal.modal('hide');
                    this.detail.val('');
                }
            }
        }),
        pmView = new PmView({
            el: $('body')
        });
});
});