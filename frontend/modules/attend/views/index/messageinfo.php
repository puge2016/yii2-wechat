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

    <title>消息</title>
    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=201705091">
    <link rel="stylesheet" href="/Public/statics/common/wechat/plugins/iscrollProbe5Plug/css/iscroll-5/iscroll-probe-5.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/vote/wechat/css/message.css?v=20170509152">

    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/assets/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion = '<?=$jsVersion ?>' ;
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>

    <script src="<?=$cdnUrl ?>static/f=Public/statics/common/wechat/js/zepto-1.1.6.min.js,Public/statics/common/wechat/js/core.min.js?v=201705091" type="text/javascript"></script>
    <script src="<?=$cdnUrl ?>static/f=Public/statics/common/wechat/js/core/import.min.js,Public/statics/common/wechat/js/core/importConfigView.min.js?v=201705091" type="text/javascript"></script>

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
    <section id="messageWrap" data-module="attend" data-url="" class="message-wrap"></section>
    <style>
        .newrecord-nav a.list:nth-child(1) em{
            background-image: url(/Public/statics/attend/wechat/images/home.png);
        }
        .newrecord-nav a:nth-child(3).curr em {
            background-image: url(/Public/statics/attend/wechat/images/record-cur.png);
        }
    </style>
    <nav class="newrecord-nav three" style="z-index:99">
        <a class="list" href="/attend/index/index"><em></em>首页</a>
        <a class="list" href="/attend/index/record"><em></em>考勤记录</a>
        <a class="list curr" href="/attend/index/messageinfo"><em><i></i></em>消息</a>
    </nav>

</div>
<div class="clear"></div>
<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>
    </div>
</footer>
<script src="/Public/statics/common/wechat/js/message.min.js?v=<?=$jsVersion ?>" type="text/javascript"></script>
</body>
</html>
