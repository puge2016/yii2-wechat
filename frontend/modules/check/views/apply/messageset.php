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

    <title>消息设置</title>
    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/mui.min.css?v=20170527">
    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/yi.css?v=20170527">

    <script type="text/javascript">
    var cdnUrl="<?=$cdnUrl ?>";
    var noneimg="/Public/assets/common/img/none.png";
    var siteServer = '<?=$siteServer ?>';
    var jsVersion="<?=$jsVersion ?>";
    var modulejsVersion="<?=$modulejsVersion ?>";
    var js_files = [cdnUrl+"Public/statics/common/wechat/js/zepto.min.js",cdnUrl+"Public/statics/common/wechat/plugins/mui.min.js"];
    </script>

    <script type="text/javascript" src="/Public/statics/common/wechat/plugins/store.min.js?v=20170527"></script>
    <script type="text/javascript" src="/Public/statics/common/wechat/plugins/xmlhttp.js?v=20170527"></script>
    <script type="text/javascript" src="/Public/statics/common/wechat/plugins/yi.js?v=20170527"></script>

    <script src="<?=$cdnUrl ?>static/f=/Public/statics/common/wechat/js/core/import.min.js,/Public/statics/common/wechat/js/core/importConfigView.min.js?v=201705091" type="text/javascript"></script>

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
    <script src="/Public/statics/common/wechat/plugins/app.js?v=201705275" type="text/javascript"></script>

    <link rel="shortcut icon" href="/favicon.ico"/>
</head>
<body>

<div class="main">

    <style>
        .mui-btn-primary{ background:#00b8a0 ; border-color:#00b8a0;}
        .mui-btn-danger{ background:#d2d2d2 ;border-color:#d2d2d2;}
        .mui-checkbox input[type="checkbox"]:checked::before, .mui-radio input[type="radio"]:checked::before{ color:#00b8a0;}
        .mui-input-row.mui-radio{ width:25%; float:left;}
        .mui-input-row{ clear:none;}
        .xuan_tit{float:left; margin:0 5% 0 15px; line-height:250%; width:35%;}
        .xuan_body{ margin-top:5%; width:100%; float:left;}
        .mui-card{ margin-top:30%;}
    </style>

    <form action="/check/apply/reminder" method="post">
        <div class="mui-card">
            <div class="xuan_body">
                <span class="xuan_tit">早提醒：</span>

                <div class="mui-input-row mui-radio">
                    <label>是</label>
                    <input name="set[type0]" type="radio" <?=$remind_set['type0'] ? 'checked' : '' ?> value="1">
                </div>

                <div class="mui-input-row mui-radio">
                    <label>否</label>
                    <input name="set[type0]" type="radio" <?=$remind_set['type0'] ? '' : 'checked' ?>  value="0">
                </div>
            </div>
            <div class="xuan_body">
                <span class="xuan_tit">晚提醒：</span>

                <div class="mui-input-row mui-radio">
                    <label>是</label>
                    <input name="set[type1]" type="radio" <?=$remind_set['type1'] ? 'checked' : '' ?>  value="1">
                </div>

                <div class="mui-input-row mui-radio">
                    <label>否</label>
                    <input name="set[type1]" type="radio" <?=$remind_set['type1'] ? '' : 'checked' ?>  value="0">
                </div>
            </div>
            <div class="xuan_body">
                <span class="xuan_tit">迟到提醒：</span>

                <div class="mui-input-row mui-radio">
                    <label>是</label>
                    <input name="set[type2]" type="radio" <?=$remind_set['type2'] ? 'checked' : '' ?> value="1">
                </div>

                <div class="mui-input-row mui-radio">
                    <label>否</label>
                    <input name="set[type2]" type="radio" <?=$remind_set['type2'] ? '' : 'checked' ?> value="0">
                </div>
            </div>
            <div class="xuan_body">
                <span class="xuan_tit">考勤信息：</span>

                <div class="mui-input-row mui-radio">
                    <label>是</label>
                    <input name="set[type3]" type="radio" <?=$remind_set['type3'] ? 'checked' : '' ?>  value="1">
                </div>

                <div class="mui-input-row mui-radio">
                    <label>否</label>
                    <input name="set[type3]" type="radio" <?=$remind_set['type3'] ? '' : 'checked' ?> value="0">
                </div>
            </div>

            <div style="clear:both"></div>
            <div class="mui-button-row" style="margin:5% 0;">
                <input type="hidden" name="<?=Yii::$app->getRequest()->csrfParam ?>" value="<?=Yii::$app->getRequest()->getCsrfToken() ?>">

                <button class="mui-btn mui-btn-primary" type="sumbit">确认</button>&nbsp;&nbsp;
                <button class="mui-btn mui-btn-danger" onclick="window.history.go(-1);return false;">取消</button>
            </div>
        </div>
    </form>

</div>
<div class="clear"></div>
<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>
    </div>
</footer>

</body>
</html>
