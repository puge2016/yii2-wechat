<?php
use yii\helpers\Html ;

?>
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
    <?= Html::csrfMetaTags() ?>

    <script type="text/javascript">
        if(window.__wxjs_is_wkwebview){
            //WKWebview内核
            document.write("<scri"+"pt src='https://res.wx.qq.com/open/js/jweixin-1.2.0.js'></sc"+"ript>");
        }else{
            //UIWebView//内核
            document.write("<scri"+"pt src='https://res.wx.qq.com/open/js/jweixin-1.1.0.js'></sc"+"ript>");
        };
    </script>

    <title>打卡</title>

    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=201705091">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/weui-0.4.2.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/jquery-weui-0.7.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/animate.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/yaoresult.min.css?v=20170509152">

    <script type="text/javascript">
    var cdnUrl="<?=$cdnUrl ?>";
    var noneimg="/Public/statics/assets/common/img/none.png";
    var siteServer = '<?=$siteServer ?>';
    var jsVersion = '<?=$jsVersion ?>' ;
    var modulejsVersion="<?=$modulejsVersion ?>";
    </script>


    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/zepto-1.1.6.min.js,/Public/statics/common/wechat/js/core.min.js?v=20170411" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/core/import.min.js,/Public/statics/common/wechat/js/core/importConfigView.min.js?v=20170411" type="text/javascript"></script>

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

    <section class='shake-wrap'>

        <div class="t">
            <span id="currentAddress">定位中····</span>
            <ins id="reflush">刷新        </ins>
        </div>

        <h2>当前时间：<?php echo date('Y年m月d日',time()) ?></h2>
        <h1 class="times" id="timer"></h1>


        <div id="ok" class="hide">
            <div class="mobel-cricle">
                <div class="mobel"></div>
            </div>
        </div>

        <div id="error" class="hide">
            <div class="i animated tada"></div>
            <ul>
                <li>可能原因:</li>
                <li>1：是否打开手机定位</li>
                <li>2：允许获取地理位置</li>
                <li>3：清理微信缓存：我->设置->通用->清理存储空间</li>
            </ul>
        </div>

        <h3>摇一摇 打卡</h3>
        <h4>不能摇一摇点击这里：<a id="checkyao" href="javascript:void(0)">打卡</a></h4>
    </section>

    <div class="tan" id="popDiv" style=" display:none;" onClick="javascript:void(0)">
        <img src="/Public/statics/attend/wechat/images/share_help.png" width="60%" style="margin-left:18%;"/>
    </div>

    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?=$baidu_ak ?>&s=1"></script>

    <script>
        var isClick = 0;
        var type = "";
        var _csrf = "<?=Yii::$app->getRequest()->getCsrfToken() ?>" ;
        var inMsg = "定位中····" ;
        var allowMsg = "您已在考勤范围内";
        var notAllow = "<span class='warning'>您当前不在考勤范围内</span>";
        var notInAllow = "<span class='warning'>管理员已关闭微信签到</span>";

        var isSend = 0 ;
        var load = "加载中";
        var currentDate = "<?php echo date('Y-m-d', time()) ?>";
        var staffId = "<?=$signPackage['userid'] ?>";
        var aid = "100143857";
        var error = "签到失败,再次尝试";
        var timeOut = "<span class='warning'>定位超时</span>";



        var deviceError = "您与当前打卡方式不匹配";
        var yes = "确定";
        var notPoint = "<span class='warning'>定位失败</span>";
        var userCancel= "用户拒绝授权获取地理位置";
        var pointCancel= "位置已过期，请重新定位";
        var SHAKE_THRESHOLD = 800;
        var last_update = 0;
        var isLocation = 0;
        var x = y = z = last_x = last_y = last_z = 0;
        var EARTH_RADIUS = 6378137.0;    //单位M
        var PI = Math.PI;
        var logTime = 0;
        var lat = '';
        var lng = '';
        //var devicestr = '[{"address":"\u5317\u4eac\u5e02\u6d77\u6dc0\u533a\u6c38\u4e30\u8def28","lat":"40.084391","lng":"116.248465","around":500}]';
        var devicestr = '<?=$devicestr ?>';
        //var deviceList = eval("("+devicestr+")");
        var deviceList = JSON.parse(devicestr);
        var serverTime='<?php echo time() ?>';
        //console.info(deviceList);
    </script>

    <script>
        //modulejsVersion = new Date().getTime();
        require.config({
            jsCompress:false,
            jsVersion:modulejsVersion
        })('checkPlug','/Public/statics/common/wechat/js/jquery-weui-0.7,/Public/statics/attend/wechat/js/yao');
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
