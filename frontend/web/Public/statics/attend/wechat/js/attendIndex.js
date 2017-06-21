;(function($){
    // removeCookie('bgyupdatewechat'+userId);
    var wechat = navigator.userAgent.match(/MicroMessenger\/([\d\.]+)/i);
    if(wechat){
        var wechatInfo = wechat[1].split('.');
        var updatecookies = getCookie('bgyupdatewechat'+userId);
        if((updatecookies != "" && updatecookies != userId ) || updatecookies == ""){
            //上线记得改版本号 6.3.7
            if(wechatInfo[0]<6 || (wechatInfo[0] == 6 && (wechatInfo[1]<3 || (wechatInfo[1] == 3 && (wechatInfo[2] <7 || wechatInfo[2] == 7))))) {  
                if ($.os.ios) {
                    YI.alert({
                        msg: '为了提升用户体验，建议升级到微信最新版本'
                    });
                }else{
                    YI.confirm({
                        msg:'为了提升用户体验，建议升级到微信最新版本',
                        yText: '立即更新',
                        nText: '暂不更新',
                        yFn: function(){
                            window.location.href = 'http://android.app.qq.com/myapp/detail.htm?apkName=com.tencent.mm';
                        }
                    });
                }
                setCookie('bgyupdatewechat'+userId, userId, 3);
                // setCookie('bgyupdatewechat'+userId, userId, 1); //测试用、设置过期时间1分钟
            }
        }
    }

    function setCookie(name, value, iDay){
        var oDate=new Date();
        oDate.setDate(oDate.getDate()+iDay);
        // oDate.setTime(oDate.getTime()+iDay*60*1000);//测试用、设置过期时间为分钟
        document.cookie=name+'='+encodeURIComponent(value)+';expires='+oDate.toGMTString();
    }

    function getCookie(name){
        var arr=document.cookie.split('; ');
        var i=0;
        for(i=0;i<arr.length;i++){
            //arr2->['username', 'abc']
            var arr2=arr[i].split('=');
            if(arr2[0]==name){
                var getC = decodeURIComponent(arr2[1]);
                return getC;
            }
        }
        return '';
    }

    function removeCookie(name){
        setCookie(name, '1', -1);
    }

    var $showBtn = $('#showBtn'),
        $checkStatus = $('.checkStatus'),
        $freshBtn = $('#freshBtn'),
        $indexUl = $('#indexUl'),
        $timer = $('.timer'),
        $openBtn = $('#openBtn'),
        $showCheck = $('.showCheck'),
        _showBtn = false,
        index = {
            init : function(){
                //console.log($deviceList);


                $.each($deviceList,function(i,e){
                    /*if(e.type==1 || e.type==0 ||e.type==7){
                        _showBtn = true;
                        return false;
                    };*/

                    if($.inArray(e.type,$deviceArr)!=-1){
						_showBtn = true;
                        return false;
                    };

                });

                if(!_showBtn && !$isOutWork){
                    $showCheck.hide();
                    $showCheck.parents('.t').css('height','55px');
                };

               this.setTime();
               this.bindEvent();
            },
            
            setTime : function(){


				function getLocalTime(servertime,i) {
			        //参数i为时区值数字，比如北京为东八区则输进8,西5输入-5
			        if (typeof i !== 'number') return;
			        var d = new Date(servertime*1000);
			        //得到1970年一月一日到现在的秒数
			        var len = d.getTime();
			        //本地时间与GMT时间的时间偏移差
			        var offset = d.getTimezoneOffset() * 60000;
			        //得到现在的格林尼治时间
			        var utcTime = len + offset;
			        return new Date(utcTime + 3600000 * i);
			    };
			    serverTime = getLocalTime(serverTime,8).getTime();
			    
                //console.info(serverTime)
                new Vue({
                    el : '#attendWrap',
                    data : {
                        time : ''
                    },
                    ready : function(){
                        var _this = this;
                        //serverTime = serverTime*1000;
                        //console.info(serverTime);
                        function getTimer(){
                            var d = new Date(serverTime),
                                h = d.getHours(),
                                m = d.getMinutes(),
                                s = d.getSeconds();
                            if(h<10){ h="0"+h; };
                            if(m<10){ m="0"+m; };
                            if(s<10){ s="0"+s; };                   
                            _this.time = h+":"+m+":"+s;
                            serverTime += 1000;
                        };
                        getTimer();
                        setInterval(getTimer,1000);
                    }
                });
                   
            },
            
            
            
            bindEvent : function(){   
                var _this = this;
                setTimeout(function(){
                    $indexUl.find('li a').children('em').addClass('animated rubberBand');
                    setTimeout(function(){
                        _this.openBtnClick();
                    },100);
                },250);

                var $is = $indexUl.find('li i');
                $.each($is,function(i,e){
                    var num = $(e).data('num');
                    if(num>0 && num<100){
                        $(e).html(num).show();
                    };
                    if(num>=100){
                         $(e).html('···').show();
                    };
                });
                
                //this.beforeFun();
            },
            openBtnClick : function(){  
                $openBtn.on('click',function(){
                    var _url = $(this).data('url');
                    YI.loading();
                    $.ajax({
                        url : _url,
                        cache: false,
                        method : 'get',
                        dataType: 'json',
                        success : function(data){
                            //console.info(data);
                            if(data.errno=="0"){
                                location.href = _url;
                    location.href="/attend/check/success?aid="+aid+"&userid="+userId+"&date="+serverDate;
                            }else if(data.errno==390201){
                                location.href = _url
                            }else{
                                YI.alert({
                                    msg : data.errmsg || 'Wifi打卡不在范围内'
                                });
                            };
                        }
                    });
                });
            },
            
            beforeFun : function(){
                var $isIp = $isGps = 0;
                //console.info($deviceList);
                //($deviceList.type==0)?($isGps = 1):($isIp = 1);
                var hasDK = true;
                $.each($deviceList,function(i,e){
                    if(e.type==0){
                        $isGps = 1;
                        hasDK = false;
                    }else if(e.type==1){
                        $isIp = 1;
                        hasDK = false;
                    }
                });
                
                if(hasDK){
                    $checkStatus.html('管理员已关闭微信打卡');
                }
                
                
                //console.info($isGps,$isIp);
                
                if($isGps==1){  
                    verifyShake();
                };
                if($isIp==1){
                    verifyIp();
                };
                var isAllowCheck = 0;//是否在考勤范围内
                var checkBtn = 0;
                
                
                
                
                //ip打卡
                function checkIp(){
                    $.ajax({
                        type:'post',
                        url:'/attend/check/ip',
                        data:'',
                        dataType:'json',
                        success:function(data){
                            //console.info(data);
                            /*var cancel = {
                                'content' : data.errmsg || '打卡成功',
                                'yes'   : '确定',
                            };*/
                            if(data.errno==0){
                                $(".check").hide();
                            }
                            //$.fn.dialog(cancel);

                            YI.alert({
                                msg : data.errmsg || '打卡成功'
                            });
                        },
                    });
                }
                //ip打卡
                function verifyIp(){
                    $.ajax({
                        type:'get',
                        url:'/attend/check/ip',
                        data:'',
                        dataType:'json',
                        success:function(data){
                            //console.info(data)
                            if(data.errno==0){
                                isAllowCheck = 1;
                                checkBtn+=1;
                                $showBtn.show();
                                $(".checkStatus").html("您已在考勤范围内");
                                
                            }else{
                                $(".checkStatus").html("您不在考勤范围内");
                                $showBtn.hide();
                            };
                            $freshBtn.show();
                        }
                    });  
                }
                var lat=lng=0;
                //Gps打卡
                function verifyShake(){
                    var geolocation = new BMap.Geolocation();
                    geolocation.getCurrentPosition(function(r){
                    if(this.getStatus() == BMAP_STATUS_SUCCESS){
                        lat = r.point.lat;
                        lng = r.point.lng;
                        $.ajax({
                            type:'get',
                            url:'/attend/check/gps?lat='+r.point.lat+'&lng='+r.point.lng,
                            data:'',
                            dataType:'json',
                            success:function(data){
                                //console.info(data);
                                if(data.errno==0){
                                    checkBtn+=10;
                                    isAllowCheck = 1;
                                    $showBtn.show();
                                    $(".checkStatus").html("您已在考勤范围内");
                                }else{
                                    if(isAllowCheck==0)$(".checkStatus").html("您不在考勤范围内");
                                    $showBtn.hide();
                                };
                                
                                $freshBtn.show();
                            }
                        });
                    }       
                    },{enableHighAccuracy: true,timeout:5000,maximumAge:60*1000})
                }
                //摇一摇打卡(地理位置)
                function checkShake(){
                    if(lat==0) return false;
                    var postdata = {"lat":lat,"lng":lng};
                    $.ajax({
                        type:'post',
                        url:'/attend/check/gps',
                        data:postdata,
                        dataType:'json',
                        success:function(data){
                            /*var cancel = {
                                'content' : data.errmsg || '打卡成功',
                                'yes'   : '确定',
                            };
                            $.fn.dialog(cancel);*/
                            
                            if(data.errno==0){
                                $(".check").hide();
                            }
                            YI.alert({
                                msg : data.errmsg || '打卡成功'
                            });
                            
                            
                        },
                    });
                }
                $(".checkBtn").on("click",function(){
                    
                    //alert(checkBtn)
                    //ip 打卡
                    if(checkBtn==1) checkIp();
                    //GPs 打卡
                    if(checkBtn==10) checkShake();
                    //GPs 打卡
                    if(checkBtn==11){ checkShake();checkIp();}
                });
                
        
                $('#freshBtn').on('click',function(){
                    verifyIp();
                    verifyShake();
                });
            }
        };
    
    
    $(function(){
        index.init();
    });
})(Zepto);
