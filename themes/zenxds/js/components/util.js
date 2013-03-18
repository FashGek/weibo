define(function(require, exports, module) {
	var $ = require('jquery'),

		// 检测文本框值是否为空,为空则产生动画效果
        imperfectTextCheck = function(elem) {
            // elem = $(elem);
            if ($.trim(elem.val()) == '') {
                elem.css("background-color", "#ffff99").fadeOut("fast", function(){
                    elem.fadeIn("fast", function(){
                        elem.css("background-color", "#ffffff");
                    });
                });
                return false;
            } else {
                return true;
            }         
        },
        substitute = function (str, o, regexp) {
            return str.replace(regexp || /\\?\{\{([^{}]+)\}\}/g, function (match, name) {
                if (match.charAt(0) === '\\') {
                    return match.slice(1);
                }
                return (o[name] === undefined) ? '' : o[name];
            });
        },

        getTime = function()
	    {
	     	return +new Date();
	    },

        formatDate = function(format, date) {
            date = date || new Date();
            var o = {};
            var Y = o.Y = date.getFullYear();
            var M = o.M = date.getMonth() + 1;
            var D = o.D = date.getDate();
            var H = o.H = date.getHours();
            var I = o.I = date.getMinutes();
            var S = o.S = date.getSeconds();
            var U = o.U = date.getMilliseconds();
            o.y = Y.toString().substring(2,4);
            o.m = (M < 10) ? ("0"+M) : M;
            o.d = (D < 10) ? ("0"+D) : D;
            o.h = (H < 10) ? ("0"+H) : H;
            o.i = (I < 10) ? ("0"+I) : I;
            o.s = (S < 10) ? ("0"+S) : S;
            o.u = (U < 10) ? ("0"+U) : ((U < 100) ? ("00"+U) : U);
            
            for (var i in o ){
                format = format.replace(i, o[i]);
            }
            return format;
        },

        transDate = function(date) {
            date = date || new Date();
            
            if (typeof date == 'string'){
                date = date.replace('-', '/').replace('-', '/');    // for firefox
                // if (typeof +date.charAt(0) != 'number') {
                date = new Date(date);
                // } else {
                //     date = new Date(Date.parse(date)); 
                // }
            } else {
                date = new Date(date);
            }
            
            var now = new Date(),
                suf = ' H:i';
            var diffMinute = (now - date) / 60000,
                diffDay = ( ( new Date( now.getFullYear(), now.getMonth(), now.getDate() ) ) -
                            ( new Date( date.getFullYear(), date.getMonth(), date.getDate() ) ) ) / (24*60*60000);
            if( diffMinute < 1){
                return "刚刚";
            }
            if( diffMinute < 60){
                return parseInt(diffMinute, 10) + "分钟前";
            }
            if( diffDay == 0 ){
                return formatDate('今天' + suf, date);
            }
            if( diffDay == 1 ){
                return formatDate("昨天" + suf, date);
            }
            if( diffDay < 30 ){
                return formatDate(diffDay + "天前" + suf, date);
            }
            if( now.getFullYear() ==  date.getFullYear()){
                return formatDate('M月D日' + suf, date);
            }
            return formatDate('Y年M月D日' + suf, date);
        },

        /* 
         * 获取文本框里所选文字的开始和结束位置
         * @param id {String} 文本框的id
         * return {} startPosition and endPosition
         * endPosition其实是结束位置后面一位
         * */
        getPositions = function( id ) {     
            var startPosition = endPosition = 0,
                element = document.getElementById(id);
            
            if ( document.selection ) {
                // for Internet Explorer
                // document.selection代表当前选中区
                var range = document.selection.createRange(),       // 要对选中去操作要先创建一个范围对象
                    dRange = range.duplicate();                     // 再克隆一个range

                // 将原始文本的值与所选文本比较得到位置
                dRange.moveToElementText(element);
                dRange.setEndPoint("EndToEnd", range);
                startPosition = dRange.text.length - range.text.length;
                endPosition = startPosition + range.text.length;        // 开始位置加上所选文本长度
            } else if ( window.getSelection ) {
                //For Firefox, Chrome, Safari etc
                startPosition = element.selectionStart;
                endPosition = element.selectionEnd;
            }

            return {
                'start': startPosition,
                'end': endPosition
            };
        },

        getCursortPosition = function(ctrl) {//获取光标位置函数
            var CaretPos = 0;   // IE Support
            if (document.selection) {
                ctrl.focus ();
                var Sel = document.selection.createRange ();
                Sel.moveStart ('character', -ctrl.value.length);
                CaretPos = Sel.text.length;
            }
            // Firefox support
            else if (ctrl.selectionStart || ctrl.selectionStart == '0')
                CaretPos = ctrl.selectionStart;
            return (CaretPos);
        },
        setCursorPosition = function(ctrl, pos){//设置光标位置函数
            if(ctrl.setSelectionRange)
            {
                ctrl.focus();
                ctrl.setSelectionRange(pos,pos);
            }
            else if (ctrl.createTextRange) {
                var range = ctrl.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        },

        textSelect = function(o, start, end){
            //o是当前对象，例如文本域对象
            //start是起始位置，end是终点位置
            var length = o.value.length,
                start = parseInt(start, 10) || 0, 
                end = parseInt(end, 10) || length;
            
            if(length){
                //如果值超过长度，则就是当前对象值的长度
                if(start > length){
                    start = length;  
                }
                if(end > length){
                    end = length;  
                }
                //如果为负值，则与长度值相加
                if(start < 0){
                    start = length + start;
                }
                if(end < 0){
                    end = length + end;  
                }
                if(o.createTextRange){//IE浏览器
                    var range = o.createTextRange();         
                    range.moveStart("character", -length);              
                    range.moveEnd("character", -length);
                    range.moveStart("character", start);
                    range.moveEnd("character", end);
                    range.select();
                }else{
                    o.setSelectionRange(start, end);
                    o.focus();
                }
            }
        };

    $.extend(exports, {
        imperfectTextCheck: imperfectTextCheck,
        substitute: substitute,
        getTime: getTime,
        formatDate: formatDate,
        transDate: transDate,
        getCursortPosition: getCursortPosition,
        setCursorPosition: setCursorPosition,
        textSelect: textSelect
    });


});