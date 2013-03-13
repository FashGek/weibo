define(function(require, exports, module) {
	var $ = require('jquery'),
		Backbone = require('backbone'),
        util = require('util'),
        Config = window.Config;

    var PmModel = Backbone.Model.extend({
            initialize: function() {
                this.setHumanDateline();
    
                if (this.get('user_id') == Config.requestor.id) {
                    this.set('isMe', true);
                }
            },

            setHumanDateline: function() {
                this.set('humanDateline', util.transDate(this.get('dateline')));
            }
        }),
        PmCollection = Backbone.Collection.extend({
            url: Config.request.pm,
            model: PmModel,
            comparator: function(item) {
                return item.get('dateline');
            },
            initialize: function() {

            }
        });

	return {
		model: PmModel,
		collection: PmCollection
	}
});