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

    <title>外勤打卡</title>
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=20170426"  class="base-css" >
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=2017042652">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/weui-0.4.2.min.css?v=2017042652">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/jquery-weui-0.7.min.css?v=2017042652">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/outwork.css?v=2017042652">

    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion="<?=$jsVersion ?>";
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>

    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/zepto-1.1.6.min.js,/Public/statics/common/wechat/js/core.min.js?v=20170429" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/core/import.min.js,/Public/statics/common/wechat/js/core/importConfigView.min.js?v=20170429" type="text/javascript"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <script type="text/javascript">
        wx.config({
            beta:true,
            debug: false,
            appId: "<?=$signPackage["appId"]; ?>",
            timestamp: "<?=$signPackage["timestamp"]; ?>",
            nonceStr: "<?=$signPackage["nonceStr"]; ?>",
            signature: "<?=$signPackage["signature"][0]; ?>",
            jsApiList: ['chooseImage','uploadImage','previewImage','closeWindow','hideMenuItems','showMenuItems']});
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
    <article class="outwork-wrap">
        <form action="" method="post" id='clockForm'>
            <section class="header">
                <h2>
                <?=$outTime ?>
                    <a href="/attend/check/outwork-log"><span>今日已打卡<i><?=$outWorkTimes ?></i>次</span></a>
                </h2>
            </section>
            <section>
                <div class="map-tit">
                    <h3></h3>
                    <p class="address"></p>
                    <a id="gpsCont" href="#">地点微调</a>
                </div>
                <div id="l-map"></div>
                <textarea name="dec" rows="" cols="4" placeholder="输入说明(不超过120个字)" maxlength="120"></textarea>
                <div class="upload">

                </div>

                <div class='btn'><a href="javascript:;" id="clockBtn" class="weui_btn weui_btn_primary">打卡</a></div>
            </section>

            <input type="hidden" name="lat" value="<?=$lat ?>">
            <input type="hidden" name="lng" value="<?=$lng ?>">
            <input type="hidden" name="point_title" value="<?=$point_title ?>">
            <input type="hidden" name="point_content" value="<?=$point_content ?>">
            <input type="hidden" name="wetype" value="1">
            <input type="hidden" name="<?=Yii::$app->getRequest()->csrfParam ?>" value="<?=Yii::$app->getRequest()->getCsrfToken() ?>">
        </form>
    </article>
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?=$baidu_ak ?>&s=1"></script>

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var newUrl= '/attend/check/wesuccess?date=<?=$date_out ?>&userid=<?=$userid ?>',lat = '<?=$lat ?>',lng = '<?=$lng ?>',point_title = '<?=$point_title ?>',point_content = '<?=$point_content ?>';
        //  modulejsVersion = new Date().getTime();
        require.config({
       jsCompress:false,
            jsVersion:modulejsVersion
        })('apply','/Public/statics/common/wechat/plugins/mobiscroll,/Public/statics/common/wechat/js/jquery-weui-0.7,/Public/statics/attend/wechat/js/outwork');
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
