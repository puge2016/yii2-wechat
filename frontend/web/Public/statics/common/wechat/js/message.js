(function($) {


    $('body').append('<style>' +
        'body{background: #fff;}' +
        'html,body{' +
        'width: 100%;' +
        'height: 100%;' +
        '}' +
        'body{' +
            'background:#EBEBEB;' +
        '}' +
        '#wrapper{' +
        'left: 10px;' +
        'top: 10px;' +
        'bottom: 70px;' +
        'right: 10px;' +
        'width: -moz-calc(100% - 20px);' +
        'width: -webkit-calc(100% - 20px);' +
        'width: calc(100% - 20px);' +
        'box-sizing: border-box;' +
        '}' +
        '.approve-iscroll-content ul{' +
        'position: relative;' +
        'background:#EBEBEB;' +
        '}' +
        '#pullDown, #pullUp {' +
        'border-bottom:0 none;' +
        'border-top:0 none;' +
        'background:#EBEBEB;' +
        '}' +
        '.message-wrap,#scroller,#wrapper{' +
            'background:#EBEBEB;' +
        '}' +
        '</style>');

    //modulejsVersion = new Date().getTime();
    var merJS = [
        '/Public/statics/common/wechat/plugins/iscrollProbe5Plug/js/iscroll-probe-5',
        '/Public/statics/common/wechat/plugins/iscrollProbe5Plug/js/iscroll-probe-5Plug',
    ];

    require.config({
        //jsCompress: false,
        jsVersion: modulejsVersion
    })('approveIscroll', merJS.join(','), function() {
        var $messageWrap = $('#messageWrap'),
            $wrap = $('<div id="wrapper" class="approveiscroll-content message-wrap"><div id="scroller"><div id="pullDown"><span class="pullDownIcon"></span><span class="pullDownLabel">上拉刷新</span></div><ul></ul><div id="pullUp"><span class="pullUpIcon"></span><span class="pullUpLabel">加载更多</span></div></div></div>')
        $messageWrap.append($wrap);


        var $messageList = $('#wrapper'),
            $scrollerContent = $messageList.find('ul'),
            url = $.trim($messageWrap.data('url')),
            _a = 0,
            message = {
                init: function() {
                    this.iScroll();
                    this.ajaxInfo('append');
                },
                firstPage: 1,
                module: $.trim($messageWrap.data('module')),
                ajaxInfo: function(insertType) {
                    //console.info(this.page);
                    //console.info(insertType);

                    var _this = this;
                    YI.getAjaxInfo({
                        url: url ? url : "/wei/index/messageget",
                        data: {
                            module: this.module,
                            page: this.firstPage
                        },
                        isLoading: false,
                        fn: function(data) {

                            if (!data.data) {
                                data.data = [];
                            };


                            var data = data.data,
                                html = '';

                            //console.info(data);

                            $.each(data, function(i, e) {
                                html += '<li>';
                                $.each(e, function(j, f) {
                                    
                                    if (j == 0) html += '<div class="date"><span>' + f.time + '</span></div>';
                                    if (f.msg_type == 'news') {
                                        html += '<div class="articl identical">';
                                        html += '<a href="' + f.content['articles'][0].url + '">';
                                        html += '<div class="cont">';
                                        html += '<dl>';
                                        html += '<dt>' + f.content['articles'][0].title + '</dt>';
                                        html += '<dd><span>' + f.time + '</span></dd>';
                                        html += '<dd>';
                                        if( f.content['articles'][0].picurl && f.content['articles'][0].picurl.substring(8,10) != 'mm'){
                                            html += '<img src="' + f.content['articles'][0].picurl + '" />';
                                        };
                                        html += '<p>' + f.content['articles'][0].description + '</p>';
                                        html += '</dd>';
                                        html += '</dl>';
                                        html += '<p class="open">查看全文</p>';
                                        html += '</div>';
                                        html += '</a>';
                                        html += '</div>';
                                    } else if (f.msg_type == 'text') {
                                        html += '<div class="text identical">';
                                        html += '<img class="appimg" src="' + f.content.avatar + '" />';
                                        html += '<div class="cont">';
                                        html += '<em></em>';
                                        html += '<p>' + f.content.content + '</p>';
                                        html += '</div>';
                                        html += '</div>';
                                    };
                                    
                                });
                                html += '</li>';
                                
                            });

                            //console.info(html)

                            if (insertType == 'before') {
                                var $li = $scrollerContent.find('li').eq(0);
                                $li[insertType](html);
                            } else {
                                $scrollerContent[insertType](html)
                            };

                            if (myScrollPlug) {
                                if (_this.firstPage > 1) {
                                    setTimeout(function() {
                                        myScrollPlug.scrollToElement($li[0], 0, null, null, true);
                                    }, 50);
                                };
                                myScrollPlug.refresh();
                            };
                        }
                    });
                },


                iScroll: function() {

                    var _this = this;

                    function iScrollLoad(type, el, slideDownCallBack, slideUpCallBack) {
                        var myScroll,
                            upIcon = $(el).find(".up-icon"),
                            downIcon = $(el).find(".down-icon");
                        //增加高度计算
                        var outerHeight = $(el).height(),
                            innerHeight = $(el).find('.scroller-content').children('ul').height(),
                            pullUp = $(el).find(".scroller-pullUp");


                        if (outerHeight > innerHeight) {
                            $(el).find('.scroller-content').css('min-height', outerHeight);
                        };



                        _this['massageIscroll' + type] = myScroll = new IScroll(el, {
                            probeType: 3,
                            mouseWheel: true,
                            click: true
                        });



                        myScroll.on("scroll", function() {
                            var y = this.y,
                                maxY = this.maxScrollY - y,
                                downHasClass = downIcon.hasClass("reverse_icon"),
                                upHasClass = upIcon.hasClass("reverse_icon");

                            if (y >= 40) {
                                !downHasClass && downIcon.addClass("reverse_icon");
                                return "";
                            } else if (y < 40 && y > 0) {
                                downHasClass && downIcon.removeClass("reverse_icon");
                                return "";
                            }

                            if (maxY >= 40) {
                                !upHasClass && upIcon.addClass("reverse_icon");
                                return "";
                            } else if (maxY < 40 && maxY >= 0) {
                                upHasClass && upIcon.removeClass("reverse_icon");
                                return "";
                            }
                        });

                        myScroll.on("slideDown", function() {
                            if (this.y > 40) {
                                //console.info("slideDown,ajax begin");
                                if (slideDownCallBack) {
                                    slideDownCallBack();
                                };
                                //console.info(this);
                                this.refresh();
                                upIcon.removeClass("reverse_icon")
                            }
                        });

                        myScroll.on("slideUp", function() {
                            if (this.maxScrollY - this.y > 40) {
                                //console.info("slideUp,ajax begin");
                                if (slideUpCallBack) {
                                    slideUpCallBack();
                                };
                                //console.info(this);
                                this.refresh();
                                upIcon.removeClass("reverse_icon")
                            }
                        });
                    };


                    window.isScrollInit();
                    window.pullDownAction = function() { //向下滑动回调函数  加载历史数据
                        _a = myScrollPlug
                        _this.firstPage++;
                        message.ajaxInfo('before');
                    };

                    window.pullUpAction = function() { //向上滑动回调函数  加载第一页数据
                        //$scrollerContent.html('<li class="loading-status">数据加载中，请稍后...</li>');
                        _this.firstPage = 1;
                        message.ajaxInfo('html');
                    };


                    //由于是ajax加载数据，所以myScrollPlug.y的值赋予一个较大的值作为参考
                    //myScrollPlug.y = -myScrollPlug.maxScrollY;  //标准

                    myScrollPlug.y = -99999999; //初始化的时候滚到最低部



                },


                bindEvent: function() {
                    /*$scrollerContent.delegate('li', 'click', function() {
                        $(this).css('background', '#f00');
                    });*/
                }
            };

        $(function(){
            message.init();
        });

    });

})(Zepto);
