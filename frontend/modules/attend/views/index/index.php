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

    <title>考勤和假期管理</title>
    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=201705091">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/updateyang.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/animate.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/weui-0.4.2.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/plugins/swiper/swiper.3.3.7.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/jquery-weui-0.7.min.css?v=20170509152">


    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/assets/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion = '<?=$jsVersion ?>' ;
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>



    <script src="<?=$siteServer ?>static/f=Public/statics/common/wechat/js/zepto-1.1.6.min.js,Public/statics/common/wechat/js/core.min.js?v=20170411" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=Public/statics/common/wechat/js/core/import.min.js,Public/statics/common/wechat/js/core/importConfigView.min.js?v=20170411" type="text/javascript"></script>


    <!-- -->

    <script type="text/javascript">
        wx.config({
            beta:true,
            debug: false,
            appId: "<?=$signPackage["appId"];?>",
            timestamp: "<?=$signPackage["timestamp"];?>",
            nonceStr: "<?=$signPackage["nonceStr"];?>",
            signature: "<?=$signPackage["signature"][0];?>",
            jsApiList: ['scanQRCode','hideMenuItems','showMenuItems']});
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
        .tip{
            background-color: rgba(0,0,0,.5);
            width: 100%;
            left: 0;
            top: 0;
            position: fixed;
            z-index: 9999;
        }
        .tip img{
            display: block;
            width: 100%;
            position: absolute;
            left: 0;
            top: 0;
        }
        .kt-router > a{
            display: block;
            height: 45px;
            color: #3A3A3A;
            text-indent: 10px;
            background-color: #FFFCD1;
            font: normal 16px/45px "微软雅黑";
        }
        .kt-router > a > i{
            color: #FC7044;
            text-decoration: underline;
        }
        .bindroute{
            font-style: normal;
            padding: 5px 10px 0 10px;
            height: 25px;
            line-height: 25px;
            color: #FAF46E;
            font-family: "微软雅黑";
            margin-bottom: -7px;
            font-size: 14px;
            word-break: break-all;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
        .attend-wrap section.t{
            height: auto;
            padding-bottom: 23px;
        }
        .swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet{
            margin: 0 2px;
        }
        .swiper-container-horizontal>.swiper-pagination-bullets{
            bottom: -2px;
        }
        .swiper-container-horizontal>.swiper-pagination-bullets span{
            background-color: #62C693;
            height: 4px;
            width: 38px;
            border-radius: 5px;
        }
        .swiper-pagination-bullet{
            opacity: 1;
        }
        .swiper-pagination-bullets span.swiper-pagination-bullet-active{
            background: #C7FFE2;
        }
        [v-cloak]{
            display: none;
        }
        .swiper-container{
            background-color: #4db17f;
        }

    </style>
    <article class="attend-wrap" id="attendWrap" v-cloak>


        <!--<div class="check" style="padding: 20px 0 0 0; display: none;">
            <div class="range" style="padding: 0 10px 10px 10px; height: 30px; box-sizing: border-box;"><span class="checkStatus">定位中...</span><span class="right-span hide" id="freshBtn">刷新</span></div>
            <div style="padding: 5px 0; display: none;" id="showBtn"><a href="javascript:;" class="check checkBtn" onclick="return false;">点击快速打卡</a></div>
        </div>

        <div style="display: none;font-size:16px;color:#fff;text-align:center;background: #4cb17e none repeat scroll 0 0;padding: 15px 0; box-sizing: border-box; ">
           当前时间：<span class="time">11:08:09</span>
        </div>-->

        <a href="javascript:;" data-href="/attend/device?_=1491621766&model=t1&sn=641610092512&code=1afe12ec14919b995a080ce0e7f8e49f&error=0" class="sm-kqj animated wobble" id="smKqj"></a>



        <div class="swiper-container" id="indexContainer">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <!--考勤打卡begin-->
                    <section class="t">
                        <p id="useRouter" class="bindroute" style="display: none;">正在使用<span id="routerName"></span></p>
                        <div>当前时间：<span class="timer">{{time}}</span></div>
                        <a data-href="/attend/check/yao?v=<?=time() ?>" class="showCheck o" href="javascript:void(0)">打卡</a>
                    </section>
                    <!--考勤打卡end-->
                </div>
                <div class="swiper-slide">
                    <!--外勤打卡begin-->
                    <section class="t">
                        <!--<p id="useRouter" class="bindroute" style="display: none;">正在使用<span id="routerName"></span></p-->
                        <div>当前时间：<span class="timer">{{time}}</span></div>
                        <a data-href="/attend/check/outwork?v=<?=time() ?>" class="showCheck" href="javascript:void(0)">外勤打卡</a>
                    </section>
                    <!--外勤打卡end-->
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>



        <div class="kt-router hide" id="ktRouter">
            <a href="/ktroutewebserver/bind/index">公司已启用路由器打卡，<i>立即绑定</i></a>
        </div>

        <div class="resource-space">
        </div>


        <ul class="area-list" id="indexUl">
            <li class="a"><a href="#"><em></em><p>异常说明</p></a></li>
            <li class="b"><a href="#"><em></em><p>加班申请</p></a></li>
            <li class="c"><a href="#"><em></em><p>调休申请</p></a></li>
            <li class="d"><a href="#"><em></em><p>请假申请</p></a></li>

            <li class="l"><a href="#"><em></em><p>我的假期</p></a></li>

            <!--<li class="g"><a href="/attend/index/record"><em></em><p>考勤记录</p></a></li>-->
            <li class="h"><a href="#"><em></em><p>我的申请</p></a></li>
            <li class="i"><a href="#"><em></em><p>待我审批</p></a><i data-num="0"></i></li>

            <li class="m"><a href="#"><em></em><p>我的排班</p></a></li>
            <li class="n" style="display: none;"><a href=""><em></em><p>团队报表</p></a></li>

            <li class="k"><a href="/attend/index/status"><em></em><p>在岗查询</p></a></li>


        </ul>


    </article>



    <!--script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=62af8a7b58ee9e3231fbce6a0f8ee53b"></script-->
    <script>
        var $deviceList = [{"type":0}];
        var serverTime = <?=$time ?>;
        var serverDate = "<?=$date_out ?>";
        var userId = '<?=$userid ?>';
        var aid = 100143857;
        var isRoute = 0;
        var macStr = '';
        var routeMac ="";
        var mac ="";
        var routeName = "";
        var $routerMacList = [];
        var $deviceArr = [0,1,7];
        var $isOutWork = 21920;
    </script>

    <script>

        $(function(){
            var merJS = [
                '/Public/statics/common/wechat/js/vue',
                '/Public/statics/common/wechat/js/weui',
                '/Public/statics/common/wechat/js/jquery-weui-0.7',
                '/Public/statics/common/wechat/plugins/swiper/swiper.3.3.7',
                '/Public/statics/attend/wechat/js/attendIndex'
            ];
//        modulejsVersion = new Date().getTime();
            require.config({
//            jsCompress:false,
                jsVersion:modulejsVersion
            })('newIndex',merJS.join(','),function(){

                var paginationClickable = 21920;
                var swiper = new Swiper('#indexContainer', {
                    pagination: '.swiper-pagination',
                    paginationClickable: paginationClickable ? true :false
                });

                $('#smKqj').on('click',function(e){
                    e.stopPropagation();
                    wx.scanQRCode({
                        needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                        scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                        success: function (res) {
                            var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                        }
                    });
                });

                var tip=function(){
                    var tmp1='<div class="tip"><img src="/statics/attend/wechat/images/images/tip.png" /></div>'
                    var $icon=$("li.o");
                    var pos=$icon.position();
                    $("body").append(tmp1);
                    $(".tip img").css("top",pos.top-4);
                    $(".tip").css("height", $(window).height()).on("click",function(){
                        $(".tip").remove();
                    });

                    var i = 0,
                            timer = setInterval(function(){
                                if($(window).scrollTop()!=0){
                                    window.scrollTo(0,0);
                                }else{
                                    i++;
                                    if(i>5){
                                        clearInterval(timer);
                                    };
                                };
                            },200);


                    $(".tip").on("touchmove",function(){
                        $(".tip").remove();
                        clearInterval(timer);
                    })
                };
                //tip();

                $('.showCheck').on('click',function () {
                    var $this=$(this);
                    $.ajax({
                        url:"/attend/check/is-early",
                        dataType:'text',
                        cache : false,
                        success:function(data) {
                            console.log($this.attr('data-href'));
                            if(data==1){
                                var title = '未到下班时间',
                                        content = '坚持打卡会被记为早退哦';
                                $.modal({
                                    title: title,
                                    text: content,
                                    buttons: [
                                        { text: "坚持打卡",className: "default",onClick: function(){
                                            location.href = $this.attr('data-href');
                                        } },
                                        { text: "暂不打卡"}
                                    ]
                                });
                            }else{
                                location.href = $this.attr('data-href');
                            }

                        },
                        error:function(res){

                        },
                        timeout:20000
                    });
                    return false;

                });

                var $ktRouter = $('#ktRouter');


                var getRouteMac = function(){
                    $.ajax({
                        url:"//wifi.bangongyi.com/cgi-bin/luci/guest/mac?v="+new Date().getTime(),
                        dataType:'text',
                        cache : false,
                        success:function(result) {
                            verfityRoute(result);
                        },
                        error:function(res){

                            $('.showCheck.o').attr('data-href',$('.showCheck.o').attr('data-href')+'&mac=1');
                            //                    $.modal({
                            //                        title: "无法使用路由打卡",
                            //                        text: "当前连接网络非指定的路由器网络，请在手机网络设置中切换网络。如有疑问，请联系管理员",
                            //                        buttons: [
                            //                            { text: "我知道了"}
                            //                        ]
                            //                    });
                        },
                        timeout:20000
                    });
                };
                if(isRoute) getRouteMac();

                var verfityRoute = function(result){
                    // result = "6UKWi/akuU2NFdn/zGO6T1alMC2ZyBlpTTsGQkA4NfsWEzxJjONw/npAPrhclI6XBrJulRYqTlsv1rjN6wUdSMLGFqCdYcUIdsy1cAAn4ExIQZq/K1nht5J0RJtTLZ1YS/Rpno7g1nGNt8sZZhpsKoh4u0gJ+PyHBd+E81ANE94=";
                    $.ajax({
                        url: '/ktroutewebserver/bind/index',
                        data:{
                            mac:result,
                            bind:0
                        },
                        type: 'post',
                        dataType: 'json',
                        success : function(data){
                            routeMac = data.data[0].routermac;
                            if($.inArray(routeMac, $routerMacList)!=-1){
                                if(data.errno=='3'){
                                    mac = data.data[0].mac;
                                    $('#routerName').html(data.data[0].wifi);
                                    $('#useRouter').show();
                                    $('.showCheck.o').attr('data-href',$('.showCheck.o').attr('data-href')+'&mac='+mac+'&routemac='+routeMac+'&v='+new Date().getTime());
                                }else if(data.errno=='4'){
                                    $ktRouter.show();
                                }else if(data.errno=='1'){
                                    $.modal({
                                        title: "微信账号与打卡设备不匹配",
                                        text: "当前设备mac地址（"+data.data[0].mac+"）已被其他帐号绑定，如需绑定，请先联系管理员解绑当前设备",
                                        buttons: [
                                            { text: "我知道了"}
                                        ]
                                    });
                                }else if(data.errno=='2'){
                                    $.modal({
                                        title: "微信账号与打卡设备不匹配",
                                        text: "如需继续使用，请联系管理员解绑当前帐号",
                                        buttons: [
                                            { text: "我知道了"}
                                        ]
                                    });
                                }else if(data.errno=='5'){
                                    $.modal({
                                        title: "微信账号与打卡设备不匹配",
                                        text: "如需解绑，请联系管理员",
                                        buttons: [
                                            { text: "我知道了"}
                                        ]
                                    });
                                }
                            }
                        }
                    });
                };


            });
        });

    </script>

    <nav class="newrecord-nav" id="newRecordNav" style="z-index:99">
        <a class="" href="/attend/index/index"><em></em>首页</a>
        <a href="/attend/index/record"><em></em>考勤记录</a>
        <a href="/attend/index/messageinfo"><em id="msgParent"><i id="msgNum">0</i></em>消息</a>
    </nav>


    <style type="text/css">
        body{
            padding-bottom: 60px!important;
        }
    </style>

    <script type="text/javascript">
        (function(){
            var count = parseInt('0'),
                    msgParent = document.getElementById('msgParent'),
                    msgNum = document.getElementById('msgNum');
            if(count>0){
                msgNum.style.display = 'block';
            };
            msgParent.onclick = function(){
                msgNum.style.display = "none";
            };
            //底部导航按钮显示 根据页面的地址判断显示
            var currentUrl = location.href;
            var currentIndexBotton = 100;
            var newRecordNav = document.getElementById('newRecordNav');
            var as = newRecordNav.getElementsByTagName('a');
            //我的选中

            //console.info(currentUrl.indexOf("/index/index") >-1);
            if(currentUrl.indexOf("/index/index") >-1 ){
                currentIndexBotton = 1;
            }else if(currentUrl.indexOf("/index/message") >-1 ){
                currentIndexBotton = 3;
            }else if(currentUrl.indexOf("/index/record")  >-1 ){
                currentIndexBotton = 2;
            };
            // $(".newrecord-nav a").eq(currentIndexBotton-1).addClass('curr');
            function addClass(elem, cls){
                if(!hasClass(elem, cls)){
                    elem.className += ' ' + cls;
                }
            };

            function hasClass(elem, cls){
                cls = cls || '';
                if(cls.replace(/\s/g, '').length == 0) return false;
                return new RegExp(' ' + cls + ' ').test(' ' + elem.className + ' ');
            };
            if(currentIndexBotton<=3){
                addClass(as[currentIndexBotton-1],'curr');
            };


        })();
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
