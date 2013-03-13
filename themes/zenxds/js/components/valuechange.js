/*
 * author: zenxds
 * email: zenxds@gmail.com
 * a jquery implement of kissy valuechange
 */
define(function(require, exports, module) {

	var $ = require('jquery');

	var KEY 		= 'valuechange',
		HISTORY_KEY = KEY + '/history',	// 输入新值后原来的value值
		POLL_KEY    = KEY + '/poll',	// 检测值的变化
		PROCESS_KEY = KEY + '/process', // 获取元素的处理函数
		interval    = 50,	// 轮询间隔

		// process(preVal, newVal)
		valueChange = function(selector, process, context) {
			elem = $(selector, context);

			var target = elem[0],
				nodeName = target.nodeName.toLowerCase();

			if (nodeName == 'input' || nodeName == 'textarea') {
                monitor(elem);
                $.data(target, PROCESS_KEY, process);
            }
		};

	function clearPollTimer(target) {
        if ($(target).data(POLL_KEY)) {
            var poll = $.data(target, POLL_KEY);
            clearTimeout(poll);
            $.removeData(target, POLL_KEY);
        }
    }

    function stopPoll(target) {
        $.removeData(target, HISTORY_KEY);
        clearPollTimer(target);
    }

    function stopPollHandler(ev) {
        clearPollTimer(ev.target);
    }

    function checkChange(target) {
        var v = target.value,
            h = $.data(target, HISTORY_KEY),
            process = $.data(target, PROCESS_KEY);
        if (v !== h) {
            process.call($(target), h, v);
            $.data(target, HISTORY_KEY, v);
        }
    }

    function startPoll(target) {
        if ($(target).data(POLL_KEY)) {
            return;
        }
        $.data(target, POLL_KEY, setTimeout(function () {
            checkChange(target);
            $.data(target, POLL_KEY, setTimeout(arguments.callee, interval));
        }, interval));
    }

    function startPollHandler(ev) {
        var target = ev.target;
        // when focus ,record its current value immediately
        if (ev.type == 'focus') {
            $.data(target, HISTORY_KEY, target.value);
        }
        startPoll(target);
    }

    function webkitSpeechChangeHandler(e) {
        checkChange(e.target);
    }

    function monitor(elem) {
        unmonitored(elem);
        elem.on('blur', stopPollHandler);
        elem.on('webkitspeechchange', webkitSpeechChangeHandler);
        elem.on('mousedown keyup keydown focus input properchange', startPollHandler);
    }

    function unmonitored(elem) {
        stopPoll(elem[0]);
        elem.off('blur', stopPollHandler);
        elem.off('webkitspeechchange', webkitSpeechChangeHandler);
        elem.off('mousedown keyup keydown focus', startPollHandler);
    }

    $.valueChange = valueChange;
});