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

    <title>打卡成功</title>
    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=201705091">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/animate.min.css?v=20170509152">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/yaoresult.min.css?v=20170509152">
    <script type="text/javascript">
    var cdnUrl="<?=$cdnUrl ?>";
    var noneimg="/Public/assets/common/img/none.png";
    var siteServer = '<?=$siteServer ?>';
    var jsVersion="<?=$jsVersion ?>";
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
            body{ background-color: #fff;}
            .route-tips{
                display: block;
                padding: 40px 15px 10px 15px;
                color: #797979;
                line-height: 25px;
                font-family: "microsoft yahei";
            }
            .route-tips p{
                padding-top: 6px;
            }
            .attend-result h1{padding: 12px 0 0px;}
            .attend-result ul li{padding-bottom: 12px;}
        </style>

        <div id="forward" onClick="javascrit:$(this).css('display','none');" style="background:#000; z-index:9;height:100%;opacity: 0.7; display:none;position:absolute;width: 100%;top:0;left:0; display:none;">
            <img src="/Public/statics/attend/wechat/images/fx.png" />
        </div>

        <section class="attend-result">
            <h1>打卡成功</h1>
            <h2 style="padding-bottom:0px ;">
                <ul>
                    <li>打卡时间<?=$clockTime ?></li>
                </ul>
            </h2>
            <div class="route-tips" style="text-align: center; padding-bottom:12px;padding-top: 0px;"><a style="color:#5b9bd1 ;" href="/attend/index/record">考勤记录></a></div>
            <img id="animateImg" class="share-img animated" src="/Public/statics/attend/wechat/images/s.png" height="168" />
        </section>

        <div class="resource-space" style="padding: 30px 0;">
        </div>

        <script type="text/javascript">
            $(function(){
                setTimeout(function(){
                    $('#animateImg').addClass('rollIn');
                },150);
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
