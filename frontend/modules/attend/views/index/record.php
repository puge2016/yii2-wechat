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

    <title>考勤记录</title>
    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=20170527">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/weui-0.4.2.min.css?v=2017052752">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/jquery-weui-0.7.min.css?v=2017052752">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/record.min.css?v=2017052752">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/animate.min.css?v=2017052752">


    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/assets/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion = '<?=$jsVersion ?>' ;
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>

    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/zepto-1.1.6.min.js,/Public/statics/common/wechat/js/core.min.js?v=20170411" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/core/import.min.js,/Public/statics/common/wechat/js/core/importConfigView.min.js?v=20170411" type="text/javascript"></script>

    <!-- -->
    <script type="text/javascript">
        wx.config({
            beta:true,
            debug: false,
            appId: "<?=$signPackage["appId"]; ?>",
            timestamp: "<?=$signPackage["timestamp"]; ?>",
            nonceStr: "<?=$signPackage["nonceStr"]; ?>",
            signature: "<?=$signPackage["signature"][0]; ?>",
            jsApiList: ['getLocation','hideMenuItems','showMenuItems']});
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
        body{ font-size: 14px;}
    </style>

    <div class="resource-space">
    </div>

    <article class="record-wrap">
        <h2 id="getMonthData"><em></em><i></i><span></span></h2>
        <ul class="nav">
            <li>日</li>
            <li>一</li>
            <li>二</li>
            <li>三</li>
            <li>四</li>
            <li>五</li>
            <li>六</li>
        </ul>

        <ul class="date-list clearfix" id="dateList">
            <!--li><i></i></li>
            <li><i></i></li>
            <li><i></i></li>
            <li class="cur"><i><em class="normal">01<b></b></em></i></li>
            <li><i><em class="cd">02<b></b></em></i></li>
            <li><i><em class="jb">04<b></b></em></i></li>
            <li><i><em class="cc">07<b></b></em></i></li>
            <li><i><em class="today">10<b></b></em></i></li>
            <li><i><em>31<b></b></em></i></li-->
        </ul>


        <section class="user-records" id="userRecords">
            <dl class="clearfix">
                <dt><img src="" /></dt>
                <dd>
                    <div class="a">
                        <i style="display: block;float:left;"></i><ins style="display: block;float:left;"></ins>
                        <span style="display: block;float:left;" onclick="location.href='/check/apply/messageset'">设置提醒</span>
                    </div>
                    <div class="b"><i></i></div>
                </dd>
            </dl>
        </section>


        <dl class="record-dl" style="display: none;">
            <dt>早班 09:00—12:00<a href="#" class="pb">查看我的排班</a></dt>
        </dl>

        <dl class="record-dl" id="recordTimes" style="display: none;">
            <!-- <dt>签到：<span>00:00</span>，签退：<span>00:00</span></dt> -->
        </dl>
        <dl class="record-dl" id="recordDl"></dl>


        <ul class="record-ul clearfix" id="userStatusRender">
            <li><em></em>正常:<span>0</span>天</li>
            <li><em></em>迟到:<span>0</span>次</li>
            <li><em></em>早退:<span>0</span>次</li>
            <li><em></em>旷工:<span>0</span>天</li>
            <li><em></em>请假:<span>0</span>次</li>
            <li><em></em>调休:<span>0</span>次</li>
            <li><em></em>加班:<span>0</span>次</li>
            <li><em></em>出差:<span>0</span>次</li>
            <li><em></em>外出:<span>0</span>次</li>
            <li><em></em>漏刷:<span>0</span>次</li>
        </ul>

    </article>


    <!--section class="fixed-nav" id="fixedNav">
        <ul>
            <li>签到签退
                <div class="fade-in-up">
                    <a href="#">签到</a>
                    <a href="#">签退</a>
                    <i></i>
                </div>
            </li>
            <li>考勤申请
                <div class="fade-in-up">
                    <a href="#">待我审批</a>
                    <a href="#">我的申请</a>
                    <a href="#">提交申请</a>
                    <i></i>
                </div>
            </li>
            <li>考勤记录</li>
        </ul>
    </section>
    <section class="newrecord-nav clearfix hide" id="newRecordNav">
        <a href="#"><em></em>首页</a>
        <a href="#"><em></em>消息</a>
        <a href="#"><em></em>我的</a>
    </section-->

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
    <script type="text/javascript">
        var curDate = '';
        if(curDate){
            curDate = $.parseJSON(curDate);
        }
        var CHECK_LOG_OUTSIDE = 2;
        var staff_id = '<?=$signPackage['userid'] ?>';
        var _csrf = "<?=Yii::$app->getRequest()->getCsrfToken() ?>" ;

        //    modulejsVersion = new Date().getTime();
        require.config({
//        jsCompress:false,
            jsVersion:modulejsVersion
        })('recordpage','/Public/statics/common/wechat/js/jquery-weui-0.7,/Public/statics/attend/wechat/js/record');

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
