<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>外勤打卡</title>
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


    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=20170310">

    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion="<?=$jsVersion ?>";
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>
    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/zepto-1.1.6.min.js,/Public/statics/common/wechat/js/core.min.js?v=20170429" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=/Public/statics/common/wechat/js/core/import.min.js,/Public/statics/common/wechat/js/core/importConfigView.min.js?v=20170429" type="text/javascript"></script>
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
        body, html,#allmap,.main,.panel-wrap{width: 100%;height: 100%; overflow:scroll; margin:0;font-family:"微软雅黑";}
        #allmap{height:300px;width:100%}.panel-wrap{position:relative}.tips{color:#6c6c6c;position:fixed;left:0;right:0;top:0;height:30px;line-height:30px;text-align:center;text-indent: 5px;font-size:14px;z-index:999;background-color:rgba(255,255,255,.9)}
        #r-result{width:100%;position:absolute;bottom:0;top:300px;overflow-y:scroll}#allmap .BMap_bubble_title p a{display:none}.BMap_bubble_content tr:nth-child(2){display:none}
        #r-result li>div>div:nth-child(3){display:none}
    </style>

    <form id="panel">
        <!--<input type="text" name ="keyword" value="">-->
        <input type="hidden" name ="lng" value="<?=$lng ?>">
        <input type="hidden" name ="lat" value="<?=$lat ?>">
    </form>
    <p class="tips">先选择地址，再点击地图中的蓝色水滴确定</p>
    <div class="panel-wrap">
        <div id="allmap"></div>
        <div id="r-result"></div>
    </div>
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?=$baidu_ak ?>&s=1"></script>
    <script type="text/javascript">
        //$(function(){
        var $result=$("#r-result");
        var map = new BMap.Map("allmap",{minZoom:16});
        var x = <?=$lng ?>,y=<?=$lat ?>;
        var myKeys = ["\u5c42","\u5ea7","\u5927\u53a6","\u5e97","\u9986","\u56ed","\u4e2d\u5fc3","\u9662","\u8def","\u8857"];
        var point = new BMap.Point(x,y);
        map.centerAndZoom(point,16);
        map.enableScrollWheelZoom(true);
        var address='',
                title='';
        var local = new BMap.LocalSearch(map, {
            renderOptions: {map:map, panel: "r-result"},
            onMarkersSet:function(pois){
                for(var i=0;i<pois.length;i++){
                    pois[i].marker.addEventListener('click',function(e,i){
                        var addr=e.target;
                        openFn(addr)
                    })
                }
            },
            onInfoHtmlSet:function(poi){
                address=poi.address;
                title=poi.title;
            }
        });
        local.searchNearby(myKeys,point,500);
        map.addEventListener("tilesloaded",function(){
            $result.find("a").remove();
        })
        function openFn(addr){
            window.location.href="/attend/check/outwork?lat="+addr.point.lat + "&lng="+addr.point.lng+'&point_content='+address+'&point_title='+title;
        }

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
