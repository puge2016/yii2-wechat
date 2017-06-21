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

    <title>在岗查询</title>

    <link class="base-css" rel="stylesheet" href="/Public/statics/common/wechat/css/common.min.css?v=20170527">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/weui-0.4.2.min.css?v=2017052752">
    <link rel="stylesheet" href="/Public/statics/common/wechat/css/jquery-weui-0.7.min.css?v=2017052752">
    <link rel="stylesheet" href="/Public/statics/attend/wechat/css/updateyang.min.css?v=2017052752">

    <script type="text/javascript">
        var cdnUrl="<?=$cdnUrl ?>";
        var noneimg="/Public/statics/assets/common/img/none.png";
        var siteServer = '<?=$siteServer ?>';
        var jsVersion = '<?=$jsVersion ?>' ;
        var modulejsVersion="<?=$modulejsVersion ?>";
    </script>

    <script src="<?=$siteServer ?>static/f=Public/statics/common/wechat/js/zepto-1.1.6.min.js,Public/statics/common/wechat/js/core.min.js?v=<?=$jsVersion ?>" type="text/javascript"></script>
    <script src="<?=$siteServer ?>static/f=Public/statics/common/wechat/js/core/import.min.js,Public/statics/common/wechat/js/core/importConfigView.min.js?v=<?=$jsVersion ?>" type="text/javascript"></script>


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
        body,html {
            -ms-overflow-style: none !important;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        body {
            overflow-x: hidden;
            -webkit-text-size-adjust: none;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000000;
            font-size: 14px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-box-align: stretch;
            background: #fffcf3;
            padding: 0;
            margin: 0;
        }
        .view,.main {
            display: -webkit-box;
            display: -moz-box;
            display: box;
            display: -ms-flexbox;
            -webkit-box-orient: vertical;
            -moz-box-orient: vertical;
            -ms-box-orient: vertical;
            box-orient: vertical;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: flex;
            -webkit-flex-direction: column;
            -moz-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            height: 100%;
            width: 100%;
        }
        .view header {
            background: #0088d1;
            border: none;
            border-bottom: 1px solid #0088d1;
            color: #ffffff;
        }
        .pages {
            z-index: 10;
            position: relative;
            -webkit-box-flex: 1;
            -moz-box-flex: 1;
            -ms-box-flex: 1;
            box-flex: 1;
            -webkit-flex: 1;
            -moz-flex: 1;
            -ms-flex: 1;
            flex: 1;
            color: #000000;
            overflow: auto;
        }
        .view footer {
            padding:20px 0 10px 0;
            width: 100%;
            text-align: center;
            color: #8c8c8c;
            font-size: 12px;
        }

    </style>
    <div class="view yhome" id="mainview">
        <div id="sdsds" class="pages">
            <div class="search-guard">
                <input type="search" name="search" id="searchInput" placeholder="看看其他人是否在岗" />
                <span class="searchbotton" id="searchBotton"></span>
            </div>
            <div class="staff">
                <!--<div class="info">-->
                <span class="info"><span class="img-span" ><img mark='useravatar'  src=""/></span><br />
				<span class="name-span" mark='username'></span>   <br />
				<font mark='department'></font>&nbsp;&nbsp;<font mark='position' class="clapos"></font></span>
                <!--</div>-->
                <div class="staff-button hide" id="userChange">
                    <!--div class="buttonK">
                        <div class="button">
                            <a class="changestatus">
                                <span status=""></span>
                            </a>
                           </div>
                        <font class="currentstatus"></font>
                       </div-->
                    <div class="mui-input-row switch-wrap clearfix" data-type="switch" data-open="开启" data-close="关闭">
                        <div class="<?= ($status_set==1) ? 'open' : 'close' ?>" style="float: right;"><span></span><ins></ins></div>
                    </div>


                    <dl>
                        <dd>&#12288;开启：允许别人查看我的在岗状态</dd>
                        <dd>关闭：不允许别人查看我的在岗状态</dd>
                    </dl>
                </div>
            </div>
            <div class="status"><span>在岗状态</span></div>
            <article id="userStatus" class="user-status">
                <ul class="status-ul clearfix">
                    <li><span>在 岗</span></li>
                    <li><span>不在岗</span></li>
                    <li><span>保 密</span></li>
                    <li><span>未 知</span></li>
                </ul>

                <section>
                    <div class="on"><span>奋力工作中~~~</span></div>
                    <div class="hide"><span>TA现在不在工位哦~~~</span></div>
                    <div class="hide"><span>嘘，人家不想让看见在岗状态啦~~~</span></div>
                    <div class="hide"><span>好像哪里出了问题，找程序员哥哥看一下吧~~~</span></div>
                </section>
            </article>
        </div>
        <footer>
            <div>说明：在岗状态以打卡数据为准，仅供参考！</div>
        </footer>

    </div>


    <script type="text/javascript">

        (function(){
            var __ASSETS__ = "https://cdn.bangongyi.com/modules/attend/assets/",
                    $userStatus = $('#userStatus'),
                    $userChange = $('#userChange'),
                    $searchBotton = $('#searchBotton'),
                    $searchInput = $('#searchInput'),
                    $statusUl = $userStatus.children('ul'),
                    Yi = {
                        init : function() {
                            this.bindEvent();
                        },
                        tab : function(){
                            //tab切换
                            $userStatus.find('li').on('click',function(){
                                var index = $(this).index();
                                //$(this).addClass('curr').siblings().removeClass('curr');
                                $userStatus.find('section > div').eq(index).show().siblings().hide();
                            });
                            $userStatus.find('section div').each(function(i,e){
                                var img = $('<img />');
                                $(e).append(img.attr('src','/Public/statics/attend/wechat/images/img46_1.png'));
                            });
                        },

                        ajaxStatus : function(val,type,target){  //target  0：默认    1：为切换状态触发
                            $.ajax({
                                type:'post',
                                url:'/attend/index/status',
                                data:{
                                    search: val,
                                    type: type,
                                    '_csrf-frontend':'<?=Yii::$app->getRequest()->getCsrfToken() ?>'
                                },
                                dataType:'json',
                                cache:false,
                                success:function(data){
                                    //console.info(data)
                                    if(data.error!=1){
                                        /*YI.alert({
                                         msg:data.status
                                         });*/
                                        $.alert(data.status);
                                    }else{
                                        if(target==1){
                                            /*YI.alert({
                                             msg : data.errormsg
                                             });*/
                                            $.alert(data.errormsg);
                                        };
                                        data.isMe?$userChange.show():$userChange.hide();
                                        data.info.we_gender==1?$statusUl.addClass('man'):$statusUl.addClass('woman');

                                        $("[mark=username]").html(data.info.we_name);
                                        $("[mark=position]").html(data.info.we_position);
                                        $("[mark=gender]").html(data.info.we_gender);
                                        $("[mark=department]").html(data.info.we_department);
                                        $("[mark=useravatar]")
                                                .attr("src",data.info.we_avatar?data.info.we_avatar:'/Public/statics/common/wechat/images/picm.jpg');
                                        $("[mark=userstatus]").html(data.errormsg);

                                        $userStatus.find('li').eq(1).find("span").html("不在岗");

                                        //data.errormsg  1：在岗    2：不在岗     3：保密     4：未知
                                        (function(i){
                                            $userStatus.find('li').eq(i).addClass('curr').trigger('click')
                                                    .siblings().removeClass('curr');
                                            if(i==1 && data.status_detail){
                                                $userStatus.find('li').eq(1).find("span").html(data.status_detail);
                                            }
                                            $userStatus.find('section > div').eq(i)
                                                    .addClass('on').siblings().removeClass('on').end();

                                            $userStatus.find('section > div').eq(i)
                                                    .addClass('on').find('img').attr('src','/Public/statics/attend/wechat/images/img46.png');
                                        })(data.status-1);

                                    }
                                }
                            });
                        },

                        searchStatus : function(){
                            //查询
                            var _this = this;
                            $searchBotton.on("click",function(){
                                _this.ajaxStatus($.trim($searchInput.val()),0,0);
                            }).trigger('click');
                        },
                        changeStatus :　function(){
                            var _this = this;
                            $("[data-type='switch']").switched(function(){
                                _this.ajaxStatus('',1,1);
                                /*if(this.container.data('value')==1){
                                 $("#tab1").show();
                                 $("#tab2").hide();
                                 }else{
                                 $("#tab2").show();
                                 $("#tab1").hide();
                                 };*/
                            });
                        },
                        bindEvent : function(){
                            this.tab();
                            this.searchStatus();
                            this.changeStatus();
                        }
                    };
            $(function() {
                //modulejsVersion = new Date().getTime();
                require.config({
                    //jsCompress:false,
                    jsVersion:modulejsVersion
                })('apply','/Public/statics/common/wechat/js/jquery-weui-0.7,/Public/statics/common/wechat/plugins/switch',function(){
                    Yi.init();
                });
            });
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
