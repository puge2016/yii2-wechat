<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="YES" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <script type="text/javascript">
        if(window.__wxjs_is_wkwebview){
            //WKWebview内核
            document.write("<scri"+"pt src='https://res.wx.qq.com/open/js/jweixin-1.2.0.js'></sc"+"ript>");
        }else{
            //UIWebView//内核
            document.write("<scri"+"pt src='https://res.wx.qq.com/open/js/jweixin-1.1.0.js'></sc"+"ript>");
        };
    </script>

    <title>外勤打卡</title>

    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=201705091">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/weui-0.4.2.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/jquery-weui-0.7.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/plugins/photoswipe/photoswipe.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/plugins/photoswipe/default-skin/default-skin.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/outwork.min.css?v=20170509152">


    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion="<?=$jsVersion ?>";
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>

    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/zepto-1.1.6.min.js,/Public/statics/common/wechat/js/core.min.js?v=201705011" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/core/import.min.js,/Public/statics/common/wechat/js/core/importConfigView.min.js?v=201705011" type="text/javascript"></script>
    <!-- -->

    <script type="text/javascript">
        wx.config({
            beta:true,
            debug: false,
            appId: "<?=$signPackage["appId"]; ?>",
            timestamp: "<?=$signPackage["timestamp"]; ?>",
            nonceStr: "<?=$signPackage["nonceStr"]; ?>",
            signature: "<?=$signPackage["signature"][0]; ?>",
            jsApiList: ['hideMenuItems','showMenuItems']});
        wx.ready(function(){
            wx.hideMenuItems({menuList: [
                "menuItem:share:appMessage",
                "menuItem:share:timeline",
                "menuItem:share:qq",
                "menuItem:share:QZone",
                "menuItem:share:weiboApp",
                "menuItem:share:facebook",
                "menuItem:share:QZone"
            ]});

        });
    </script>

    <!--
    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', 'a98236287478f06b']);
            _vds.push(['enableHT',true]);
            (function() {
                var vds = document.createElement('script');
                vds.type='text/javascript';
                vds.async = true;
                vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(vds, s);
            })();
        })();
    </script>
    <script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
    -->
    <link rel="shortcut icon" href="/favicon.ico"/>
</head>
<body>
<div class="main">
    <style type="text/css">
        /*#l-map{height:300px;width:100%;margin-top: 10px;}*/
        html,body{
            background-color: #fff;
        }
    </style>

    <article class="outworklog-wrap" id="outworklog" v-cloak>
        <section class="header">
            <span><em></em>{{Date}}&#12288;星期{{Week}}<i></i></span>
            <p><a href="javascript:;" @click="prevDateFn()"></a><a href="javascript:;"  id="nextDate" @click="nextDateFn()"></a></p>
        </section>
        <section v-show="cont.list==''">
            <p class="textNone">暂无数据</p>
        </section>
        <section class="cont" v-for="item in list">
            <dl>
                <dt>
                <p>{{item.time}}</p>
                <p>{{item.title}}</p>
                <span>{{item.address}}</span>
                <p>{{item.dec}}</p>
                </dt>
                <dd v-if="item.images.length>0">
                    <img :src="item.images[0].msrc" @click="gallery(item.images,0,$event)" />
                    <i>{{item.images.length}}</i>
                </dd>
            </dl>
        </section>
    </article>

    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(function(){
            var merJS = [
                '/Public/statics/common/wechat/plugins/mobiscroll',
                '/Public/statics/common/wechat/js/jquery-weui-0.7',
                '/Public/statics/common/wechat/js/vue',
                '/Public/statics/common/wechat/plugins/photoswipe/photoswipe',
                '/Public/statics/common/wechat/plugins/photoswipe/photoswipe-ui-default'
            ];
//      modulejsVersion = new Date().getTime();
            require.config({
          jsCompress:true,
                jsVersion:modulejsVersion
            })('outWork',merJS.join(','),function(){
                var outVue= new Vue({
                    el: "#outworklog",
                    data:{
                        datetime:'',
                        Date:'',
                        Week:'',
                        prevDatenum:0,
                        nextDatenum:0,
                        cont:[],
                        list : ''
                    },
                    ready:function(){
                        var _this=this;
                        _this.myAjax();

                    },
                    methods:{
                        prevDateFn:function(){
                            var _this=this;
                            _this.datetime=_this.prevDatenum;
                            if(_this.datetime>0){
                                _this.myAjax();
                            }else{
                                $.alert('没有更多了！');
                            }
                        },
                        nextDateFn:function(){
                            var _this=this;
                            _this.datetime=_this.nextDatenum;
                            if(_this.datetime>0){
                                _this.myAjax();
                            }else{
                                $.alert('没有更多了！');
                            }

                        },
                        gallery: function(thumb, index, event) { //相册系统
                            var ary = [];
                            var pswpElement = document.querySelectorAll('.pswp')[0];
                            var loaded = false;
                            $.each(thumb,function(i,e){
                                ary.push({
                                    el: event.target,
                                    msrc: e.msrc,
                                    src: e.src,
                                    w: e.size.split("*")[0],
                                    h: e.size.split("*")[1]
                                });
                            });
                            var options = {
                                getThumbBoundsFn: function(index) {
                                    var thumbnail = ary[index].el;
                                    var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
                                    var rect = thumbnail.getBoundingClientRect();
                                    return {
                                        x: rect.left,
                                        y: rect.top + pageYScroll,
                                        w: rect.width
                                    };
                                },
                                mainClass: 'pswp--minimal--dark',
                                barsSize: {
                                    top: 0,
                                    bottom: 0
                                },
                                history: true,
                                shareEl: false,
                                zoomEl: true,
                                index: index,
                                fullscreenEl: false,
                                bgOpacity: 0.85,
                                tapToToggleControls: false
                            };
                            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, ary, options);
                            gallery.init();

                        },
                        myAjax:function(){
                            var _this=this;
                            console.log(_this.datetime);
                            YI.getAjaxInfo({
                                url: '/attend/check/outwork-log',
                                data: {
                                    'datetime':_this.datetime,
                                    '<?=Yii::$app->getRequest()->csrfParam ?>':'<?=Yii::$app->getRequest()->getCsrfToken() ?>'
                                },
                                method: 'post',
                                tip: false,
                                weui : true,
                                fn: function(data) {
                                    _this.cont=data.data;
                                    console.log(_this.cont);
                                    _this.list = _this.cont.list;
                                    _this.Date=_this.cont.currentDate;
                                    _this.Week=_this.cont.currentWeek;
                                    _this.Date=_this.cont.currentDate;
                                    _this.prevDatenum=_this.cont.prevDate;
                                    _this.nextDatenum=_this.cont.nextDate;
                                },
                                erro: function(data) {

                                }
                            });
                        }
                    }
                })
            });
        });
    </script>
</div>
<div class="clear"></div>
<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>
    </div>
</footer>

</body>
</html>
